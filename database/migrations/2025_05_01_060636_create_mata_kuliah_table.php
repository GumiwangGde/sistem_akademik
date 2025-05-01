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
    Schema::create('mata_kuliah', function (Blueprint $table) {
        $table->id('id_mk');
        $table->unsignedBigInteger('id_dosen');
        $table->string('kode_mk');
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


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mata_kuliah');
    }
};
