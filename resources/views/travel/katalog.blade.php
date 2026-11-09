@extends('travel.layout')
@section('title','Paket Wisata - Travel Tour')
@section('travel-content')
<section class="travel-hero"><span>EXPLORE • DISCOVER • TRAVEL</span><h1>Temukan perjalanan<br><em>impianmu.</em></h1><p>Pilih paket travel dan nikmati petualangan yang berkesan.</p></section>
<div class="travel-heading"><h2>Paket Travel</h2><form method="GET"><input name="cari" placeholder="Cari destinasi..." value="{{ request('cari') }}"><button>Cari</button></form></div>
<div class="travel-cards">
@forelse($pakets as $paket)
<article class="travel-card">
@if($paket->foto)<img src="{{ asset('storage/'.$paket->foto) }}" alt="Foto {{ $paket->nama_paket }}">@else<div class="travel-placeholder">✦</div>@endif
<div class="travel-card-body"><small>{{ $paket->tujuan }} • {{ $paket->durasi_hari }} hari • {{ $paket->tanggal_berangkat->format('d M Y') }}</small><h3>{{ $paket->nama_paket }}</h3>
<p>Kuota total: {{ $paket->kuota }} peserta</p><strong>Rp {{ number_format($paket->harga,0,',','.') }}</strong>
<a class="travel-button" href="{{ route('travel.detail',$paket) }}">Lihat Detail ↗</a></div></article>
@empty<p>Belum ada paket perjalanan tersedia.</p>@endforelse
</div><div class="travel-paginate">{{ $pakets->links() }}</div>
@endsection
