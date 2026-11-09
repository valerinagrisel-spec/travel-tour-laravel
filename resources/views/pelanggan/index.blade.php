@extends('layouts.app')
@section('title', 'Data Pelanggan | Travel Tour')
@section('content')
<main style="max-width:1100px;margin:36px auto;padding:24px;font-family:Unbounded,sans-serif;color:#252944">
    <a href="{{ route('dashboard') }}">← Dashboard</a>
    <h1>Data Pelanggan</h1>
    <p>UC-12 · Pemeriksaan data pelanggan (akses hanya-baca).</p>
    <form method="GET" action="{{ route('pelanggan.cari') }}" style="display:flex;gap:12px;margin:24px 0;flex-wrap:wrap">
        <input name="keyword" value="{{ $keyword }}" placeholder="Cari nama atau nomor HP" aria-label="Cari pelanggan" style="padding:12px;min-width:280px;border:1px solid #aaa;border-radius:8px">
        <button type="submit" style="padding:12px 20px;background:#252944;color:white;border:0;border-radius:8px">Cari</button>
        <a href="{{ route('pelanggan.index') }}" style="padding:12px">Reset</a>
    </form>
    @if($keyword !== '' && $pelanggan->isEmpty())
        <p role="status">Data Pelanggan Tidak Ditemukan.</p>
    @elseif($pelanggan->isEmpty())
        <p>Belum ada pelanggan terdaftar.</p>
    @else
        <div style="overflow-x:auto"><table style="width:100%;border-collapse:collapse;background:white">
            <thead><tr style="background:#e3ff3b"><th style="padding:14px;text-align:left">Nama</th><th style="padding:14px;text-align:left">Email</th><th style="padding:14px;text-align:left">No. HP</th><th style="padding:14px;text-align:left">Aksi</th></tr></thead>
            <tbody>
            @foreach($pelanggan as $orang)
                <tr style="border-bottom:1px solid #ddd"><td style="padding:14px">{{ $orang->name }}</td><td style="padding:14px">{{ $orang->email }}</td><td style="padding:14px">{{ $orang->nomor_telepon }}</td><td style="padding:14px"><a href="{{ route('pelanggan.detail', $orang->id) }}">Detail</a></td></tr>
            @endforeach
            </tbody>
        </table></div>

    @endif
</main>
@endsection
