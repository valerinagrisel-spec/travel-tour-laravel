@extends('travel.layout')
@section('title','Detail Pesanan - Travel Tour')
@section('travel-content')
<a href="{{ route('customer.pesanan.index') }}">← Pesanan Saya</a>
<div class="travel-panel travel-width"><div class="travel-kicker">RINGKASAN PEMESANAN</div><h1>{{ $pemesanan->kode_pemesanan }}</h1>
<p class="travel-status">{{ str_replace('_',' ',ucwords($pemesanan->status,'_')) }}</p>
<dl><dt>Paket</dt><dd>{{ $pemesanan->paket?->nama_paket }}</dd><dt>Keberangkatan</dt><dd>{{ $pemesanan->tanggal_berangkat->format('d M Y') }}</dd><dt>Jumlah peserta</dt><dd>{{ $pemesanan->jumlah_peserta }}</dd><dt>Invoice</dt><dd>{{ $pemesanan->pembayaran?->nomor_invoice }}</dd><dt>Total bayar</dt><dd>Rp {{ number_format($pemesanan->total_pembayaran,0,',','.') }}</dd><dt>Batas pembayaran</dt><dd>{{ $pemesanan->batas_waktu_pembayaran->format('d M Y H:i') }} (zona waktu aplikasi)</dd></dl>
<h3>Penumpang</h3><ul>@foreach($pemesanan->penumpang as $orang)<li>{{ $orang->nama_lengkap }}</li>@endforeach</ul>
@if($pemesanan->status==='belum_terbayar')
<div class="travel-alert">Pembayaran dilakukan sesuai instruksi resmi penyelenggara. QR pembayaran belum dikonfigurasi, sehingga jangan melakukan transfer ke nomor rekening yang tidak diverifikasi.</div>
<form method="POST" action="{{ route('customer.pesanan.upload',$pemesanan) }}" enctype="multipart/form-data" class="travel-form">@csrf
<label>Unggah Bukti Pembayaran (JPG, PNG, PDF; maksimal 4 MB)<input type="file" name="bukti" accept="image/png,image/jpeg,application/pdf" required></label>
<button class="travel-button">Kirim Pembayaran ↗</button></form>
@elseif($pemesanan->status==='menunggu_verifikasi_pembayaran')<p>Bukti pembayaran sudah diterima sistem dan sedang diperiksa oleh Staff.</p>
@elseif($pemesanan->status==='pembayaran_terverifikasi')<p>Pembayaran sudah diverifikasi. Menunggu verifikasi pemesanan oleh Staff.</p>
@elseif($pemesanan->status==='diverifikasi')<p>Pemesanan telah diverifikasi.</p>@if($pemesanan->link_grup_wa)<a class="travel-button" href="{{ $pemesanan->link_grup_wa }}" rel="noopener noreferrer" target="_blank">Gabung Grup WhatsApp ↗</a>@endif
@elseif($pemesanan->status==='ditolak')<div class="travel-alert error">Pesanan ditolak: {{ $pemesanan->alasan_penolakan }}</div>
@else<div class="travel-alert error">Batas waktu pembayaran telah berakhir. Kuota pesanan dilepas.</div>@endif
</div>
@endsection
