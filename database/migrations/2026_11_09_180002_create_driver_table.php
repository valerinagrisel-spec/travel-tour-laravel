<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void { Schema::create('driver', function (Blueprint $table) {
        $table->id(); $table->string('nama'); $table->string('nomor_telepon',20);
        $table->string('nomor_sim',40)->unique(); $table->text('alamat');
        $table->enum('status',['aktif','tidak_aktif'])->default('aktif');
        $table->string('foto')->nullable(); $table->timestamps(); $table->softDeletes();
    }); }
    public function down(): void { Schema::dropIfExists('driver'); }
};
