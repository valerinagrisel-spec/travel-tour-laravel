<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('pemesanan', function(Blueprint $table){
   $table->id();
   $table->string('kode_pemesanan',40)->unique();
   $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
   $table->foreignId('paket_travel_id')->constrained('paket_travel')->restrictOnDelete();
   $table->date('tanggal_berangkat');
   $table->unsignedInteger('jumlah_peserta');
   $table->decimal('harga_satuan',14,2);
   $table->decimal('biaya_admin',14,2)->default(0);
   $table->decimal('total_pembayaran',14,2);
   $table->string('email_pemesan');
   $table->string('telepon_pemesan',20);
   $table->string('status',40)->default('belum_terbayar');
   $table->timestamp('batas_waktu_pembayaran');
   $table->text('link_grup_wa')->nullable();
   $table->text('alasan_penolakan')->nullable();
   $table->timestamps();
   $table->index(['paket_travel_id','status']);
  });
 }
 public function down(): void {Schema::dropIfExists('pemesanan');}
};
