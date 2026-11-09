<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
class DriverController extends Controller {
    public function index(Request $request) {
        $query=Driver::query();
        if($request->filled('cari')) $query->where(fn($q)=>$q->where('nama','like','%'.$request->cari.'%')->orWhere('nomor_telepon','like','%'.$request->cari.'%'));
        if($request->filled('status')) $query->where('status',$request->status);
        return view('admin.master.index',['jenis'=>'driver','judul'=>'Driver','data'=>$query->latest()->paginate(8)->withQueryString()]);
    }
    public function create(){return view('admin.master.form',['jenis'=>'driver','judul'=>'Tambah Driver','item'=>new Driver]);}
    public function store(Request $request){
        $data=$this->validasi($request);
        if($request->hasFile('foto'))$data['foto']=$request->file('foto')->store('driver','public');
        Driver::create($data);
        return redirect()->route('admin.driver.index')->with('success','Driver berhasil ditambahkan.');
    }
    public function edit(Driver $driver){return view('admin.master.form',['jenis'=>'driver','judul'=>'Edit Driver','item'=>$driver]);}
    public function update(Request $request,Driver $driver){
        $data=$this->validasi($request,$driver);
        if($request->hasFile('foto')){
            $new=$request->file('foto')->store('driver','public');
            if($driver->foto)Storage::disk('public')->delete($driver->foto);
            $data['foto']=$new;
        }
        $driver->update($data);
        return redirect()->route('admin.driver.index')->with('success','Driver berhasil diperbarui.');
    }
    public function destroy(Driver $driver){
        $driver->delete();
        return redirect()->route('admin.driver.index')->with('success','Driver dihapus dari daftar.');
    }
    private function validasi(Request $request,?Driver $driver=null):array{
        return $request->validate([
            'nama'=>['required','string','max:255'],
            'nomor_telepon'=>['required','string','max:20'],
            'nomor_sim'=>['required','string','max:40',Rule::unique('driver','nomor_sim')->ignore($driver?->id)],
            'alamat'=>['required','string','max:1000'],
            'status'=>['required',Rule::in(['aktif','tidak_aktif'])],
            'foto'=>['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);
    }
}
