<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id('id_kelas');
            $table->foreignId('id_dosen')->constrained('dosen')->onDelete('cascade');
            $table->foreignId('id_jadwal')->constrained('jadwal_kuliah')->onDelete('cascade');
            $table->string('nama_kelas');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
