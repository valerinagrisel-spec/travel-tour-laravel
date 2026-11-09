<?php
namespace App\Http\Controllers\Customer;
use App\Http\Controllers\Controller;
use App\Models\PaketTravel;
use App\Models\Pemesanan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TravelController extends Controller {
 private function customer():void {abort_unless(auth()->check() && auth()->user()->role === 'customer',403);}
 private function owned(int $id):Pemesanan {
  return Pemesanan::with(['paket','penumpang','pembayaran'])->where('user_id',auth()->id())->findOrFail($id);
 }
 private function expire(Pemesanan $order):void {
  if($order->status==='belum_terbayar' && $order->batas_waktu_pembayaran->isPast()) {
   $order->update(['status'=>'kedaluwarsa']);
  }
 }
 public function katalog(Request $request){
  $pakets=PaketTravel::query()->whereDate('tanggal_berangkat','>=',today())->when($request->query('cari'),fn($q,$v)=>$q->where('nama_paket','like','%'.$v.'%'))->orderBy('tanggal_berangkat')->paginate(9)->withQueryString();
  return view('travel.katalog',compact('pakets'));
 }
 public function detail(PaketTravel $paket){return view('travel.detail',compact('paket'));}
 public function form(PaketTravel $paket){
  $this->customer();
  abort_if($paket->tanggal_berangkat->isPast(),404);
  $terpakai=Pemesanan::where('paket_travel_id',$paket->id)
   ->whereNotIn('status',['ditolak','kedaluwarsa'])
   ->where(fn($q)=>$q->where('status','!=','belum_terbayar')->orWhere('batas_waktu_pembayaran','>',now()))
   ->sum('jumlah_peserta');
  $tersedia=max(0,$paket->kuota-$terpakai);
  return view('travel.form',compact('paket','tersedia'));
 }
 public function checkout(Request $request,PaketTravel $paket){
  $this->customer();
  $data=$request->validate([
   'jumlah_peserta'=>['required','integer','min:1','max:30'],
   'email_pemesan'=>['required','email','max:255'],
   'telepon_pemesan'=>['required','string','max:20'],
   'penumpang'=>['required','array'],
   'penumpang.*.nama_lengkap'=>['required','string','max:120'],
   'penumpang.*.nik'=>['required','digits:16'],
   'penumpang.*.tanggal_lahir'=>['required','date','before:today'],
  ]);
  if(count($data['penumpang'])!==(int)$data['jumlah_peserta']){
   throw ValidationException::withMessages(['jumlah_peserta'=>'Jumlah data penumpang harus sama dengan jumlah peserta.']);
  }
  $order=DB::transaction(function()use($paket,$data){
   $locked=PaketTravel::whereKey($paket->id)->lockForUpdate()->firstOrFail();
   if($locked->tanggal_berangkat->isPast()){
    throw ValidationException::withMessages(['jumlah_peserta'=>'Tanggal keberangkatan telah lewat.']);
   }
   // Reservasi kedaluwarsa tidak lagi memakan kuota; lakukan ini saat pemesanan baru.
   Pemesanan::where('paket_travel_id',$locked->id)->where('status','belum_terbayar')
    ->where('batas_waktu_pembayaran','<=',now())->update(['status'=>'kedaluwarsa']);
   $terpakai=Pemesanan::where('paket_travel_id',$locked->id)->whereNotIn('status',['ditolak','kedaluwarsa'])->sum('jumlah_peserta');
   if($terpakai+$data['jumlah_peserta']>$locked->kuota){
    throw ValidationException::withMessages(['jumlah_peserta'=>'Sisa kuota tidak mencukupi.']);
   }
   $harga=$locked->harga;
   // Laporan menyebut biaya admin, tetapi belum menentukan nominalnya: default 0.
   $admin=0;
   $order=Pemesanan::create([
    'kode_pemesanan'=>'TRV-'.strtoupper(Str::random(10)),
    'user_id'=>auth()->id(),'paket_travel_id'=>$locked->id,
    'tanggal_berangkat'=>$locked->tanggal_berangkat,
    'jumlah_peserta'=>$data['jumlah_peserta'],
    'harga_satuan'=>$harga,'biaya_admin'=>$admin,
    'total_pembayaran'=>($harga*$data['jumlah_peserta'])+$admin,
    'email_pemesan'=>$data['email_pemesan'],
    'telepon_pemesan'=>$data['telepon_pemesan'],
    'status'=>'belum_terbayar','batas_waktu_pembayaran'=>now()->addHours(24),
   ]);
   foreach($data['penumpang'] as $penumpang){$order->penumpang()->create($penumpang);}
   Pembayaran::create(['pemesanan_id'=>$order->id,'nomor_invoice'=>'INV-'.strtoupper(Str::random(12)),'nominal'=>$order->total_pembayaran,'status'=>'belum_dibayar']);
   return $order;
  },3);
  return redirect()->route('customer.pesanan.show',$order);
 }
 public function pesanan(){
  $this->customer();
  $pesanans=Pemesanan::with('paket','pembayaran')->where('user_id',auth()->id())->latest()->paginate(10);
  foreach($pesanans as $item){$this->expire($item);}
  return view('travel.pesanan',compact('pesanans'));
 }
 public function show(Pemesanan $pemesanan){
  $this->customer();
  $pemesanan=$this->owned($pemesanan->id);
  $this->expire($pemesanan);
  return view('travel.show',compact('pemesanan'));
 }
 public function upload(Request $request,Pemesanan $pemesanan){
  $this->customer();
  $validated=$request->validate(['bukti'=>['required','file','mimes:jpg,jpeg,png,pdf','max:4096']]);
  // Simpan file dulu secara private; status transaksi hanya diperbarui jika file ada.
  $path=$validated['bukti']->store('bukti-pembayaran','local');
  try {
   $order=DB::transaction(function()use($pemesanan,$path){
    $order=Pemesanan::where('user_id',auth()->id())->lockForUpdate()->findOrFail($pemesanan->id);
    if($order->status!=='belum_terbayar' || $order->batas_waktu_pembayaran->isPast()){
     if($order->status==='belum_terbayar' && $order->batas_waktu_pembayaran->isPast())$order->update(['status'=>'kedaluwarsa']);
     throw ValidationException::withMessages(['bukti'=>'Pesanan tidak dapat dibayar lagi.']);
    }
    $order->pembayaran()->update(['bukti_pembayaran'=>$path,'tanggal_bayar'=>now(),'status'=>'menunggu_verifikasi','alasan_penolakan'=>null]);
    $order->update(['status'=>'menunggu_verifikasi_pembayaran']);
    return $order;
   });
  } catch(\Throwable $e){Storage::disk('local')->delete($path);throw $e;}
  return redirect()->route('customer.pesanan.show',$order)->with('success','Bukti pembayaran dikirim. Menunggu verifikasi Staff.');
 }
}
