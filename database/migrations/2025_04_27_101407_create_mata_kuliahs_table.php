<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mata_kuliah', function (Blueprint $table) {
            $table->id('id_mk');
            $table->foreignId('id_dosen')->constrained('dosen')->onDelete('cascade');
            $table->foreignId('id_nilai')->nullable()->constrained('nilai')->onDelete('set null');
            $table->string('kode_mk')->unique();
            $table->string('nama_mk');
            $table->integer('sks');
            $table->string('semester');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('hari');
            $table->string('ruang');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mata_kuliah');
    }
};
