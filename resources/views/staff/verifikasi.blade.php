@extends('travel.layout')
@section('title','Verifikasi Staff - Travel Tour')
@section('travel-content')
<div class="travel-heading"><h1>Verifikasi Staff</h1><p>Pembayaran diperiksa lebih dahulu, lalu pemesanan dan link grup WhatsApp.</p></div>
@forelse($pesanans as $p)
<section class="travel-panel travel-staff-row"><div class="travel-kicker">{{ $p->kode_pemesanan }}</div><h3>{{ $p->pemesan?->name }} — {{ $p->paket?->nama_paket }}</h3><p>Status: {{ $p->status }} · Rp {{ number_format($p->total_pembayaran,0,',','.') }}</p>
@if($p->status==='menunggu_verifikasi_pembayaran')
<a href="{{ route('staff.bukti',$p) }}" target="_blank" rel="noopener noreferrer">Lihat bukti pembayaran ↗</a>
<form class="travel-form" method="POST" action="{{ route('staff.pembayaran',$p) }}">@csrf
<label>Keputusan Pembayaran<select name="keputusan" required><option value="terverifikasi">Verifikasi Pembayaran</option><option value="ditolak">Tolak Pembayaran</option></select></label><label>Alasan (wajib jika ditolak)<textarea name="alasan"></textarea></label><button class="travel-button">Simpan Keputusan</button></form>
@elseif($p->status==='pembayaran_terverifikasi')
<form class="travel-form" method="POST" action="{{ route('staff.pesanan',$p) }}">@csrf
<label>Keputusan Pemesanan<select name="keputusan" required><option value="diverifikasi">Verifikasi Pemesanan</option><option value="ditolak">Tolak Pemesanan</option></select></label><label>Link grup WhatsApp (wajib jika diverifikasi)<input type="url" name="link_grup_wa" placeholder="https://chat.whatsapp.com/..."></label><label>Alasan (wajib jika ditolak)<textarea name="alasan"></textarea></label><button class="travel-button">Simpan Keputusan</button></form>
@endif</section>
@empty<p>Belum ada pesanan.</p>@endforelse
{{ $pesanans->links() }}
@endsection
