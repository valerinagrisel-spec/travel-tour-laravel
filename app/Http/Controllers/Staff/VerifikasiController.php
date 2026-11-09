<?php
namespace App\Http\Controllers\Staff;
use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
class VerifikasiController extends Controller {
 private function staff():void{abort_unless(auth()->check() && auth()->user()->role==='staff',403);}
 public function index(){
  $this->staff();
  $pesanans=Pemesanan::with('paket','pemesan','pembayaran')->latest()->paginate(15);
  return view('staff.verifikasi',compact('pesanans'));
 }
 public function bukti(Pemesanan $pemesanan){
  $this->staff();
  $payment=$pemesanan->pembayaran;
  abort_unless($payment && $payment->bukti_pembayaran && Storage::disk('local')->exists($payment->bukti_pembayaran),404);
  return response()->file(Storage::disk('local')->path($payment->bukti_pembayaran));
 }
 public function pembayaran(Request $request,Pemesanan $pemesanan){
  $this->staff();
  $data=$request->validate(['keputusan'=>['required','in:terverifikasi,ditolak'],'alasan'=>['required_if:keputusan,ditolak','nullable','string','max:1000']]);
  DB::transaction(function()use($pemesanan,$data){
   $order=Pemesanan::whereKey($pemesanan->id)->lockForUpdate()->firstOrFail();
   if($order->status!=='menunggu_verifikasi_pembayaran')throw ValidationException::withMessages(['keputusan'=>'Status pesanan sudah berubah.']);
   $pay=$order->pembayaran;
   if(!$pay || $pay->status!=='menunggu_verifikasi')throw ValidationException::withMessages(['keputusan'=>'Pembayaran belum siap diverifikasi.']);
   if($data['keputusan']==='terverifikasi'){
    $pay->update(['status'=>'terverifikasi','alasan_penolakan'=>null]);
    $order->update(['status'=>'pembayaran_terverifikasi']);
   }else{
    // Pembayaran ditolak: pemesanan tetap memegang kuota hingga Staff menolak pemesanannya.
    $pay->update(['status'=>'ditolak','alasan_penolakan'=>$data['alasan']]);
    $order->update(['status'=>'ditolak','alasan_penolakan'=>$data['alasan']]);
   }
  });
  return back()->with('success','Status pembayaran berhasil diperbarui.');
 }
 public function pesanan(Request $request,Pemesanan $pemesanan){
  $this->staff();
  $data=$request->validate(['keputusan'=>['required','in:diverifikasi,ditolak'],'link_grup_wa'=>['required_if:keputusan,diverifikasi','nullable','url:http,https','max:2048'],'alasan'=>['required_if:keputusan,ditolak','nullable','string','max:1000']]);
  DB::transaction(function()use($pemesanan,$data){
   $order=Pemesanan::whereKey($pemesanan->id)->lockForUpdate()->firstOrFail();
   if($order->status!=='pembayaran_terverifikasi')throw ValidationException::withMessages(['keputusan'=>'Verifikasi pemesanan baru dapat dilakukan setelah pembayaran terverifikasi.']);
   $order->update(['status'=>$data['keputusan'],'link_grup_wa'=>$data['keputusan']==='diverifikasi'?$data['link_grup_wa']:null,'alasan_penolakan'=>$data['keputusan']==='ditolak'?$data['alasan']:null]);
  });
  return back()->with('success','Status pemesanan berhasil diperbarui.');
 }
}
