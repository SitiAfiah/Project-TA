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
    $table->text('keterangan')->nullable(); // Alasan izin/sakit
    $table->timestamp('waktu_presensi')->nullable(); // Catat jam tepatnya dia absen
            $table->timestamps();
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
