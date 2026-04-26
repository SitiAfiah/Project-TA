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
        Schema::create('presensis', function (Blueprint $table) {
        $table->id();
        $table->foreignId('jadwal_id')->constrained('jadwals')->onDelete('cascade');
        $table->foreignId('anggota_id')->constrained('anggotas')->onDelete('cascade');
        $table->enum('status', ['Hadir', 'Izin', 'Sakit', 'Alfa'])->default('Alfa');
        $table->text('keterangan')->nullable(); 
        $table->timestamp('waktu_presensi')->nullable();

        // TAMBAHKAN 2 BARIS INI:
        $table->boolean('is_verified')->default(false); // Untuk tahu sudah di-ACC pelatih atau belum
        $table->unsignedBigInteger('verified_by')->nullable(); // Untuk catat ID pelatih yang meng-ACC
        
        $table->timestamps();

        // Tambahkan foreign key untuk verified_by
        $table->foreign('verified_by')->references('id')->on('anggotas');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensis');
    }
};
