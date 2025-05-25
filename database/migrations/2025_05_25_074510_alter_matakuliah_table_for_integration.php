<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Nama file: timestamp_alter_matakuliah_table_for_integration.php
// Pastikan nama class unik jika tidak menggunakan anonymous class
// class AlterMatakuliahTableForIntegration extends Migration 
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('matakuliah', function (Blueprint $table) {
            // Tambahkan kolom foreign key untuk tahun_ajaran setelah kolom 'id_mk' atau sesuai preferensi
            $table->unsignedBigInteger('id_tahun_ajaran')->nullable()->after('ruang_id'); // Sesuaikan 'after' jika perlu
            $table->foreign('id_tahun_ajaran')->references('id')->on('tahun_ajaran')->onDelete('set null');

            // Tambahkan kolom foreign key untuk master_matakuliah
            $table->unsignedBigInteger('id_master_mk')->nullable()->after('id_tahun_ajaran');
            $table->foreign('id_master_mk')->references('id_master_mk')->on('master_matakuliah')->onDelete('set null');

            // Kolom kode_mk, nama_mk, sks, semester di tabel matakuliah (jadwal)
            // sekarang bisa menjadi redundant jika data diambil dari master_matakuliah dan tahun_ajaran.
            // Anda bisa mempertimbangkan untuk membuatnya nullable atau menghapusnya di migrasi terpisah
            // setelah migrasi data. Untuk saat ini, kita biarkan.
            // Contoh jika ingin membuat nullable:
            // $table->string('kode_mk')->nullable()->change();
            // $table->string('nama_mk')->nullable()->change();
            // $table->integer('sks')->nullable()->change();
            // $table->string('semester')->nullable()->change(); // Semester kurikulum, bisa jadi tetap relevan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matakuliah', function (Blueprint $table) {
            $table->dropForeign(['id_tahun_ajaran']);
            $table->dropColumn('id_tahun_ajaran');

            $table->dropForeign(['id_master_mk']);
            $table->dropColumn('id_master_mk');

            // Jika Anda mengubah kolom menjadi nullable di up(), kembalikan di sini jika perlu
            // $table->string('kode_mk')->nullable(false)->change();
            // $table->string('nama_mk')->nullable(false)->change();
            // $table->integer('sks')->nullable(false)->change();
            // $table->string('semester')->nullable(false)->change();
        });
    }
};
