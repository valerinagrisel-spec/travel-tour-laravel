@extends('layouts.app')

@section('title', 'Masuk - Travel Tour')

@section('content')
<div class="auth-page">
    <div class="auth-container">

        <div class="auth-brand">
            <div class="brand-logo">T<span>✦</span></div>
            <h1>TRAVEL TOUR</h1>
            <p>Jelajahi destinasi impianmu bersama kami.</p>

            <div class="brand-decoration">
                <span>EXPLORE</span>
                <span>DISCOVER</span>
                <span>TRAVEL</span>
            </div>
        </div>

        <div class="auth-card">
            <div class="auth-heading">
                <span class="eyebrow">WELCOME BACK</span>
                <h2>Masuk Akun</h2>
                <p>Masuk untuk melanjutkan perjalananmu.</p>
            </div>

            @if ($errors->any())
            <div class="alert-error">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Masukkan email kamu"
                        value="{{ old('email') }}"
                        required
                        autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        required>
                </div>

                <div class="form-options">
                    <label class="remember">
                        <input type="checkbox" name="remember" value="1">
                        Ingat saya
                    </label>
                </div>

                <button type="submit" class="btn-primary">
                    MASUK SEKARANG <span>↗</span>
                </button>
            </form>

            <p class="auth-footer">
                Belum punya akun?
                <a href="{{ route('register') }}">Daftar sekarang</a>
            </p>
        </div>
    </div>
</div>
@endsection