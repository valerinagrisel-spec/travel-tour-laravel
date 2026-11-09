@extends('layouts.app')
@section('content')
<div class="tt-shell"><aside class="tt-sidebar"><div class="tt-logo">Travel<br>Tour<span>✦</span></div><nav class="tt-nav"><a href="{{ route('driver.jadwal.index') }}" class="selected">▦ &nbsp; Jadwal Tour</a></nav><div class="tt-bottom"><b>Driver</b><form method="POST" action="{{ route('logout') }}">@csrf<button>Keluar</button></form></div></aside><main class="tt-main">@yield('driver-content')</main></div>
@endsection
