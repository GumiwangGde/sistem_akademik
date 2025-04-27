<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dosen', function (Blueprint $table) {
            $table->id('id_dosen');
            $table->string('nidn')->unique();
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('is_dosen_wali')->default(false);
            $table->string('role')->default('dosen');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dosen');
    }
};
