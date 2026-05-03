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
        Schema::create('spp', function (Blueprint $table) {
    $table->id();
    // Gunakan nama tabel sesuai yang ada di Schema::create tabel tujuan
    $table->foreignId('anggota_id')->constrained('anggotas')->onDelete('cascade');
    $table->foreignId('kolat_id')->constrained('kolat')->onDelete('cascade');
    $table->foreignId('kas_id')->nullable()->constrained('kas')->onDelete('set null');

    $table->string('bulan');
    $table->year('tahun');
    $table->date('jatuh_tempo');
    $table->integer('nominal');
    $table->enum('status', ['lunas', 'pending', 'belum_bayar']);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spp');
    }
};
