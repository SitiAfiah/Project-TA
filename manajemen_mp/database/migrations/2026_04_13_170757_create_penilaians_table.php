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
            $table->foreignId('pelatih_id')->constrained('anggotas')->onDelete('cascade'); // Pelatih yang dinilai
            $table->foreignId('anggota_id')->constrained('anggotas')->onDelete('cascade'); // Anggota yang menilai
            $table->date('bulan_evaluasi'); // Untuk menandai evaluasi bulan apa

            // Kriteria Evaluasi Pelatih (Skala 1-5 atau 1-10)
            $table->integer('kedisiplinan')->default(0);
            $table->integer('kejelasan_materi')->default(0);
            $table->integer('penguasaan_teknik')->default(0);
            $table->integer('sikap_perilaku')->default(0); // Etika pelatih
            $table->text('kritik_saran')->nullable(); // Masukan dari anggota
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
