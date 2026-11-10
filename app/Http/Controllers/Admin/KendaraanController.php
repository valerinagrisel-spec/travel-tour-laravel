<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class KendaraanController extends Controller
{
    // PC-14: Mengambil data master Kendaraan
    public function getDataMaster()
    {
        return Kendaraan::all();
    }

    // PC-13: Menampilkan daftar Kendaraan
    public function getDaftarKendaraan(Request $request)
    {
        $items = Kendaraan::query()
            ->when($request->filled('cari'), function ($query) use ($request) {
                $cari = $request->input('cari');

                $query->where(function ($q) use ($cari) {
                    $q->where('plat_nomor', 'like', "%{$cari}%")
                        ->orWhere('jenis_kendaraan', 'like', "%{$cari}%")
                        ->orWhere('merek', 'like', "%{$cari}%");
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->input('status'));
            })
            ->latest()
            ->paginate(8)
            ->withQueryString();


        return view('admin.master.index', [
            'jenis' => 'kendaraan',
            'judul' => 'Kendaraan',
            'data' => $items,
        ]);
    }

    // PC-15: Mengambil satu data Kendaraan
    public function getKendaraan(string $id)
    {
        return Kendaraan::findOrFail($id);
    }

    // PC-16: Validasi dan tambah Kendaraan
    public function validasiTambahKendaraan(Request $request)
    {
        $data = $this->validasi($request);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')
                ->store('kendaraan', 'public');
        }

        Kendaraan::create($data);

        return redirect()
            ->route('admin.kendaraan.index')
            ->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    // PC-17: Validasi dan edit Kendaraan
    public function validasiEditKendaraan(Request $request, Kendaraan $kendaraan)
    {
        $data = $this->validasi($request, $kendaraan);

        if ($request->hasFile('foto')) {
            $fotoLama = $kendaraan->foto;

            $data['foto'] = $request->file('foto')
                ->store('kendaraan', 'public');
        }

        $kendaraan->update($data);

        if (isset($fotoLama) && $fotoLama) {
            Storage::disk('public')->delete($fotoLama);
        }

        return redirect()
            ->route('admin.kendaraan.index')
            ->with('success', 'Kendaraan berhasil diperbarui.');
    }

    // PC-18: Menghapus Kendaraan
    public function hapusKendaraan(Kendaraan $kendaraan)
    {
        $kendaraan->delete();

        return redirect()
            ->route('admin.kendaraan.index')
            ->with('success', 'Kendaraan dihapus dari daftar.');
    }

    // Penghubung route Laravel
    public function index(Request $request)
    {
        return $this->getDaftarKendaraan($request);
    }

    public function create()
    {
        return view('admin.master.form', [
            'jenis' => 'kendaraan',
            'judul' => 'Tambah Kendaraan',
            'item' => new Kendaraan,
        ]);
    }

    public function store(Request $request)
    {
        return $this->validasiTambahKendaraan($request);
    }

    public function edit(Kendaraan $kendaraan)
    {
        return view('admin.master.form', [
            'jenis' => 'kendaraan',
            'judul' => 'Edit Kendaraan',
            'item' => $this->getKendaraan($kendaraan->getKey()),
        ]);
    }

    public function update(Request $request, Kendaraan $kendaraan)
    {
        return $this->validasiEditKendaraan($request, $kendaraan);
    }

    public function destroy(Kendaraan $kendaraan)
    {
        return $this->hapusKendaraan($kendaraan);
    }

    // Aturan validasi input
    private function validasi(Request $request, ?Kendaraan $kendaraan = null): array
    {
        $platRule = Rule::unique('kendaraan', 'plat_nomor');

        if ($kendaraan !== null) {
            $platRule->ignore($kendaraan->getKey(), 'id_kendaraan');
        }

        return $request->validate([
            'plat_nomor' => ['required', 'string', 'max:20', $platRule],
            'jenis_kendaraan' => ['required', 'string', 'max:50'],
            'merek' => ['required', 'string', 'max:50'],
            'kapasitas' => ['required', 'integer', 'min:1', 'max:200'],
            'tahun_produksi' => [
                'required',
                'integer',
                'min:1980',
                'max:' . (date('Y') + 1),
            ],
            'status' => [
                'required',
                Rule::in(['tersedia', 'digunakan', 'perawatan', 'tidak_aktif']),
            ],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);
    }
}
