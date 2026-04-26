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
        Schema::table('jadwals', function (Blueprint $table) {
            // Menambah kolom untuk simpan foto kegiatan (pengganti WA)
            $table->string('foto_bukti')->nullable()->after('lokasi');
            // Menambah kolom untuk materi/catatan latihan
            $table->text('catatan_latihan')->nullable()->after('foto_bukti');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            $table->dropColumn(['foto_bukti', 'catatan_latihan']);
        });
    }
};
