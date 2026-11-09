<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void { Schema::create('paket_travel', function (Blueprint $table) {
        $table->id(); $table->string('nama_paket'); $table->string('tujuan');
        $table->unsignedInteger('durasi_hari'); $table->decimal('harga',14,2);
        $table->unsignedInteger('kuota'); $table->date('tanggal_berangkat');
        $table->text('deskripsi')->nullable(); $table->string('foto')->nullable();
        $table->timestamps(); $table->softDeletes();
    }); }
    public function down(): void { Schema::dropIfExists('paket_travel'); }
};
