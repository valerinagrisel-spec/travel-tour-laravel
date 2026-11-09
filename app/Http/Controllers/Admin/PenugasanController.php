<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Kendaraan;
use App\Models\Pemesanan;
use App\Models\Penugasan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class PenugasanController extends Controller {
 public function index(){
  return view('admin.penugasan.index',[
   'judul'=>'Penugasan',
   'penugasans'=>Penugasan::with(['pemesanan.paket','pemesanan.pemesan','driver','kendaraan'])->orderByDesc('tanggal_mulai')->paginate(12),
   'pesanans'=>Pemesanan::with('paket','pemesan')->where('status','diverifikasi')->whereNotIn('id',Penugasan::query()->select('pemesanan_id'))->orderBy('tanggal_berangkat')->get(),
   'drivers'=>Driver::where('status','aktif')->orderBy('nama')->get(),
   'kendaraans'=>Kendaraan::where('status','tersedia')->orderBy('plat_nomor')->get(),
   'driverUsers'=>User::where('role','driver')->orderBy('name')->get(),
  ]);
 }
 public function store(Request $request){
  $data=$request->validate([
   'pemesanan_id'=>['required','integer','exists:pemesanan,id'],
   'driver_id'=>['required','integer','exists:driver,id'],
   'kendaraan_id'=>['required','integer','exists:kendaraan,id'],
   'titik_kumpul'=>['required','string','max:160'],
   'jam_kumpul'=>['required','date_format:H:i'],
   'catatan'=>['nullable','string','max:2000'],
  ]);
  DB::transaction(function()use($data){
   // Lock rows in a fixed order so concurrent requests cannot assign the same resource.
   $driver=Driver::whereKey($data['driver_id'])->lockForUpdate()->firstOrFail();
   $kendaraan=Kendaraan::whereKey($data['kendaraan_id'])->lockForUpdate()->firstOrFail();
   $pesanan=Pemesanan::with('paket')->whereKey($data['pemesanan_id'])->lockForUpdate()->firstOrFail();
   if($pesanan->status!=='diverifikasi')throw ValidationException::withMessages(['pemesanan_id'=>'Pemesanan harus telah diverifikasi Staff.']);
   if(Penugasan::where('pemesanan_id',$pesanan->id)->exists())throw ValidationException::withMessages(['pemesanan_id'=>'Pemesanan ini sudah memiliki penugasan.']);
   if($driver->status!=='aktif')throw ValidationException::withMessages(['driver_id'=>'Driver tidak aktif.']);
   if($kendaraan->status!=='tersedia')throw ValidationException::withMessages(['kendaraan_id'=>'Kendaraan tidak tersedia.']);
   if($kendaraan->kapasitas < $pesanan->jumlah_peserta)throw ValidationException::withMessages(['kendaraan_id'=>'Kapasitas kendaraan kurang dari jumlah peserta.']);
   if(!$pesanan->paket)throw ValidationException::withMessages(['pemesanan_id'=>'Data paket sudah tidak tersedia.']);
   $start=$pesanan->tanggal_berangkat->copy()->startOfDay();
   $end=$start->copy()->addDays(max(1,(int)$pesanan->paket->durasi_hari)-1);
   if($start->isBefore(now()->startOfDay()))throw ValidationException::withMessages(['pemesanan_id'=>'Tanggal keberangkatan sudah lewat.']);
   $bentrok=Penugasan::where('status','terjadwal')->whereDate('tanggal_mulai','<=',$end->toDateString())->whereDate('tanggal_selesai','>=',$start->toDateString())
    ->where(fn($q)=>$q->where('driver_id',$driver->id)->orWhere('kendaraan_id',$kendaraan->id))->exists();
   if($bentrok)throw ValidationException::withMessages(['driver_id'=>'Driver atau kendaraan sudah ditugaskan pada rentang tanggal tersebut.']);
   Penugasan::create([
    'pemesanan_id'=>$pesanan->id,'driver_id'=>$driver->id,'kendaraan_id'=>$kendaraan->id,
    'tanggal_mulai'=>$start->toDateString(),'tanggal_selesai'=>$end->toDateString(),
    'titik_kumpul'=>$data['titik_kumpul'],'jam_kumpul'=>$data['jam_kumpul'],
    'catatan'=>$data['catatan']??null,'status'=>'terjadwal'
   ]);
  },3);
  return redirect()->route('admin.penugasan.index')->with('success','Penugasan berhasil disimpan.');
 }
 public function hubungkanDriver(Request $request){
  $data=$request->validate(['driver_id'=>['required','exists:driver,id'],'user_id'=>['required','exists:users,id']]);
  DB::transaction(function()use($data){
   $driver=Driver::whereKey($data['driver_id'])->lockForUpdate()->firstOrFail();
   $user=User::whereKey($data['user_id'])->firstOrFail();
   if($user->role!=='driver')throw ValidationException::withMessages(['user_id'=>'Akun harus mempunyai role driver.']);
   if(Driver::where('user_id',$user->id)->whereKeyNot($driver->id)->exists())throw ValidationException::withMessages(['user_id'=>'Akun sudah terhubung ke driver lain.']);
   $driver->user_id=$user->id;$driver->save();
  });
  return back()->with('success','Akun login driver berhasil dihubungkan.');
 }
}
