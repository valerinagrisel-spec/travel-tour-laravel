<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Penugasan extends Model {
 protected $table='penugasan';
 protected $fillable=['pemesanan_id','driver_id','kendaraan_id','tanggal_mulai','tanggal_selesai','titik_kumpul','jam_kumpul','catatan','status'];
 protected $casts=['tanggal_mulai'=>'date','tanggal_selesai'=>'date'];
 public function pemesanan(){return $this->belongsTo(Pemesanan::class);}
 public function driver(){return $this->belongsTo(Driver::class);}
 public function kendaraan(){return $this->belongsTo(Kendaraan::class);}
}
