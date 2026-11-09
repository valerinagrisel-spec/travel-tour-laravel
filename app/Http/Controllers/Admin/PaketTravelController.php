<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\PaketTravel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class PaketTravelController extends Controller {
    public function index(Request $request) {
        $query=PaketTravel::query();
        if ($request->filled('cari')) $query->where(fn($q)=>$q->where('nama_paket','like','%'.$request->cari.'%')->orWhere('tujuan','like','%'.$request->cari.'%'));
        if ($request->filled('tujuan')) $query->where('tujuan',$request->tujuan);
        return view('admin.master.index', [
            'jenis'=>'paket','judul'=>'Paket Travel','data'=>$query->latest()->paginate(8)->withQueryString(),
            'tujuanList'=>PaketTravel::select('tujuan')->distinct()->orderBy('tujuan')->pluck('tujuan')
        ]);
    }
    public function create() {return view('admin.master.form',['jenis'=>'paket','judul'=>'Tambah Paket Wisata','item'=>new PaketTravel]);}
    public function store(Request $request) {
        $data=$this->validasi($request);
        if ($request->hasFile('foto')) $data['foto']=$request->file('foto')->store('paket','public');
        PaketTravel::create($data);
        return redirect()->route('admin.paket.index')->with('success','Paket wisata berhasil ditambahkan.');
    }
    public function edit(PaketTravel $paket) {return view('admin.master.form',['jenis'=>'paket','judul'=>'Edit Paket Wisata','item'=>$paket]);}
    public function update(Request $request,PaketTravel $paket) {
        $data=$this->validasi($request);
        if ($request->hasFile('foto')) {
            $new=$request->file('foto')->store('paket','public');
            if($paket->foto)Storage::disk('public')->delete($paket->foto);
            $data['foto']=$new;
        }
        $paket->update($data);
        return redirect()->route('admin.paket.index')->with('success','Paket wisata berhasil diperbarui.');
    }
    public function destroy(PaketTravel $paket) {
        // Soft delete: tidak menghapus foto, agar riwayat transaksi nanti tetap terjaga.
        $paket->delete();
        return redirect()->route('admin.paket.index')->with('success','Paket wisata berhasil dihapus dari daftar.');
    }
    private function validasi(Request $request): array {
        return $request->validate([
            'nama_paket'=>['required','string','max:255'], 'tujuan'=>['required','string','max:255'],
            'durasi_hari'=>['required','integer','min:1','max:365'],
            'harga'=>['required','numeric','min:0'], 'kuota'=>['required','integer','min:1'],
            'tanggal_berangkat'=>['required','date'], 'deskripsi'=>['nullable','string','max:5000'],
            'foto'=>['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);
    }
}
