<?php

namespace App\Http\Controllers\Staff;

use App\Entities\Pelanggan_Entity;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

/** UC-12, CD-22, PC-35, PC-36, PC-37. */
class PelangganController extends Controller
{
    public function __construct(private Pelanggan_Entity $entity) {}

    private function cekAkses(): void
    {
        // Menyamakan tambahan akses Manager dengan implementasi repo teman.
        abort_unless(auth()->check() && in_array(auth()->user()->role, ['staff', 'manager'], true), 403);
    }

    /** PC-35 */
    public function getAll_pelanggan()
    {
        $this->cekAkses();
        $pelanggan = $this->entity->getAll_data_pelanggan();
        $keyword = '';
        return view('pelanggan.index', compact('pelanggan', 'keyword'));
    }

    /** PC-36 */
    public function cari_pelanggan(Request $request)
    {
        $this->cekAkses();
        $keyword = trim((string) ($request->validate(['keyword' => ['nullable','string','max:100']])['keyword'] ?? ''));
        if ($keyword === '') return redirect()->route('pelanggan.index');
        $pelanggan = $this->entity->get_cari_pelanggan($keyword);
        return view('pelanggan.index', compact('pelanggan', 'keyword'));
    }

    /** CD-22: cek_data_pelanggan(hasilCari) */
    public function cek_data_pelanggan(Collection $hasilCari): bool
    {
        return $hasilCari->isNotEmpty();
    }

    /** PC-37: read-only, no status mutation. */
    public function get_detail_pelanggan(string $idPelanggan)
    {
        $this->cekAkses();
        $pelanggan = $this->entity->get_data_detail_pelanggan($idPelanggan);
        abort_unless($pelanggan, 404);
        return view('pelanggan.show', compact('pelanggan'));
    }
}
