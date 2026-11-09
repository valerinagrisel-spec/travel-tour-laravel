@extends('travel.layout')
@section('title','Pemesanan - Travel Tour')
@section('travel-content')
<a href="{{ route('travel.detail',$paket) }}">← Kembali ke paket</a><div class="travel-panel travel-width">
<div class="travel-kicker">PEMESANAN</div><h1>{{ $paket->nama_paket }}</h1>
<p>Berangkat {{ $paket->tanggal_berangkat->format('d M Y') }} · Sisa kuota saat ini {{ $tersedia }} orang</p>
@if($tersedia<1)<div class="travel-alert error">Kuota habis. Pilih paket lainnya.</div>@else
<form method="POST" action="{{ route('customer.pemesanan.store',$paket) }}" class="travel-form" id="formBooking">@csrf
<label>Jumlah Peserta <select id="jumlahPeserta" name="jumlah_peserta" required>
@for($i=1;$i<=min(30,$tersedia);$i++)<option value="{{ $i }}" @selected(old('jumlah_peserta',1)==$i)>{{ $i }} orang</option>@endfor</select></label>
<div class="travel-cols"><label>Email pemesan<input type="email" name="email_pemesan" value="{{ old('email_pemesan',auth()->user()->email) }}" required></label><label>No. HP pemesan<input name="telepon_pemesan" value="{{ old('telepon_pemesan',auth()->user()->nomor_telepon) }}" required></label></div>
<h3>Data Penumpang</h3><div id="dataPenumpang"></div>
<div class="travel-cost"><span>Harga per orang</span><strong>Rp {{ number_format($paket->harga,0,',','.') }}</strong><span>Biaya admin</span><strong>Rp 0</strong><span>Total</span><strong id="totalBiaya"></strong></div>
<button type="submit" class="travel-button">Check Out ↗</button></form>
@endif</div>
<script>
(()=>{const count=document.getElementById('jumlahPeserta');if(!count)return;
const data=document.getElementById('dataPenumpang');const total=document.getElementById('totalBiaya');
const harga=@json((float)$paket->harga);const old=@json(old('penumpang', []));
const esc=s=>String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
function render(){const prev=[...data.querySelectorAll('fieldset')].map(el=>({nama_lengkap:el.querySelector('[data-nama]').value,nik:el.querySelector('[data-nik]').value,tanggal_lahir:el.querySelector('[data-lahir]').value}));
let html='';for(let i=0;i<Number(count.value);i++){let v=prev[i]||old[i]||{};html+=`<fieldset><legend>Penumpang ${i+1}</legend><label>Nama Lengkap<input data-nama name="penumpang[${i}][nama_lengkap]" value="${esc(v.nama_lengkap)}" maxlength="120" required></label><div class="travel-cols"><label>NIK / No. KTP<input data-nik name="penumpang[${i}][nik]" value="${esc(v.nik)}" inputmode="numeric" pattern="[0-9]{16}" maxlength="16" required></label><label>Tanggal Lahir<input data-lahir type="date" name="penumpang[${i}][tanggal_lahir]" value="${esc(v.tanggal_lahir)}" required></label></div></fieldset>`;}data.innerHTML=html;total.textContent=new Intl.NumberFormat('id-ID',{style:'currency',currency:'IDR',maximumFractionDigits:0}).format(harga*Number(count.value));}
count.addEventListener('change',render);render();})();
</script>
@endsection
