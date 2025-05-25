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
        Schema::create('tahun_ajaran', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('kode_tahun_ajaran', 20)->unique(); // Contoh: 20231 (2023 Ganjil), 20232 (2023 Genap)
            $table->string('nama_tahun_ajaran', 100); // Contoh: "Tahun Ajaran 2023/2024 Ganjil"
            $table->enum('semester', ['Ganjil', 'Genap']);
            $table->year('tahun_mulai');
            $table->year('tahun_selesai');
            $table->enum('status', ['aktif', 'tidak aktif', 'direncanakan'])->default('direncanakan');
            $table->date('tanggal_mulai_perkuliahan')->nullable();
            $table->date('tanggal_selesai_perkuliahan')->nullable();
            $table->date('tanggal_mulai_frs')->nullable();
            $table->date('tanggal_selesai_frs')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahun_ajaran');
    }
};
