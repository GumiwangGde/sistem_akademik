<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Nama file: timestamp_alter_frs_table_for_tahun_ajaran_integration.php
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('frs', function (Blueprint $table) {
            // Tambahkan kolom foreign key untuk tahun_ajaran
            // Kolom id_mk di FRS merujuk ke tabel matakuliah (jadwal_kuliah)
            // yang juga sudah memiliki id_tahun_ajaran.
            // Penambahan id_tahun_ajaran di FRS bisa untuk denormalisasi atau query yang lebih mudah.
            $table->unsignedBigInteger('id_tahun_ajaran')->nullable()->after('id_mk'); // Sesuaikan 'after'
            $table->foreign('id_tahun_ajaran')->references('id')->on('tahun_ajaran')->onDelete('set null');

            // Perbarui unique constraint jika diperlukan untuk menyertakan id_tahun_ajaran
            // Hapus unique constraint lama jika ada dan namanya diketahui
            // $table->dropUnique('unique_frs_entry'); // Nama dari migrasi FRS awal
            
            // Tambahkan unique constraint baru
            // $table->unique(['id_mahasiswa', 'id_mk', 'id_tahun_ajaran'], 'unique_frs_entry_per_tahun_ajaran');
            // Untuk saat ini, saya akan membiarkan unique constraint yang ada,
            // karena id_mk (jadwal) sudah spesifik per tahun ajaran setelah tabel matakuliah diubah.
            // Jika id_mk di FRS bisa sama untuk mahasiswa yang sama di tahun ajaran berbeda (misal mengulang),
            // maka unique constraint perlu diubah.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('frs', function (Blueprint $table) {
            $table->dropForeign(['id_tahun_ajaran']);
            $table->dropColumn('id_tahun_ajaran');

            // Jika Anda mengubah unique constraint di up(), kembalikan di sini
            // $table->dropUnique('unique_frs_entry_per_tahun_ajaran');
            // $table->unique(['id_mahasiswa', 'id_mk'], 'unique_frs_entry');
        });
    }
};
