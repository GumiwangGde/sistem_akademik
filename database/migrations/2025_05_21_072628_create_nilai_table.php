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
        Schema::create('nilai', function (Blueprint $table) {
            $table->id('id_nilai');
            $table->unsignedBigInteger('id_frs');
            $table->decimal('nilai_angka', 5, 2)->nullable();
            $table->string('nilai_huruf', 3)->nullable();
            $table->enum('status_penilaian', ['belum_dinilai', 'sudah_dinilai'])->default('belum_dinilai');
            $table->timestamps();

            // Foreign key
            $table->foreign('id_frs')->references('id_frs')->on('frs')->onDelete('cascade');
            
            // Each FRS entry should have only one grade record
            $table->unique('id_frs', 'unique_frs_nilai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai');
    }
};