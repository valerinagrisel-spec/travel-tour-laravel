<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Driver extends Model {
    use SoftDeletes;
    protected $table = 'driver';
    protected $primaryKey = 'id_driver';
    protected $keyType = 'string';
    public $incrementing = false;
    protected static function booted(): void {
        static::creating(function ($item) {
            if (!$item->getKey()) {
                $item->id_driver = 'DRV-' . (string) \Illuminate\Support\Str::uuid();
            }
        });
    }
    protected $fillable = ['nama_lengkap','nomor_telepon','nomor_sim','alamat','status','foto'];
}
