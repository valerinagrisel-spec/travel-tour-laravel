@extends('laporan.layout')
@section('title', 'Laporan Transaksi - Travel Tour')
@section('laporan-content')
<div class="lap-header"><div><span class="lap-eyebrow">{{ $mode === 'staff' ? 'STAFF' : 'MANAGER' }} / LAPORAN</span><h1>Laporan Transaksi</h1><p>Ringkasan transaksi dengan pembayaran yang telah diverifikasi.</p></div></div>
<form method="GET" action="{{ $mode === 'staff' ? route('staff.laporan.index') : route('manager.laporan.index') }}" class="lap-filter">
    <label>Periode Awal <input type="date" name="dari" value="{{ $dari }}" required></label>
    <label>Periode Akhir <input type="date" name="sampai" value="{{ $sampai }}" required></label>
    <button type="submit" class="lap-primary">Tampilkan</button>
</form>
<div class="lap-statgrid"><div class="lap-stat"><small>Transaksi Terverifikasi</small><strong>{{ number_format($jumlah,0,',','.') }}</strong></div><div class="lap-stat"><small>Total Penerimaan Terverifikasi</small><strong>Rp {{ number_format($total,0,',','.') }}</strong></div></div>
<div class="lap-toolbar"><h2>Daftar Transaksi</h2><div class="lap-buttons">
@if($jumlah > 0 && $mode === 'staff')
<form action="{{ route('staff.laporan.kirim') }}" method="POST">@csrf<input type="hidden" name="dari" value="{{ $dari }}"><input type="hidden" name="sampai" value="{{ $sampai }}"><button type="submit" class="lap-primary">Kirim Laporan</button></form>
@elseif($jumlah > 0 && $mode === 'manager')
<a class="lap-primary" href="{{ route('manager.laporan.pdf',['dari'=>$dari,'sampai'=>$sampai]) }}">Ekspor PDF</a><a class="lap-primary" href="{{ route('manager.laporan.excel',['dari'=>$dari,'sampai'=>$sampai]) }}">Ekspor Excel</a>
@endif
</div></div>
<div class="lap-tablewrap"><table class="lap-table"><thead><tr><th>No</th><th>Tanggal</th><th>Kode</th><th>Pelanggan</th><th>Paket</th><th>Invoice</th><th>Nominal</th><th>Status</th></tr></thead><tbody>
@forelse($transaksi as $index => $p)
<tr><td>{{ $index+1 }}</td><td>{{ $p->tanggal_bayar?->format('d/m/Y') }}</td><td>{{ $p->pemesanan?->kode_pemesanan }}</td><td>{{ $p->pemesanan?->pemesan?->name }}</td><td>{{ $p->pemesanan?->paket?->nama_paket }}</td><td>{{ $p->nomor_invoice }}</td><td>Rp {{ number_format($p->nominal,0,',','.') }}</td><td><span class="lap-status">Terverifikasi</span></td></tr>
@empty<tr><td colspan="8" class="lap-empty">Laporan transaksi tidak tersedia pada periode ini.</td></tr>@endforelse
</tbody></table></div>
@if($mode === 'manager')
<section class="lap-sent"><h2>Riwayat Laporan dari Staff</h2><div class="lap-tablewrap"><table class="lap-table"><thead><tr><th>Dikirim</th><th>Nama Staff</th><th>Periode</th><th>Jumlah</th><th>Total Saat Dikirim</th><th>Aksi</th></tr></thead><tbody>
@forelse($laporanTerkirim as $item)
<tr><td>{{ $item->created_at->format('d/m/Y H:i') }}</td><td>{{ $item->pengirim?->name }}</td><td>{{ $item->periode_awal->format('d/m/Y') }} – {{ $item->periode_akhir->format('d/m/Y') }}</td><td>{{ $item->jumlah_transaksi }}</td><td>Rp {{ number_format($item->total_nominal,0,',','.') }}</td><td><a href="{{ route('manager.laporan.index',['dari'=>$item->periode_awal->format('Y-m-d'),'sampai'=>$item->periode_akhir->format('Y-m-d')]) }}">Tinjau</a></td></tr>
@empty<tr><td colspan="6" class="lap-empty">Belum ada laporan yang dikirim Staff.</td></tr>@endforelse
</tbody></table></div></section>
@endif
@endsection
