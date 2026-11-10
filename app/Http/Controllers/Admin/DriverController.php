<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class DriverController extends Controller
{
    // PC-20: Mengambil data master Driver
    public function getDataMaster()
    {
        return Driver::all();
    }

    // PC-19: Menampilkan daftar Driver
    public function getDaftarDriver(Request $request)
    {
        $items = Driver::query()
            ->when($request->filled('cari'), function ($query) use ($request) {
                $cari = $request->input('cari');

                $query->where(function ($q) use ($cari) {
                    $q->where('nama_lengkap', 'like', "%{$cari}%")
                        ->orWhere('nomor_telepon', 'like', "%{$cari}%")
                        ->orWhere('nomor_sim', 'like', "%{$cari}%");
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->input('status'));
            })
            ->latest()
            ->paginate(8)
            ->withQueryString();


        return view('admin.master.index', [
            'jenis' => 'driver',
            'judul' => 'Driver',
            'data' => $items,
        ]);
    }

    // PC-21: Mengambil satu data Driver
    public function getDriver(string $id)
    {
        return Driver::findOrFail($id);
    }

    // PC-22: Validasi dan tambah Driver
    public function validasiTambahDriver(Request $request)
    {
        $data = $this->validasi($request);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')
                ->store('driver', 'public');
        }

        Driver::create($data);

        return redirect()
            ->route('admin.driver.index')
            ->with('success', 'Driver berhasil ditambahkan.');
    }

    // PC-23: Validasi dan edit Driver
    public function validasiEditDriver(Request $request, Driver $driver)
    {
        $data = $this->validasi($request, $driver);

        if ($request->hasFile('foto')) {
            $fotoLama = $driver->foto;

            $data['foto'] = $request->file('foto')
                ->store('driver', 'public');
        }

        $driver->update($data);

        if (isset($fotoLama) && $fotoLama) {
            Storage::disk('public')->delete($fotoLama);
        }

        return redirect()
            ->route('admin.driver.index')
            ->with('success', 'Driver berhasil diperbarui.');
    }

    // PC-24: Menghapus Driver
    public function hapusDriver(Driver $driver)
    {
        $driver->delete();

        return redirect()
            ->route('admin.driver.index')
            ->with('success', 'Driver dihapus dari daftar.');
    }

    // Penghubung route Laravel
    public function index(Request $request)
    {
        return $this->getDaftarDriver($request);
    }

    public function create()
    {
        return view('admin.master.form', [
            'jenis' => 'driver',
            'judul' => 'Tambah Driver',
            'item' => new Driver,
        ]);
    }

    public function store(Request $request)
    {
        return $this->validasiTambahDriver($request);
    }

    public function edit(Driver $driver)
    {
        return view('admin.master.form', [
            'jenis' => 'driver',
            'judul' => 'Edit Driver',
            'item' => $this->getDriver($driver->getKey()),
        ]);
    }

    public function update(Request $request, Driver $driver)
    {
        return $this->validasiEditDriver($request, $driver);
    }

    public function destroy(Driver $driver)
    {
        return $this->hapusDriver($driver);
    }

    // Aturan validasi input
    private function validasi(Request $request, ?Driver $driver = null): array
    {
        $nomorSimRule = Rule::unique('driver', 'nomor_sim');

        if ($driver !== null) {
            $nomorSimRule->ignore($driver->getKey(), 'id_driver');
        }

        return $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'nomor_telepon' => ['required', 'string', 'max:20'],
            'nomor_sim' => ['required', 'string', 'max:50', $nomorSimRule],
            'alamat' => ['required', 'string', 'max:255'],
            'status' => ['required', Rule::in(['aktif', 'tidak_aktif'])],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);
    }
}
