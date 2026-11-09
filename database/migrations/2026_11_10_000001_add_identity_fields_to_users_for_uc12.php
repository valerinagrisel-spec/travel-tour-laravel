<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'nomor_identitas')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('nomor_identitas', 50)->nullable();
            });
        }
        if (! Schema::hasColumn('users', 'dokumen_identitas')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('dokumen_identitas', 300)->nullable();
            });
        }
    }

    public function down(): void
    {
        // Tidak otomatis menghapus data identitas yang mungkin sudah terisi.
    }
};
