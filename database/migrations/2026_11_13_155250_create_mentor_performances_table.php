<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mentor_performances', function (Blueprint $table) {
            $table->id();
            $table->string('mentor_name');           // Nama ARK / mentor
            $table->string('category');              // tk_pu_aktif | bpu_aktif | iuran
            $table->integer('no')->default(0);       // Nomor urut (dari spreadsheet)
            $table->string('target')->default('0');  // Target (bisa angka atau format rupiah)
            $table->string('realisasi')->default('0'); // Realisasi
            $table->string('gap')->default('0');     // GAP (auto-hitung atau manual)
            $table->string('percent')->default('0%'); // % Capaian
            $table->string('period')->nullable();    // Periode laporan (bulan/tahun)
            $table->timestamps();

            $table->unique(['mentor_name', 'category', 'period']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mentor_performances');
    }
};
