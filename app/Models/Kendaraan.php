<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Kendaraan extends Model {
    use SoftDeletes;
    protected $table = 'kendaraan';
    protected $primaryKey = 'id_kendaraan';
    protected $keyType = 'string';
    public $incrementing = false;
    protected static function booted(): void {
        static::creating(function ($item) {
            if (!$item->getKey()) {
                $item->id_kendaraan = 'KDR-' . (string) \Illuminate\Support\Str::uuid();
            }
        });
    }
    protected $fillable = ['plat_nomor','jenis_kendaraan','merek','kapasitas','tahun_produksi','status','foto'];
}
