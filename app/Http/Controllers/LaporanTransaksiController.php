<?php

namespace App\Http\Controllers;

use App\Models\LaporanTerkirim;
use App\Models\Pembayaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaporanTransaksiController extends Controller
{
    private function role(string $role): void
    {
        abort_unless(Auth::check() && Auth::user()->role === $role, 403);
    }

    private function periode(Request $request): array
    {
        $values = $request->validate([
            'dari' => ['nullable', 'date_format:Y-m-d', 'before_or_equal:sampai'],
            'sampai' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:dari'],
        ]);

        $dari = $values['dari'] ?? now()->startOfMonth()->toDateString();
        $sampai = $values['sampai'] ?? now()->toDateString();

        if ($dari > $sampai) {
            throw ValidationException::withMessages(['dari' => 'Tanggal awal tidak boleh melewati tanggal akhir.']);
        }

        return [$dari, $sampai];
    }

    private function transaksi(string $dari, string $sampai)
    {
        return Pembayaran::query()
            ->with(['pemesanan.pemesan', 'pemesanan.paket'])
            ->where('status', 'terverifikasi')
            ->whereDate('tanggal_bayar', '>=', $dari)
            ->whereDate('tanggal_bayar', '<=', $sampai)
            ->orderBy('tanggal_bayar')
            ->orderBy('id');
    }

    private function ringkasan(string $dari, string $sampai): array
    {
        $data = $this->transaksi($dari, $sampai)->get();
        return [$data, $data->count(), $data->sum(fn($t) => (float) $t->nominal)];
    }

    public function staff(Request $request)
    {
        $this->role('staff');
        [$dari, $sampai] = $this->periode($request);
        [$transaksi, $jumlah, $total] = $this->ringkasan($dari, $sampai);
        return view('laporan.index', compact('dari', 'sampai', 'transaksi', 'jumlah', 'total') + ['mode' => 'staff', 'laporanTerkirim' => collect()]);
    }

    public function kirim(Request $request)
    {
        $this->role('staff');
        // Periode disampaikan melalui input POST, bukan dipercayakan dari total yang dikirim browser.
        [$dari, $sampai] = $this->periode($request);
        [$transaksi, $jumlah, $total] = $this->ringkasan($dari, $sampai);
        if ($jumlah === 0) {
            throw ValidationException::withMessages(['dari' => 'Laporan transaksi tidak tersedia untuk periode ini.']);
        }

        DB::transaction(function () use ($dari, $sampai, $jumlah, $total) {
            LaporanTerkirim::create([
                'staff_user_id' => Auth::id(),
                'periode_awal' => $dari,
                'periode_akhir' => $sampai,
                'jumlah_transaksi' => $jumlah,
                'total_nominal' => $total,
            ]);
        });

        // Pengiriman ini adalah publikasi laporan ke daftar Manager di aplikasi, bukan email.
        return redirect()->route('staff.laporan.index', ['dari' => $dari, 'sampai' => $sampai])
            ->with('success', 'Laporan berhasil dikirim ke Manager melalui aplikasi.');
    }

    public function manager(Request $request)
    {
        $this->role('manager');
        [$dari, $sampai] = $this->periode($request);
        [$transaksi, $jumlah, $total] = $this->ringkasan($dari, $sampai);
        $laporanTerkirim = LaporanTerkirim::with('pengirim')->latest()->limit(30)->get();
        return view('laporan.index', compact('dari', 'sampai', 'transaksi', 'jumlah', 'total', 'laporanTerkirim') + ['mode' => 'manager']);
    }

    public function pdf(Request $request)
    {
        $this->role('manager');
        [$dari, $sampai] = $this->periode($request);
        [$transaksi, $jumlah, $total] = $this->ringkasan($dari, $sampai);
        abort_if($jumlah === 0, 404, 'Laporan transaksi tidak tersedia.');
        $pdf = Pdf::loadView('laporan.pdf', compact('dari', 'sampai', 'transaksi', 'jumlah', 'total'));
        return $pdf->setPaper('a4', 'landscape')->download("laporan-transaksi-{$dari}-{$sampai}.pdf");
    }

    public function excel(Request $request)
    {
        $this->role('manager');
        [$dari, $sampai] = $this->periode($request);
        [$transaksi, $jumlah, $total] = $this->ringkasan($dari, $sampai);
        abort_if($jumlah === 0, 404, 'Laporan transaksi tidak tersedia.');
        $sheetFile = new Spreadsheet();
        $sheet = $sheetFile->getActiveSheet();
        $sheet->setTitle('Transaksi');
        $sheet->fromArray([['Laporan Transaksi Travel Tour'], ["Periode: {$dari} s.d. {$sampai}"], [], ['No', 'Tanggal Bayar', 'Kode Pemesanan', 'Nama Pelanggan', 'Paket Travel', 'Invoice', 'Nominal (Rp)', 'Status Pembayaran']], null, 'A1');
        $row = 5;
        foreach ($transaksi as $i => $p) {
            $sheet->fromArray([
                $i + 1,
                optional($p->tanggal_bayar)->format('Y-m-d H:i'),
                $p->pemesanan?->kode_pemesanan,
                $p->pemesanan?->pemesan?->name,
                $p->pemesanan?->paket?->nama_paket,
                $p->nomor_invoice,
                (float) $p->nominal,
                'Terverifikasi',
            ], null, "A{$row}");
            $row++;
        }
        $sheet->setCellValue("F{$row}", 'TOTAL');
        $sheet->setCellValue("G{$row}", $total);
        $sheet->getStyle("G5:G{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet->getStyle('A4:H4')->getFont()->setBold(true);
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        $writer = new Xlsx($sheetFile);
        return response()->streamDownload(function () use ($writer, $sheetFile) {
            $writer->save('php://output');
            $sheetFile->disconnectWorksheets();
        }, "laporan-transaksi-{$dari}-{$sampai}.xlsx", [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
