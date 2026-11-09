@extends('travel.layout')
@section('title','Pesanan Saya - Travel Tour')
@section('travel-content')
<div class="travel-heading"><h1>Pesanan Saya</h1><a class="travel-button" href="{{ route('travel.katalog') }}">Pilih Paket +</a></div>
<div class="travel-table-wrap"><table class="travel-table"><thead><tr><th>Kode</th><th>Paket</th><th>Berangkat</th><th>Total</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
@forelse($pesanans as $p)<tr><td>{{ $p->kode_pemesanan }}</td><td>{{ $p->paket?->nama_paket }}</td><td>{{ $p->tanggal_berangkat->format('d/m/Y') }}</td><td>Rp {{ number_format($p->total_pembayaran,0,',','.') }}</td><td><span class="travel-status">{{ str_replace('_',' ',ucwords($p->status,'_')) }}</span></td><td><a href="{{ route('customer.pesanan.show',$p) }}">Detail →</a></td></tr>
@empty<tr><td colspan="6">Kamu belum mempunyai pesanan.</td></tr>@endforelse
</tbody></table></div>{{ $pesanans->links() }}
@endsection
