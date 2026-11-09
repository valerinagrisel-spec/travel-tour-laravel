@extends('layouts.app')
@section('content')
<div class="tt-shell">
    <aside class="tt-sidebar">
        <div class="tt-logo">Travel<br>Tour<span>✦</span></div>
        <nav class="tt-nav">
            @if(auth()->user()->role === 'staff')
                <a href="{{ route('staff.verifikasi') }}">▤ &nbsp; Verifikasi</a>
                <a class="selected" href="{{ route('staff.laporan.index') }}">▦ &nbsp; Laporan Transaksi</a>
            @else
                <a class="selected" href="{{ route('manager.laporan.index') }}">▦ &nbsp; Laporan Transaksi</a>
            @endif
        </nav>
        <div class="tt-bottom">
            <b>{{ ucfirst(auth()->user()->role) }}</b>
            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">Keluar</button></form>
        </div>
    </aside>
    <main class="tt-main">
        @if(session('success'))<div class="tt-notice">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="tt-error"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        @yield('laporan-content')
    </main>
</div>
@endsection
