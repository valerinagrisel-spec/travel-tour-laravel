
<?php

use Illuminate\Support\Facades\Route;

use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\EnsureRole;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LaporanTransaksiController;

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\PaketTravelController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\KendaraanController;
use App\Http\Controllers\Admin\PenugasanController;

use App\Http\Controllers\Customer\TravelController;
use App\Http\Controllers\Staff\VerifikasiController;
use App\Http\Controllers\Staff\PelangganController;
use App\Http\Controllers\Driver\JadwalTourController;


// ===============================
// HALAMAN PUBLIK
// ===============================

Route::get('/', fn() => redirect()->route('travel.katalog'));

Route::get('/paket-wisata', [TravelController::class, 'katalog'])
    ->name('travel.katalog');

Route::get('/paket-wisata/{paket}', [TravelController::class, 'detail'])
    ->name('travel.detail');


// ===============================
// LOGIN & REGISTER
// ===============================

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.submit');

    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register.submit');
});


// ===============================
// AKUN LOGIN
// ===============================

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [AuthController::class, 'dashboard'])
        ->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});


// ===============================
// ADMIN
// ===============================

Route::middleware(['auth', EnsureAdmin::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('paket', PaketTravelController::class)
            ->except('show');

        Route::resource('driver', DriverController::class)
            ->except('show');

        Route::resource('kendaraan', KendaraanController::class)
            ->except('show');

        Route::get('/penugasan', [PenugasanController::class, 'index'])
            ->name('penugasan.index');

        Route::post('/penugasan', [PenugasanController::class, 'store'])
            ->name('penugasan.store');

        Route::post('/penugasan/hubungkan-driver', [
            PenugasanController::class,
            'hubungkanDriver'
        ])->name('penugasan.link');
    });


// ===============================
// CUSTOMER
// ===============================

Route::middleware(['auth', EnsureRole::class . ':customer'])
    ->group(function () {

        Route::get('/pesan/{paket}', [TravelController::class, 'form'])
            ->name('customer.pemesanan.create');

        Route::post('/pesan/{paket}', [TravelController::class, 'checkout'])
            ->name('customer.pemesanan.store');

        Route::get('/pesanan-saya', [TravelController::class, 'pesanan'])
            ->name('customer.pesanan.index');

        Route::get('/pesanan-saya/{pemesanan}', [TravelController::class, 'show'])
            ->name('customer.pesanan.show');

        Route::post('/pesanan-saya/{pemesanan}/bukti', [
            TravelController::class,
            'upload'
        ])->name('customer.pesanan.upload');
    });


// ===============================
// STAFF
// ===============================

Route::middleware(['auth', EnsureRole::class . ':staff'])
    ->prefix('staff')
    ->name('staff.')
    ->group(function () {

        Route::get('/verifikasi', [VerifikasiController::class, 'index'])
            ->name('verifikasi');

        Route::get('/bukti/{pemesanan}', [VerifikasiController::class, 'bukti'])
            ->name('bukti');

        Route::post('/pembayaran/{pemesanan}', [
            VerifikasiController::class,
            'pembayaran'
        ])->name('pembayaran');

        Route::post('/pemesanan/{pemesanan}', [
            VerifikasiController::class,
            'pesanan'
        ])->name('pesanan');

        Route::get('/laporan', [LaporanTransaksiController::class, 'staff'])
            ->name('laporan.index');

        Route::post('/laporan/kirim', [LaporanTransaksiController::class, 'kirim'])
            ->name('laporan.kirim');
    });



// ===============================
// DRIVER
// ===============================

Route::middleware(['auth', EnsureRole::class . ':driver'])
    ->prefix('driver')
    ->name('driver.')
    ->group(function () {

        Route::get('/jadwal-tour', [JadwalTourController::class, 'index'])
            ->name('jadwal.index');

        Route::get('/jadwal-tour/{penugasan}', [
            JadwalTourController::class,
            'show'
        ])->name('jadwal.show');
    });


// ===============================
// MANAGER
// ===============================

Route::middleware(['auth', EnsureRole::class . ':manager'])
    ->prefix('manager')
    ->name('manager.')
    ->group(function () {

        Route::get('/laporan', [LaporanTransaksiController::class, 'manager'])
            ->name('laporan.index');

        Route::get('/laporan/pdf', [LaporanTransaksiController::class, 'pdf'])
            ->name('laporan.pdf');

        Route::get('/laporan/excel', [
            LaporanTransaksiController::class,
            'excel'
        ])->name('laporan.excel');
    });


Route::middleware('auth')->prefix('pelanggan')->name('pelanggan.')->group(function () {
    Route::get('/', [PelangganController::class, 'getAll_pelanggan'])->name('index');
    Route::get('/cari', [PelangganController::class, 'cari_pelanggan'])->name('cari');
    Route::get('/{idPelanggan}', [PelangganController::class, 'get_detail_pelanggan'])->name('detail');
});


Route::middleware('auth')
    ->prefix('pelanggan')
    ->name('pelanggan.')
    ->group(function () {

        Route::get(
            '/',
            [PelangganController::class, 'getAll_pelanggan']
        )->name('index');

        Route::get(
            '/cari',
            [PelangganController::class, 'cari_pelanggan']
        )->name('cari');

        Route::get(
            '/{idPelanggan}',
            [PelangganController::class, 'get_detail_pelanggan']
        )->name('detail');
    });
