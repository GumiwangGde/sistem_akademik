<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('master_matakuliah', function (Blueprint $table) {
            $table->id('id_master_mk'); // Primary key
            $table->string('kode_mk', 20)->unique(); // Kode mata kuliah unik
            $table->string('nama_mk', 150); // Nama mata kuliah
            $table->integer('sks_teori')->default(0);
            $table->integer('sks_praktek')->default(0);
            $table->integer('sks_lapangan')->default(0);
            $table->integer('sks_total')->virtualAs('sks_teori + sks_praktek + sks_lapangan'); // Kolom virtual untuk total SKS
            $table->unsignedTinyInteger('semester_default')->nullable(); // Semester default penawaran (misal: 1, 2, .. 8)
            
            $table->unsignedBigInteger('id_prodi')->nullable(); // Foreign key ke tabel prodi
            $table->foreign('id_prodi')->references('id_prodi')->on('prodi')->onDelete('set null');
            
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_matakuliah');
    }
};
