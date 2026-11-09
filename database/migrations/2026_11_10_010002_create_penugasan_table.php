<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('penugasan', function(Blueprint $table){
   $table->id();
   $table->foreignId('pemesanan_id')->unique()->constrained('pemesanan')->restrictOnDelete();
   $table->foreignId('driver_id')->constrained('driver')->restrictOnDelete();
   $table->foreignId('kendaraan_id')->constrained('kendaraan')->restrictOnDelete();
   $table->date('tanggal_mulai');
   $table->date('tanggal_selesai');
   $table->string('titik_kumpul',160);
   $table->time('jam_kumpul');
   $table->text('catatan')->nullable();
   $table->string('status',25)->default('terjadwal');
   $table->timestamps();
   $table->index(['driver_id','tanggal_mulai','tanggal_selesai'],'ix_penugasan_driver_tanggal');
   $table->index(['kendaraan_id','tanggal_mulai','tanggal_selesai'],'ix_penugasan_mobil_tanggal');
  });
 }
 public function down():void{ Schema::dropIfExists('penugasan'); }
};
