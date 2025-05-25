<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_matakuliah', function (Blueprint $table) {
            $table->id('id_master_mk'); 
            $table->string('kode_mk', 20)->unique(); 
            $table->string('nama_mk', 150); 
            $table->integer('sks')->default(0);
            $table->integer('sks_total')->virtualAs('sks');
            $table->unsignedTinyInteger('semester_default')->nullable(); 
            $table->unsignedBigInteger('id_prodi')->nullable(); 
            $table->foreign('id_prodi')->references('id_prodi')->on('prodi')->onDelete('set null');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_matakuliah');
    }
};
