@extends('driver.layout')
@section('title','Jadwal Tour - Travel Tour')
@section('driver-content')
<h1 class="tt-title">Jadwal Tour</h1><p class="pj-help">Driver: {{ $driver->nama }}. Pilih Detail untuk melihat informasi perjalanan.</p>
<div class="tt-table-wrap"><table class="tt-table"><thead><tr><th>No.</th><th>Paket</th><th>Periode</th><th>Plat Nomor</th><th>Aksi</th></tr></thead><tbody>
@forelse($jadwals as $item)<tr><td>{{ $loop->iteration + ($jadwals->currentPage()-1)*$jadwals->perPage() }}</td><td>{{ $item->pemesanan?->paket?->nama_paket }}</td><td>{{ $item->tanggal_mulai->format('d/m/Y') }}–{{ $item->tanggal_selesai->format('d/m/Y') }}</td><td>{{ $item->kendaraan?->plat_nomor }}</td><td><a class="tt-btn" href="{{ route('driver.jadwal.show',$item) }}">Detail</a></td></tr>
@empty<tr><td class="tt-empty" colspan="5">Jadwal tour tidak tersedia.</td></tr>@endforelse
</tbody></table></div><div class="tt-pagination">{{ $jadwals->links() }}</div>
@endsection
