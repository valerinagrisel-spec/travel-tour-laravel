<?php

namespace App\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

/** CD-22 (Pelanggan Entity). Adaptasi tabel UMUM -> users tanpa memodifikasi primary key. */
class Pelanggan_Entity
{
    /** PC-35 */
    public function getAll_data_pelanggan(): Collection
    {
        return User::query()->where('role', 'customer')->orderBy('name')->get();
    }

    /** PC-36 */
    public function get_cari_pelanggan(string $keyword): Collection
    {
        return User::query()->where('role', 'customer')
            ->where(fn ($q) => $q->where('name', 'like', '%'.$keyword.'%')
                ->orWhere('nomor_telepon', 'like', '%'.$keyword.'%'))
            ->orderBy('name')->get();
    }

    /** PC-37 */
    public function get_data_detail_pelanggan(string $idPelanggan): ?User
    {
        return User::query()->where('role', 'customer')->find($idPelanggan);
    }
}
