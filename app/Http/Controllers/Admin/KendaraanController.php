<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
class KendaraanController extends Controller {
    public function index(Request $request){
        $query=Kendaraan::query();
        if($request->filled('cari'))$query->where(fn($q)=>$q->where('plat_nomor','like','%'.$request->cari.'%')->orWhere('merek','like','%'.$request->cari.'%')->orWhere('jenis','like','%'.$request->cari.'%'));
        if($request->filled('status'))$query->where('status',$request->status);
        return view('admin.master.index',['jenis'=>'kendaraan','judul'=>'Kendaraan','data'=>$query->latest()->paginate(8)->withQueryString()]);
    }
    public function create(){return view('admin.master.form',['jenis'=>'kendaraan','judul'=>'Tambah Kendaraan','item'=>new Kendaraan]);}
    public function store(Request $request){
        $data=$this->validasi($request);
        if($request->hasFile('foto'))$data['foto']=$request->file('foto')->store('kendaraan','public');
        Kendaraan::create($data);
        return redirect()->route('admin.kendaraan.index')->with('success','Kendaraan berhasil ditambahkan.');
    }
    public function edit(Kendaraan $kendaraan){return view('admin.master.form',['jenis'=>'kendaraan','judul'=>'Edit Kendaraan','item'=>$kendaraan]);}
    public function update(Request $request,Kendaraan $kendaraan){
        $data=$this->validasi($request,$kendaraan);
        if($request->hasFile('foto')){
            $new=$request->file('foto')->store('kendaraan','public');
            if($kendaraan->foto)Storage::disk('public')->delete($kendaraan->foto);
            $data['foto']=$new;
        }
        $kendaraan->update($data);
        return redirect()->route('admin.kendaraan.index')->with('success','Kendaraan berhasil diperbarui.');
    }
    public function destroy(Kendaraan $kendaraan){
        $kendaraan->delete();
        return redirect()->route('admin.kendaraan.index')->with('success','Kendaraan dihapus dari daftar.');
    }
    private function validasi(Request $request,?Kendaraan $kendaraan=null):array{
        return $request->validate([
            'plat_nomor'=>['required','string','max:20',Rule::unique('kendaraan','plat_nomor')->ignore($kendaraan?->id)],
            'jenis'=>['required','string','max:50'], 'merek'=>['required','string','max:255'],
            'kapasitas'=>['required','integer','min:1','max:200'],
            'tahun_produksi'=>['required','integer','min:1980','max:'.(date('Y')+1)],
            'status'=>['required',Rule::in(['tersedia','digunakan','perawatan','tidak_aktif'])],
            'foto'=>['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);
    }
}
