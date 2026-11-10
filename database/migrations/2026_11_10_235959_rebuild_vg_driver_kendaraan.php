<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // DESTRUKTIF: hapus data penugasan, driver, kendaraan saja.
        Schema::dropIfExists('penugasan');
        Schema::dropIfExists('driver');
        Schema::dropIfExists('kendaraan');

        Schema::create('kendaraan', function (Blueprint $table) {
            $table->string('id_kendaraan', 50)->primary('PK_KENDARAAN');
            $table->string('plat_nomor', 20)->unique('UQ_KENDARAAN_PLAT');
            $table->string('jenis_kendaraan', 50);
            $table->string('merek', 50);
            $table->integer('kapasitas');
            $table->integer('tahun_produksi');
            $table->string('status', 50)->default('tersedia');
            // Kolom teknis dipertahankan agar CRUD dan foto tetap berfungsi.
            $table->string('foto')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('driver', function (Blueprint $table) {
            $table->string('id_driver', 50)->primary('PK_DRIVER');
            $table->string('nama_lengkap', 100);
            $table->string('nomor_telepon', 20);
            $table->string('nomor_sim', 50)->unique('UQ_DRIVER_SIM');
            $table->string('alamat', 255);
            $table->string('status', 50)->default('aktif');
            $table->string('foto')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('user_id')->nullable()->unique()->constrained('users')->nullOnDelete();
        });
        Schema::create('penugasan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemesanan_id')->unique()->constrained('pemesanan')->restrictOnDelete();
            $table->string('driver_id', 50);
            $table->string('kendaraan_id', 50);
            $table->foreign('driver_id')->references('id_driver')->on('driver')->restrictOnDelete();
            $table->foreign('kendaraan_id')->references('id_kendaraan')->on('kendaraan')->restrictOnDelete();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('titik_kumpul', 160);
            $table->time('jam_kumpul');
            $table->text('catatan')->nullable();
            $table->string('status', 25)->default('terjadwal');
            $table->timestamps();
            $table->index(['driver_id','tanggal_mulai','tanggal_selesai'],'ix_penugasan_driver_tanggal');
            $table->index(['kendaraan_id','tanggal_mulai','tanggal_selesai'],'ix_penugasan_mobil_tanggal');
        });
    }
    public function down(): void {
        throw new \RuntimeException('Rollback otomatis dinonaktifkan: restore ekspor SQL bila ingin kembali ke skema lama.');
    }
};
