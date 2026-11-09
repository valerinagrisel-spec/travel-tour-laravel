@extends('admin.layout')
@section('admin-content')
<h1 class="tt-title">Data Master</h1>
<div class="tt-toolbar"><div class="tt-tabs">
 <a class="{{ $jenis==='paket'?'active':'' }}" href="{{ route('admin.paket.index') }}">Paket Travel</a>
 <a class="{{ $jenis==='driver'?'active':'' }}" href="{{ route('admin.driver.index') }}">Driver</a>
 <a class="{{ $jenis==='kendaraan'?'active':'' }}" href="{{ route('admin.kendaraan.index') }}">Kendaraan</a>
 </div><a class="tt-btn" href="{{ route('admin.'.$jenis.'.create') }}">+ Tambah {{ $judul }}</a></div>
<form method="GET" class="tt-filter"><input name="cari" value="{{ request('cari') }}" placeholder="Cari {{ strtolower($judul) }}..." aria-label="Cari {{ $judul }}">
@if($jenis==='paket')<select name="tujuan" aria-label="Filter tujuan"><option value="">Semua tujuan</option>@foreach($tujuanList as $tujuan)<option value="{{ $tujuan }}" @selected(request('tujuan')===$tujuan)>{{ $tujuan }}</option>@endforeach</select>
@else<select name="status" aria-label="Filter status"><option value="">Semua status</option>@foreach(($jenis==='driver'?['aktif'=>'Aktif','tidak_aktif'=>'Tidak Aktif']:['tersedia'=>'Tersedia','digunakan'=>'Digunakan','perawatan'=>'Perawatan','tidak_aktif'=>'Tidak Aktif']) as $val=>$label)<option value="{{ $val }}" @selected(request('status')===$val)>{{ $label }}</option>@endforeach</select>@endif
<button type="submit" class="tt-btn">Cari</button><a class="tt-reset" href="{{ route('admin.'.$jenis.'.index') }}">Reset</a></form>
<div class="tt-table-wrap"><table class="tt-table"><thead><tr>
 @if($jenis==='paket')<th>Foto</th><th>Nama Paket</th><th>Tujuan</th><th>Harga</th><th>Kuota</th>
 @elseif($jenis==='driver')<th>Foto</th><th>Nama</th><th>No. Telepon</th><th>No. SIM</th><th>Asal</th><th>Status</th>
 @else<th>Foto</th><th>No. Plat</th><th>Jenis</th><th>Merek</th><th>Kapasitas</th><th>Tahun</th><th>Status</th>@endif
 <th>Aksi</th></tr></thead><tbody>
 @forelse($data as $item)<tr>
 <td>@if($item->foto)<img class="tt-thumb" src="{{ asset('storage/'.$item->foto) }}" alt="Foto">@else<div class="tt-thumb tt-placeholder">📷</div>@endif</td>
 @if($jenis==='paket')<td>{{ $item->nama_paket }}</td><td>{{ $item->tujuan }}</td><td>Rp {{ number_format($item->harga,0,',','.') }}</td><td>{{ $item->kuota }}</td>
 @elseif($jenis==='driver')<td>{{ $item->nama }}</td><td>{{ $item->nomor_telepon }}</td><td>{{ $item->nomor_sim }}</td><td>{{ $item->alamat }}</td><td><span class="tt-status s-{{ $item->status }}">{{ str_replace('_',' ',ucwords($item->status,'_')) }}</span></td>
 @else<td>{{ $item->plat_nomor }}</td><td>{{ $item->jenis }}</td><td>{{ $item->merek }}</td><td>{{ $item->kapasitas }}</td><td>{{ $item->tahun_produksi }}</td><td><span class="tt-status s-{{ $item->status }}">{{ str_replace('_',' ',ucwords($item->status,'_')) }}</span></td>@endif
 <td><div class="tt-actions"><a href="{{ route('admin.'.$jenis.'.edit',$item->id) }}" class="tt-edit">Edit</a><form method="POST" action="{{ route('admin.'.$jenis.'.destroy',$item->id) }}" onsubmit="return confirm('Yakin menghapus data ini?')">@csrf @method('DELETE')<button type="submit" class="tt-delete">Hapus</button></form></div></td>
 </tr>@empty<tr><td colspan="9" class="tt-empty">Belum ada data. Klik tombol Tambah untuk mengisi.</td></tr>@endforelse
 </tbody></table></div><div class="tt-pagination">{{ $data->links() }}</div>
@endsection
