<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('penumpang',function(Blueprint $table){
   $table->id();
   $table->foreignId('pemesanan_id')->constrained('pemesanan')->cascadeOnDelete();
   $table->string('nama_lengkap',120);
   $table->text('nik');
   $table->date('tanggal_lahir');
   $table->timestamps();
  });
 }
 public function down(): void {Schema::dropIfExists('penumpang');}
};
