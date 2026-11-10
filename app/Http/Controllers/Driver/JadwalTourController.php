<?php
namespace App\Http\Controllers\Driver;
use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Penugasan;
class JadwalTourController extends Controller {
 private function akunDriver():Driver{
  abort_unless(auth()->check() && auth()->user()->role==='driver',403);
  $driver=Driver::where('user_id',auth()->id())->first();
  abort_unless($driver,403,'Akun Driver belum dihubungkan ke Data Master Driver oleh Admin.');
  return $driver;
 }
 public function index(){
  $driver=$this->akunDriver();
  $jadwals=Penugasan::with(['pemesanan.paket','kendaraan'])->where('driver_id',$driver->getKey())->orderBy('tanggal_mulai')->paginate(12);
  return view('driver.jadwal.index',compact('driver','jadwals'));
 }
 public function show(Penugasan $penugasan){
  $driver=$this->akunDriver();
  abort_unless($penugasan->driver_id===$driver->getKey(),403);
  $penugasan->load(['pemesanan.paket','kendaraan']);
  return view('driver.jadwal.show',compact('penugasan','driver'));
 }
}
