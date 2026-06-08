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
        Schema::create('anggota_role', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel anggotas
            $table->foreignId('anggota_id')->constrained('anggotas')->onDelete('cascade');

            // Relasi ke tabel roles (asumsi nama tabel master role kamu adalah 'roles')
            $table->foreignId('role_id')->constrained('role')->onDelete('cascade');

            $table->timestamps();

            // Kunci unik agar satu orang tidak bisa punya role yang sama persis secara ganda
            $table->unique(['anggota_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota_role');
    }
};
