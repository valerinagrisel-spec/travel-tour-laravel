<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Penumpang extends Model {
 protected $table='penumpang';
 protected $fillable=['nama_lengkap','nik','tanggal_lahir'];
 protected $casts=['nik'=>'encrypted','tanggal_lahir'=>'date'];
 public function pemesanan(){return $this->belongsTo(Pemesanan::class);}
}
