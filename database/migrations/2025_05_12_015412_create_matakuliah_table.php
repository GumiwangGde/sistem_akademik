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
    Schema::create('matakuliah', function (Blueprint $table) {
        $table->id('id_mk');
        $table->unsignedBigInteger('id_dosen'); // Foreign key for Dosen
        $table->unsignedBigInteger('kelas_id'); // Foreign key for Kelas
        $table->string('kode_mk');
        $table->string('nama_mk');
        $table->integer('sks');
        $table->string('semester');
        $table->time('jam_mulai');
        $table->time('jam_selesai');
        $table->string('hari');
        $table->foreignId('ruang_id')->constrained('ruang')->onDelete('cascade');

        // Foreign keys
        $table->foreign('id_dosen')->references('id_dosen')->on('dosen')->onDelete('cascade');
        $table->foreign('kelas_id')->references('id_kelas')->on('kelas')->onDelete('cascade');

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matakuliah');
    }
};
