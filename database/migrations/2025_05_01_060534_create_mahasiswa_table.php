<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id('id_mahasiswa');
            $table->string('nrp');
            $table->string('nama');
            $table->string('prodi');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['mahasiswa', 'dosen', 'admin']);
            $table->unsignedBigInteger('id_kelas');
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
