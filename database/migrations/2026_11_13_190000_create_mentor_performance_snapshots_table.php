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
        Schema::create('mentor_performance_snapshots', function (Blueprint $table) {
            $table->id();
            $table->date('snapshot_date')->unique(); // Tanggal snapshot (1 snapshot per 24 jam)
            $table->string('header_date')->nullable(); // Tanggal teks dari header spreadsheet
            $table->json('categories'); // Data lengkap rekapitulasi kategori, mentor, dan total
            $table->string('checksum', 64)->nullable(); // Hash MD5 untuk deteksi perubahan
            $table->boolean('has_changed')->default(false); // Flag apakah ada perubahan dari hari sebelumnya
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentor_performance_snapshots');
    }
};
