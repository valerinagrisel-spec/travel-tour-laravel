<?php

namespace App\Entities;

use App\Models\Kendaraan;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

/** Adaptasi nama operasi entity CD-05; fisik DB tetap menggunakan tabel kendaraan. */
class Kendaraan_Entity
{
    public function getAllDataMaster(): Collection
    {
        return Kendaraan::query()->latest()->get();
    }

    public function getAllDataKendaraan(?Request $request = null): LengthAwarePaginator
    {
        $query = Kendaraan::query();
        if ($request?->filled('cari')) {
            $keyword = $request->input('cari');
            $query->where(fn ($q) => $q->where('plat_nomor', 'like', "%{$keyword}%")
                ->orWhere('merek', 'like', "%{$keyword}%")
                ->orWhere('jenis_kendaraan', 'like', "%{$keyword}%"));
        }
        if ($request?->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        return $query->latest()->paginate(8)->withQueryString();
    }

    public function getDataKendaraan(string $idKendaraan): Kendaraan
    {
        return Kendaraan::query()->findOrFail($idKendaraan);
    }

    public function simpan(array $dataKendaraan, ?string $idKendaraan = null): bool
    {
        if ($idKendaraan === null) {
            return Kendaraan::query()->create($dataKendaraan)->exists;
        }
        return $this->getDataKendaraan($idKendaraan)->update($dataKendaraan);
    }

    public function hapusDataKendaraan(string $idKendaraan): bool
    {
        return (bool) $this->getDataKendaraan($idKendaraan)->delete();
    }
}
