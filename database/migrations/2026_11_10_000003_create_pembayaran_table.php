<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('pembayaran',function(Blueprint $table){
   $table->id();
   $table->foreignId('pemesanan_id')->unique()->constrained('pemesanan')->restrictOnDelete();
   $table->string('nomor_invoice',50)->unique();
   $table->decimal('nominal',14,2);
   $table->string('bukti_pembayaran')->nullable();
   $table->timestamp('tanggal_bayar')->nullable();
   $table->string('status',40)->default('belum_dibayar');
   $table->text('alasan_penolakan')->nullable();
   $table->timestamps();
  });
 }
 public function down(): void {Schema::dropIfExists('pembayaran');}
};
