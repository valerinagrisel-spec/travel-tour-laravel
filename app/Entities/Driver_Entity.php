<?php

namespace App\Entities;

use App\Models\Driver;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

/** Adaptasi nama operasi entity CD-06; fisik DB tetap menggunakan tabel driver. */
class Driver_Entity
{
    public function getAllDataMaster(): Collection
    {
        return Driver::query()->latest()->get();
    }

    public function getAllDataDriver(?Request $request = null): LengthAwarePaginator
    {
        $query = Driver::query();
        if ($request?->filled('cari')) {
            $keyword = $request->input('cari');
            $query->where(fn($q) => $q->where('nama_lengkap', 'like', "%{$keyword}%")
                ->orWhere('nomor_telepon', 'like', "%{$keyword}%"));
        }
        if ($request?->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        return $query->latest()->paginate(8)->withQueryString();
    }

    public function getDataDriver(string $idDriver): Driver
    {
        return Driver::query()->findOrFail($idDriver);
    }

    public function simpan(array $dataDriver, ?string $idDriver = null): bool
    {
        if ($idDriver === null) {
            return Driver::query()->create($dataDriver)->exists;
        }
        return $this->getDataDriver($idDriver)->update($dataDriver);
    }

    public function hapusDataDriver(string $idDriver): bool
    {
        return (bool) $this->getDataDriver($idDriver)->delete();
    }
}
