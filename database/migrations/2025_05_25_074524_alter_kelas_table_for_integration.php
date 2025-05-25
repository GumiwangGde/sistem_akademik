<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Nama file: timestamp_alter_kelas_table_for_integration.php
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            // Tambahkan kolom foreign key untuk tahun_ajaran
            $table->unsignedBigInteger('id_tahun_ajaran')->nullable()->after('id_dosen_wali'); // Sesuaikan 'after'
            $table->foreign('id_tahun_ajaran')->references('id')->on('tahun_ajaran')->onDelete('set null');

            // Tambahkan kolom foreign key untuk prodi
            $table->unsignedBigInteger('id_prodi')->nullable()->after('id_tahun_ajaran');
            $table->foreign('id_prodi')->references('id_prodi')->on('prodi')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropForeign(['id_tahun_ajaran']);
            $table->dropColumn('id_tahun_ajaran');

            $table->dropForeign(['id_prodi']);
            $table->dropColumn('id_prodi');
        });
    }
};
