<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('berita', function (Blueprint $table) {
            $table->id(); // Primary key, auto-increment
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Admin yang membuat/mengupdate berita
            $table->string('judul'); // Judul berita
            $table->string('slug')->unique(); // Slug untuk URL yang SEO-friendly
            $table->longText('isi'); // Isi berita, bisa HTML
            $table->string('gambar_url')->nullable(); // URL atau path ke gambar ilustrasi (opsional)
            $table->enum('target_role', ['dosen', 'mahasiswa', 'semua'])->default('semua'); // Target audiens berita
            $table->enum('status', ['draft', 'terbit'])->default('draft'); // Status publikasi berita
            $table->timestamp('published_at')->nullable(); // Tanggal berita dijadwalkan untuk terbit (opsional)
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('berita');
    }
};
