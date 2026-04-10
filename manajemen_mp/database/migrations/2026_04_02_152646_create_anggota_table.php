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
    Schema::create('anggotas', function (Blueprint $table) {
        $table->id();

        // --- RELASI (Penghubung ke Dropdown) ---
        $table->foreignId('role_id')->constrained('role')->onDelete('cascade');
        $table->foreignId('kolat_id')->constrained('kolat')->onDelete('cascade');

        // --- DATA IDENTITAS ---
        $table->string('no_induk')->unique();
        $table->string('nama_lengkap');
        $table->enum('jenis_kelamin', ['L', 'P']);
        $table->string('tempat_lahir');
        $table->date('tgl_lahir');

        // --- DATA KONTAK & ALAMAT ---
        $table->string('no_hp', 15)->nullable();
        $table->text('alamat');

        // --- DATA ORGANISASI ---
        $table->string('tingkatan'); // Dasar I, Balik II, dll
        $table->date('tgl_gabung');
        $table->date('tgl_keluar')->nullable(); // Diisi jika sudah tidak aktif
        $table->enum('status', ['Aktif', 'Non-Aktif', 'Alumni'])->default('Aktif');
        $table->string('jabatan')->default('Anggota'); // Jabatan struktural di kolat/cabang

        // --- DATA KHUSUS KEPELATIHAN (Nullable) ---
        // Kolom ini hanya diisi jika role_id merujuk ke 'Pelatih'
        $table->string('no_sk')->nullable();
        $table->date('tgl_sk')->nullable();
        $table->date('masa_berlaku')->nullable();
        $table->string('foto_sk')->nullable();

        // --- LAINNYA ---
        $table->text('catatan_medis')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota');
    }
};
