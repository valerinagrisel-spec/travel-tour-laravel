<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Pembayaran extends Model {
 protected $table='pembayaran';
 protected $guarded=['id'];
 protected $casts=['tanggal_bayar'=>'datetime','nominal'=>'decimal:2'];
 public function pemesanan(){return $this->belongsTo(Pemesanan::class);}
}
