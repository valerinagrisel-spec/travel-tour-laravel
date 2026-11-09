<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('laporan_terkirim', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_user_id')->constrained('users')->restrictOnDelete();
            $table->date('periode_awal');
            $table->date('periode_akhir');
            $table->unsignedInteger('jumlah_transaksi');
            $table->decimal('total_nominal', 16, 2);
            $table->timestamps();
            $table->index(['periode_awal', 'periode_akhir']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_terkirim');
    }
};
