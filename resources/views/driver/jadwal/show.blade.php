@extends('driver.layout')
@section('title','Detail Jadwal Tour - Travel Tour')
@section('driver-content')
<h1 class="tt-title">Detail Jadwal Tour</h1><div class="pj-panel pj-detail">
 <div><strong>Paket Wisata</strong><span>{{ $penugasan->pemesanan?->paket?->nama_paket }}</span></div>
 <div><strong>Tujuan</strong><span>{{ $penugasan->pemesanan?->paket?->tujuan }}</span></div>
 <div><strong>Jadwal</strong><span>{{ $penugasan->tanggal_mulai->format('d/m/Y') }} – {{ $penugasan->tanggal_selesai->format('d/m/Y') }}</span></div>
 <div><strong>Titik Kumpul</strong><span>{{ $penugasan->titik_kumpul }}</span></div>
 <div><strong>Jam Kumpul</strong><span>{{ substr($penugasan->jam_kumpul,0,5) }}</span></div>
 <div><strong>Jumlah Peserta</strong><span>{{ $penugasan->pemesanan?->jumlah_peserta }} orang</span></div>
 <div><strong>Kendaraan</strong><span>{{ $penugasan->kendaraan?->merek }} / {{ $penugasan->kendaraan?->plat_nomor }}</span></div>
 <div><strong>Catatan</strong><span>{{ $penugasan->catatan ?: '-' }}</span></div>
</div><a href="{{ route('driver.jadwal.index') }}" class="tt-btn" style="margin-top:15px">Kembali ke Jadwal Tour</a>
@endsection
