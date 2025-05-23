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
        Schema::create('frs', function (Blueprint $table) {
            $table->id('id_frs');
            $table->unsignedBigInteger('id_mahasiswa');
            $table->unsignedBigInteger('id_mk'); // Referensi langsung ke matakuliah
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->timestamps();

            // Foreign keys
            $table->foreign('id_mahasiswa')->references('id_mahasiswa')->on('mahasiswa')->onDelete('cascade');
            $table->foreign('id_mk')->references('id_mk')->on('matakuliah')->onDelete('cascade');

            // Prevent duplicate entries
            $table->unique(['id_mahasiswa', 'id_mk'], 'unique_frs_entry');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frs');
    }
};