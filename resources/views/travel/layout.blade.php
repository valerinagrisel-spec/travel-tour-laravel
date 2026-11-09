@extends('layouts.app')
@section('content')
<div class="travel-site">
<header class="travel-top"><a class="travel-brand" href="{{ route('travel.katalog') }}">TRAVEL <span>TOUR✦</span></a>
<nav><a href="{{ route('travel.katalog') }}">Paket Wisata</a>
@auth
@if(auth()->user()->role==='customer')<a href="{{ route('customer.pesanan.index') }}">Pesanan Saya</a>@else<a href="{{ route('dashboard') }}">Dashboard</a>@endif
<form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">Keluar</button></form>
@else
<a href="{{ route('login') }}">Masuk</a><a class="travel-pill" href="{{ route('register') }}">Daftar</a>
@endauth</nav></header>
<main class="travel-body">
@if(session('success'))<div class="travel-alert success">{{ session('success') }}</div>@endif
@if($errors->any())<div class="travel-alert error"><strong>Periksa formulir:</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
@yield('travel-content')
</main></div>
@endsection
