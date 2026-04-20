<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kolat_id')->constrained('kolat')->onDelete('cascade');
            $table->foreignId('pelatih_id')->constrained('anggotas')->onDelete('cascade'); // Relasi ke anggota (yang rolenya pelatih)
            $table->string('judul_kegiatan'); // Misal: Latihan Rutin, UKT, atau Pengumuman Libur
            $table->enum('jenis', ['Rutin', 'Tambahan', 'Pengumuman']);
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai')->nullable();
            $table->string('lokasi');
            $table->text('deskripsi')->nullable(); // Untuk isi pengumuman
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};
