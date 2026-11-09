@extends('admin.layout')
@section('admin-content')
<h1 class="tt-title">Penugasan Perjalanan</h1>
<p class="pj-help">Pilih pesanan yang sudah diverifikasi Staff, kemudian tetapkan kendaraan dan driver tanpa bentrok jadwal.</p>
<div class="pj-columns">
 <section class="pj-panel">
  <h2>Tambah Penugasan</h2>
  <form action="{{ route('admin.penugasan.store') }}" method="POST" class="pj-form">@csrf
   <label>Pemesanan terverifikasi<select name="pemesanan_id" required><option value="">Pilih pesanan</option>@foreach($pesanans as $pesanan)<option value="{{ $pesanan->id }}" @selected(old('pemesanan_id')==$pesanan->id)>{{ $pesanan->kode_pemesanan }} — {{ $pesanan->paket?->nama_paket }} — {{ $pesanan->jumlah_peserta }} peserta — {{ $pesanan->tanggal_berangkat?->format('d/m/Y') }}</option>@endforeach</select></label>
   <label>Driver aktif<select name="driver_id" required><option value="">Pilih driver</option>@foreach($drivers as $driver)<option value="{{ $driver->id }}" @selected(old('driver_id')==$driver->id)>{{ $driver->nama }}{{ $driver->user_id ? ' (akun tersambung)' : ' (akun belum tersambung)' }}</option>@endforeach</select></label>
   <label>Kendaraan tersedia<select name="kendaraan_id" required><option value="">Pilih kendaraan</option>@foreach($kendaraans as $kendaraan)<option value="{{ $kendaraan->id }}" @selected(old('kendaraan_id')==$kendaraan->id)>{{ $kendaraan->plat_nomor }} — {{ $kendaraan->merek }} ({{ $kendaraan->kapasitas }} kursi)</option>@endforeach</select></label>
   <label>Titik kumpul<input type="text" name="titik_kumpul" maxlength="160" value="{{ old('titik_kumpul') }}" placeholder="Contoh: Lobby Hotel" required></label>
   <label>Jam kumpul<input type="time" name="jam_kumpul" value="{{ old('jam_kumpul','07:00') }}" required></label>
   <label>Catatan<textarea name="catatan" rows="3" placeholder="Informasi tambahan (opsional)">{{ old('catatan') }}</textarea></label>
   <button class="tt-btn" type="submit">Simpan Penugasan</button>
  </form>
 </section>
 <section class="pj-panel">
  <h2>Hubungkan Akun Driver</h2>
  <p class="pj-help">Agar jadwal muncul saat Driver login, hubungkan data driver dengan akun yang memiliki role <code>driver</code>.</p>
  <form class="pj-form" method="POST" action="{{ route('admin.penugasan.link') }}">@csrf
   <label>Data Master Driver<select name="driver_id" required><option value="">Pilih driver</option>@foreach($drivers as $driver)<option value="{{ $driver->id }}">{{ $driver->nama }}</option>@endforeach</select></label>
   <label>Akun Login Driver<select name="user_id" required><option value="">Pilih akun</option>@foreach($driverUsers as $u)<option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>@endforeach</select></label>
   <button class="tt-btn" type="submit">Hubungkan Akun</button>
  </form>
  <p class="pj-help">Jika daftar akun kosong, buat akun lewat Register lalu ubah role menjadi <code>driver</code> lewat Tinker khusus lingkungan development.</p>
 </section>
</div>
<section class="pj-panel pj-list"><h2>Jadwal Penugasan</h2>
<div class="tt-table-wrap"><table class="tt-table"><thead><tr><th>Kode Pesanan</th><th>Paket</th><th>Periode</th><th>Driver</th><th>Kendaraan</th><th>Status</th></tr></thead><tbody>
@forelse($penugasans as $item)<tr><td>{{ $item->pemesanan?->kode_pemesanan }}</td><td>{{ $item->pemesanan?->paket?->nama_paket }}</td><td>{{ $item->tanggal_mulai->format('d/m/Y') }}–{{ $item->tanggal_selesai->format('d/m/Y') }}</td><td>{{ $item->driver?->nama }}</td><td>{{ $item->kendaraan?->plat_nomor }}</td><td><span class="tt-status s-tersedia">{{ ucfirst($item->status) }}</span></td></tr>
@empty<tr><td class="tt-empty" colspan="6">Belum ada penugasan. Verifikasi pemesanan oleh Staff dahulu.</td></tr>@endforelse
</tbody></table></div><div class="tt-pagination">{{ $penugasans->links() }}</div></section>
@endsection
