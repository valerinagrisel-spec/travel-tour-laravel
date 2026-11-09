<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Pemesanan extends Model {
 protected $table='pemesanan';
 protected $guarded=['id'];
 protected $casts=['tanggal_berangkat'=>'date','batas_waktu_pembayaran'=>'datetime','total_pembayaran'=>'decimal:2'];
 public function paket(){return $this->belongsTo(PaketTravel::class,'paket_travel_id');}
 public function pemesan(){return $this->belongsTo(User::class,'user_id');}
 public function penumpang(){return $this->hasMany(Penumpang::class);}
 public function pembayaran(){return $this->hasOne(Pembayaran::class);}
}
