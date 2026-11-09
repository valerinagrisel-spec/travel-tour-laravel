<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanTerkirim extends Model
{
    protected $table = 'laporan_terkirim';
    protected $fillable = ['staff_user_id', 'periode_awal', 'periode_akhir', 'jumlah_transaksi', 'total_nominal'];
    protected $casts = ['periode_awal' => 'date', 'periode_akhir' => 'date', 'total_nominal' => 'decimal:2'];

    public function pengirim()
    {
        return $this->belongsTo(User::class, 'staff_user_id');
    }
}
