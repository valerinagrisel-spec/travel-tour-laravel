@extends('travel.layout')
@section('title',$paket->nama_paket.' - Travel Tour')
@section('travel-content')
<a href="{{ route('travel.katalog') }}">← Kembali ke paket</a>
<div class="travel-detail"><div>@if($paket->foto)<img class="travel-detail-img" src="{{ asset('storage/'.$paket->foto) }}" alt="{{ $paket->nama_paket }}">@else<div class="travel-placeholder tall">✦</div>@endif</div>
<div class="travel-panel"><div class="travel-kicker">{{ strtoupper($paket->tujuan) }}</div><h1>{{ $paket->nama_paket }}</h1>
<p>{{ $paket->deskripsi ?: 'Informasi paket perjalanan.' }}</p><dl><dt>Durasi</dt><dd>{{ $paket->durasi_hari }} hari</dd><dt>Keberangkatan</dt><dd>{{ $paket->tanggal_berangkat->format('d M Y') }}</dd><dt>Kuota total</dt><dd>{{ $paket->kuota }}</dd></dl>
<strong class="travel-price">Rp {{ number_format($paket->harga,0,',','.') }} / orang</strong>
@if($paket->tanggal_berangkat->isFuture() || $paket->tanggal_berangkat->isToday())
@auth
@if(auth()->user()->role==='customer')<a href="{{ route('customer.pemesanan.create',$paket) }}" class="travel-button">Pesan Sekarang ↗</a>@else<p>Masuk menggunakan akun Customer untuk memesan.</p>@endif
@else<a href="{{ route('login') }}" class="travel-button">Masuk untuk Memesan ↗</a>@endauth
@else<p>Jadwal keberangkatan sudah lewat.</p>@endif</div></div>
@endsection
