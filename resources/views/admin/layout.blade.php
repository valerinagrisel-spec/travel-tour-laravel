@extends('layouts.app')
@section('title', ($judul ?? 'Dashboard').' - Travel Tour')
@section('content')
<div class="tt-shell">
    <aside class="tt-sidebar">
        <div class="tt-logo">Travel<br>Tour<span>✦</span></div>
        <nav class="tt-nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'selected' : '' }}">▦ &nbsp; Dashboard</a>
            <a href="{{ route('admin.paket.index') }}" class="{{ request()->routeIs('admin.paket.*','admin.driver.*','admin.kendaraan.*') ? 'selected' : '' }}">▤ &nbsp; Data Master</a>
            <a href="{{ route('admin.penugasan.index') }}" class="{{ request()->routeIs('admin.penugasan.*') ? 'selected' : '' }}">◎ &nbsp; Penugasan</a>
        </nav>
        <div class="tt-bottom"><b>Admin</b>
            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">Keluar</button></form>
        </div>
    </aside>
    <main class="tt-main">
        @if(session('success'))<div class="tt-notice" role="status">✓ {{ session('success') }}</div>@endif
        @if($errors->any())<div class="tt-error" role="alert"><strong>Data belum dapat disimpan.</strong>
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>@endif
        @yield('admin-content')
    </main>
</div>
@endsection