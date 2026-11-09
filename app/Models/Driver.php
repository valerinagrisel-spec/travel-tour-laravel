<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Driver extends Model {
    use SoftDeletes;
    protected $table = 'driver';
    protected $fillable = ['nama','nomor_telepon','nomor_sim','alamat','status','foto'];
}
