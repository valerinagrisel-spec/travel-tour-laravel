<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class PaketTravel extends Model {
    use SoftDeletes;
    protected $table = 'paket_travel';
    protected $fillable = ['nama_paket','tujuan','durasi_hari','harga','kuota','tanggal_berangkat','deskripsi','foto'];
    protected $casts = ['tanggal_berangkat'=>'date','harga'=>'decimal:2'];
}
