@extends('admin.layout')
@section('admin-content')
<h1 class="tt-title">Dashboard</h1>
<div class="tt-stats">
    <div class="tt-stat"><strong>{{ $paketCount }}</strong><span>Total Paket Travel</span></div>
    <div class="tt-stat"><strong>{{ $driverCount }}</strong><span>Driver Terdaftar</span></div>
    <div class="tt-stat"><strong>{{ $kendaraanCount }}</strong><span>Total Kendaraan</span></div>
    <div class="tt-stat"><strong>{{ $tersediaCount }}</strong><span>Kendaraan Tersedia</span></div>
</div>
<div class="tt-grid2">
    <section class="tt-panel">
        <h2>Status Penugasan</h2>
        <div class="tt-pie"><b>{{ $kendaraanCount }}</b><small>kendaraan</small></div>
        <div class="tt-legend"><span>● Tersedia: {{ $tersediaCount }}</span><span>● Digunakan: {{ $digunakanCount }}</span><span>● Perawatan: {{ $perawatanCount }}</span></div>
    </section>
    <section class="tt-panel">
        <h2>Ketersediaan Kendaraan</h2>

        <div class="tt-meter-label">
            Tersedia <b>{{ $tersediaCount }}</b>
        </div>
        <div class="tt-meter">
            <i data-width="{{ $kendaraanCount ? round($tersediaCount * 100 / $kendaraanCount) : 0 }}"></i>
        </div>

        <div class="tt-meter-label">
            Digunakan <b>{{ $digunakanCount }}</b>
        </div>
        <div class="tt-meter">
            <i data-width="{{ $kendaraanCount ? round($digunakanCount * 100 / $kendaraanCount) : 0 }}"></i>
        </div>

        <div class="tt-meter-label">
            Perawatan <b>{{ $perawatanCount }}</b>
        </div>
        <div class="tt-meter">
            <i data-width="{{ $kendaraanCount ? round($perawatanCount * 100 / $kendaraanCount) : 0 }}"></i>
        </div>

    </section>
    <section class="tt-panel">
        <h2>Paket Travel Terdaftar</h2>@forelse($paketTerpopuler as $p)<div class="tt-kv">{{ $p->nama_paket }} <span>{{ $p->kuota }} kuota</span></div>@empty<p>Belum ada paket wisata.</p>@endforelse
    </section>
    <section class="tt-panel">
        <h2>Aktivitas Terbaru</h2>
        <p>Belum ada aktivitas tercatat. Riwayat aktivitas akan ditambahkan bersama modul transaksi.</p>
    </section>
</div>

<script>
    document.querySelectorAll('.tt-meter i[data-width]').forEach(function(bar) {
        const value = Number(bar.dataset.width) || 0;
        bar.style.width = Math.max(0, Math.min(100, value)) + '%';
    });
</script>

@endsection