<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dosen', function (Blueprint $table) {
            $table->bigIncrements('id_dosen'); // PK
            $table->unsignedBigInteger('user_id')->unique(); // FK ke tabel users
            $table->string('nidn')->unique(); // Nomor Induk Dosen Nasional
            $table->boolean('is_dosen_wali')->default(false); // Menandai apakah dosen wali
            $table->timestamps();

            // Foreign key ke tabel users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dosen');
    }
};

