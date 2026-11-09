@extends('layouts.app')

@section('title', 'Daftar - Travel Tour')

@section('content')
<div class="auth-page">
    <div class="auth-container">

        <div class="auth-brand">
            <div class="brand-logo">T<span>✦</span></div>
            <h1>TRAVEL TOUR</h1>
            <p>Mulai petualangan barumu bersama Travel Tour.</p>

            <div class="brand-decoration">
                <span>EXPLORE</span>
                <span>DISCOVER</span>
                <span>TRAVEL</span>
            </div>
        </div>

        <div class="auth-card">
            <div class="auth-heading">
                <span class="eyebrow">JOIN US</span>
                <h2>Buat Akun</h2>
                <p>Daftarkan dirimu untuk mulai menjelajah.</p>
            </div>

            @if ($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('register.submit') }}">
                @csrf

                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name"
                        value="{{ old('name') }}"
                        placeholder="Nama lengkap kamu" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email"
                        value="{{ old('email') }}"
                        placeholder="Alamat email" required>
                </div>

                <div class="form-group">
                    <label for="nomor_telepon">Nomor Telepon</label>
                    <input type="tel" id="nomor_telepon"
                        name="nomor_telepon"
                        value="{{ old('nomor_telepon') }}"
                        placeholder="08xxxxxxxxxx" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password"
                        name="password"
                        placeholder="Minimal 8 karakter" required>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">
                        Konfirmasi Password
                    </label>
                    <input type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Ulangi password" required>
                </div>

                <button type="submit" class="btn-primary">
                    DAFTAR SEKARANG <span>↗</span>
                </button>
            </form>

            <p class="auth-footer">
                Sudah punya akun?
                <a href="{{ route('login') }}">Masuk sekarang</a>
            </p>
        </div>
    </div>
</div>
@endsection