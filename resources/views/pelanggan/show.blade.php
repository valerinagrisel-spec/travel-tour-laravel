@extends('layouts.app')
@section('title', 'Detail Pelanggan | Travel Tour')
@section('content')
<main style="max-width:900px;margin:36px auto;padding:24px;font-family:Unbounded,sans-serif;color:#252944">
    <a href="{{ route('pelanggan.index') }}">← Kembali ke Data Pelanggan</a>
    <h1>Detail Pelanggan</h1>
    <div style="background:white;border-radius:14px;padding:24px;line-height:2.1">
        <p><strong>Nama:</strong> {{ $pelanggan->name }}</p>
        <p><strong>Email:</strong> {{ $pelanggan->email }}</p>
        <p><strong>Nomor HP:</strong> {{ $pelanggan->nomor_telepon ?: 'Belum diisi' }}</p>
        <p><strong>Nomor Identitas:</strong> {{ $pelanggan->nomor_identitas ?: 'Belum diisi' }}</p>
        <p><strong>Dokumen Identitas:</strong>
            @if($pelanggan->dokumen_identitas)
                Tersimpan (akses file memerlukan pemeriksaan otorisasi dan penyimpanan privat)
            @else
                Belum diunggah
            @endif
        </p>
        <p><strong>Kelengkapan:</strong> {{ $pelanggan->nomor_identitas && $pelanggan->dokumen_identitas ? 'Lengkap' : 'Belum lengkap' }}</p>
    </div>
    <p style="font-size:12px">UC-12 hanya meninjau data. Halaman ini tidak mengubah status atau data pelanggan. Dokumen identitas sengaja tidak ditautkan secara publik.</p>
</main>
@endsection
