<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Nama file: timestamp_alter_mahasiswa_table_for_prodi_integration.php
return new class extends Migration
{
    /**
     * Run the migrations.
     * PERHATIAN: Perubahan ini berpotensi breaking change jika ada data prodi (string) yang sudah ada.
     * Anda mungkin memerlukan strategi migrasi data.
     */
    public function up(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            // Tambahkan kolom id_prodi (FK) setelah 'nama' atau sesuai preferensi
            $table->unsignedBigInteger('id_prodi_new')->nullable()->after('nama'); // Kolom sementara
            // Di sini Anda idealnya melakukan migrasi data dari kolom 'prodi' (string) ke 'id_prodi_new'
            // Contoh: UPDATE mahasiswa SET id_prodi_new = (SELECT id_prodi FROM prodi WHERE prodi.nama_prodi = mahasiswa.prodi LIMIT 1);
            // Ini harus dilakukan dengan DB::statement atau sejenisnya sebelum drop kolom lama.
        });

        // Setelah data dimigrasikan (jika ada), hapus kolom lama dan rename kolom baru
        // Ini dipisah agar bisa ada data migration step diantaranya jika diperlukan
        Schema::table('mahasiswa', function (Blueprint $table) {
            // Hapus kolom 'prodi' yang lama (string)
            // $table->dropColumn('prodi'); // Lakukan ini jika sudah yakin data termigrasi

            // Rename kolom baru dan tambahkan constraint foreign key
            // Jika tidak ada migrasi data, Anda bisa langsung menambahkan id_prodi dan menghapus string prodi
            // Untuk contoh ini, saya akan langsung menambahkan id_prodi dan menghapus kolom string 'prodi'
            // Jika Anda punya data, lakukan migrasi data dulu!
            
            // Hapus kolom prodi string yang lama
            if (Schema::hasColumn('mahasiswa', 'prodi')) {
                $table->dropColumn('prodi');
            }

            // Tambah kolom id_prodi sebagai FK
            if (!Schema::hasColumn('mahasiswa', 'id_prodi')) {
                $table->unsignedBigInteger('id_prodi')->nullable()->after('nama'); // Atau posisi yang sesuai
                $table->foreign('id_prodi')->references('id_prodi')->on('prodi')->onDelete('set null');
            } elseif (Schema::hasColumn('mahasiswa', 'id_prodi_new')) {
                // Jika menggunakan kolom sementara 'id_prodi_new'
                $table->renameColumn('id_prodi_new', 'id_prodi');
                // Pastikan foreign key sudah ada atau tambahkan di sini jika belum
                // $table->foreign('id_prodi')->references('id_prodi')->on('prodi')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            if (Schema::hasColumn('mahasiswa', 'id_prodi')) {
                $table->dropForeign(['id_prodi']);
                $table->dropColumn('id_prodi');
            }

            // Tambahkan kembali kolom prodi string jika di rollback
            if (!Schema::hasColumn('mahasiswa', 'prodi')) {
                 $table->string('prodi')->after('nama'); // Sesuaikan posisi
            }
        });
    }
};
