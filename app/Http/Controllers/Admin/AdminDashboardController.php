<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\PaketTravel;
use App\Models\Driver;
use App\Models\Kendaraan;
class AdminDashboardController extends Controller {
    public function index(){
        return view('admin.dashboard',[
            'paketCount'=>PaketTravel::count(),
            'driverCount'=>Driver::count(),
            'kendaraanCount'=>Kendaraan::count(),
            'tersediaCount'=>Kendaraan::where('status','tersedia')->count(),
            'perawatanCount'=>Kendaraan::where('status','perawatan')->count(),
            'digunakanCount'=>Kendaraan::where('status','digunakan')->count(),
            'paketTerpopuler'=>PaketTravel::orderBy('nama_paket')->take(3)->get(),
            'aktivitas'=>collect(), // log aktivitas belum ada pada modul pertama
        ]);
    }
}
