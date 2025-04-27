<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('frs', function (Blueprint $table) {
            $table->id('id_frs');
            $table->foreignId('id_mahasiswa')->constrained('mahasiswa')->onDelete('cascade');
            $table->foreignId('id_jadwal_kuliah')->constrained('jadwal_kuliah')->onDelete('cascade');
            $table->foreignId('id_mata_kuliah')->constrained('mata_kuliah')->onDelete('cascade');
            $table->string('semester');
            $table->string('tahun_ajaran');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('frs');
    }
};
