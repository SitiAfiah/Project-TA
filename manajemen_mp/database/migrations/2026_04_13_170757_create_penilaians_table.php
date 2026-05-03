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
        Schema::create('penilaians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelatih_id')->constrained('anggotas')->onDelete('cascade');
            $table->foreignId('anggota_id')->constrained('anggotas')->onDelete('cascade');
            $table->date('bulan_evaluasi');

            // 6 Kriteria Evaluasi Hasil Wawancara (Skala 1-5)
            $table->integer('metode_pelatihan')->default(0);
            $table->integer('komunikasi')->default(0);
            $table->integer('sikap_kepribadian')->default(0);
            $table->integer('kepemimpinan')->default(0);
            $table->integer('konsistensi_komitmen')->default(0);
            $table->integer('kedekatan_interpersonal')->default(0);

            $table->text('kritik_saran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaians');
    }
};
