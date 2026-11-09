<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void { Schema::create('kendaraan', function (Blueprint $table) {
        $table->id(); $table->string('plat_nomor',20)->unique();
        $table->string('jenis',50); $table->string('merek');
        $table->unsignedSmallInteger('kapasitas'); $table->unsignedSmallInteger('tahun_produksi');
        $table->enum('status',['tersedia','digunakan','perawatan','tidak_aktif'])->default('tersedia');
        $table->string('foto')->nullable(); $table->timestamps(); $table->softDeletes();
    }); }
    public function down(): void { Schema::dropIfExists('kendaraan'); }
};
