@extends('admin.layout')
@section('admin-content')
<h1 class="tt-title">Form {{ $judul }}</h1>
@php($edit=$item->exists)
<form class="tt-form" method="POST" enctype="multipart/form-data" action="{{ $edit ? route('admin.'.$jenis.'.update',$item->id) : route('admin.'.$jenis.'.store') }}">
 @csrf @if($edit) @method('PUT') @endif
 <div class="tt-fields">
 @if($jenis==='paket')
  <label>Nama Paket <input required name="nama_paket" value="{{ old('nama_paket',$item->nama_paket) }}" placeholder="Bali Hemat"></label>
  <div class="tt-inline"><label>Tujuan <input required name="tujuan" value="{{ old('tujuan',$item->tujuan) }}" placeholder="Bali"></label><label>Durasi (hari) <input required type="number" min="1" name="durasi_hari" value="{{ old('durasi_hari',$item->durasi_hari) }}"></label></div>
  <div class="tt-inline"><label>Harga (Rp) <input required type="number" min="0" step="0.01" name="harga" value="{{ old('harga',$item->harga) }}"></label><label>Kuota <input required type="number" min="1" name="kuota" value="{{ old('kuota',$item->kuota) }}"></label></div>
  <label>Jadwal Keberangkatan <input required type="date" name="tanggal_berangkat" value="{{ old('tanggal_berangkat',$item->tanggal_berangkat?->format('Y-m-d')) }}"></label>
  <label>Deskripsi <textarea rows="4" name="deskripsi">{{ old('deskripsi',$item->deskripsi) }}</textarea></label>
 @elseif($jenis==='driver')
  <label>Nama Lengkap <input required name="nama" value="{{ old('nama',$item->nama) }}" placeholder="Andi Pratama"></label>
  <label>Nomor Telepon <input required name="nomor_telepon" value="{{ old('nomor_telepon',$item->nomor_telepon) }}"></label>
  <label>Nomor SIM <input required name="nomor_sim" value="{{ old('nomor_sim',$item->nomor_sim) }}"></label>
  <label>Alamat <textarea required rows="3" name="alamat">{{ old('alamat',$item->alamat) }}</textarea></label>
  <label>Status <select required name="status"><option value="aktif" @selected(old('status',$item->status)==='aktif')>Aktif</option><option value="tidak_aktif" @selected(old('status',$item->status)==='tidak_aktif')>Tidak Aktif</option></select></label>
 @else
  <label>Plat Nomor <input required name="plat_nomor" value="{{ old('plat_nomor',$item->plat_nomor) }}" placeholder="N 1234 AB"></label>
  <label>Jenis Kendaraan <input required name="jenis" value="{{ old('jenis',$item->jenis) }}" placeholder="Mobil atau Bus"></label>
  <label>Merek <input required name="merek" value="{{ old('merek',$item->merek) }}" placeholder="Toyota"></label>
  <div class="tt-inline"><label>Tahun Produksi <input required type="number" min="1980" name="tahun_produksi" value="{{ old('tahun_produksi',$item->tahun_produksi) }}"></label><label>Kapasitas <input required type="number" min="1" name="kapasitas" value="{{ old('kapasitas',$item->kapasitas) }}"></label></div>
  <label>Status <select required name="status">@foreach(['tersedia'=>'Tersedia','digunakan'=>'Digunakan','perawatan'=>'Perawatan','tidak_aktif'=>'Tidak Aktif'] as $val=>$label)<option value="{{ $val }}" @selected(old('status',$item->status??'tersedia')===$val)>{{ $label }}</option>@endforeach</select></label>
 @endif
 <div class="tt-form-buttons"><a class="tt-cancel" href="{{ route('admin.'.$jenis.'.index') }}">Batal</a><button class="tt-btn" type="submit">Simpan</button></div>
 </div>
 <div class="tt-upload"><strong>{{ $jenis==='paket'?'Foto Paket':($jenis==='driver'?'Foto Profil':'Foto Mobil') }}</strong>
 @if($item->foto)<img id="foto-preview" src="{{ asset('storage/'.$item->foto) }}" alt="Pratinjau foto">@else<img id="foto-preview" alt="Pratinjau foto" hidden>@endif
 <input id="foto-input" type="file" accept="image/png,image/jpeg,image/webp" name="foto">
 <small>JPG, PNG, WEBP. Maksimum 2 MB.</small></div>
</form>
<script>
document.querySelector('#foto-input').addEventListener('change', e=>{
 const file=e.target.files[0],img=document.querySelector('#foto-preview');if(!file)return;
 img.src=URL.createObjectURL(file);img.hidden=false;
});
</script>
@endsection
