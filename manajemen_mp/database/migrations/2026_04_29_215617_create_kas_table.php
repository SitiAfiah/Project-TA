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
       Schema::create('kas', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->enum('jenis', ['masuk', 'keluar']);
            // Kategori untuk membedakan asal uang (SPP, Seragam, Operasional, dll)
            $table->string('kategori');
            $table->decimal('nominal', 15, 2);
            $table->text('keterangan')->nullable();

            // Saldo akhir disimpan per baris transaksi agar history saldo terlihat jelas
            $table->decimal('saldo_akhir', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kas');
    }
};
