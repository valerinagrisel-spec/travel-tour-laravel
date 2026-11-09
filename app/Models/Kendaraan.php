<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Kendaraan extends Model {
    use SoftDeletes;
    protected $table = 'kendaraan';
    protected $fillable = ['plat_nomor','jenis','merek','kapasitas','tahun_produksi','status','foto'];
}
