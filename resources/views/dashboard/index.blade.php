@extends('layouts.app')
@section('title','Dashboard - Travel Tour')
@section('content')
<div class="dashboard-page">
    <aside class="sidebar">
        <div class="sidebar-brand">TRAVEL TOUR ✦</div>
        <nav class="sidebar-menu"><a class="active" href="{{ route('dashboard') }}">Dashboard</a></nav>
    </aside>
    <main class="dashboard-main">
        <header class="dashboard-top">
            <div>
                <h1>Dashboard {{ $roleName }}</h1>
                <p>Halo, {{ auth()->user()->name }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">@csrf<button class="logout-btn" type="submit">Keluar</button></form>
        </header>
        <div class="dashboard-banner"><span class="role-tag">{{ strtoupper($roleName) }}</span>
            <h2>Selamat datang di Travel Tour</h2>
            <p>Fitur untuk {{ $roleName }} akan dilanjutkan pada modul berikutnya. Dashboard Admin dan Data Master sudah tersedia untuk akun Admin.</p>
        </div>
    </main>
</div>
@endsection