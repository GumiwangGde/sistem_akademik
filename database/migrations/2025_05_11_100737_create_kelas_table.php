<?php


// Migration: create_kelas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKelasTable extends Migration
{
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id('id_kelas'); // Kolom primary key untuk kelas
            $table->string('nama_kelas'); // Nama kelas
            $table->enum('status', ['inactive', 'active'])->default('inactive'); // Status kelas (default: inactive)
            
            // Kolom foreign key yang merujuk ke id_dosen di tabel dosen
            $table->unsignedBigInteger('id_dosen_wali')->nullable(); // Foreign key untuk dosen wali (nullable jika status inactive)
            
            // Menambahkan foreign key constraint ke tabel dosen
            $table->foreign('id_dosen_wali')
                  ->references('id_dosen')
                  ->on('dosen')
                  ->onDelete('set null'); // Jika dosen dihapus, set nilai id_dosen_wali ke null

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
}
