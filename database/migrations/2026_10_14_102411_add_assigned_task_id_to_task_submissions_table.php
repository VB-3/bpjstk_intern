<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan assigned_task_id ke task_submissions agar satu user
     * hanya bisa submit satu kali per tugas aktif.
     */
    public function up(): void
    {
        Schema::table('task_submissions', function (Blueprint $table) {
            // Kolom nullable agar data lama tidak rusak
            $table->foreignId('assigned_task_id')
                  ->nullable()
                  ->after('user_id')
                  ->constrained('assigned_tasks')
                  ->onDelete('cascade');

            // Unique constraint: satu user hanya boleh submit satu kali per tugas
            $table->unique(['user_id', 'assigned_task_id'], 'unique_user_task_submission');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_submissions', function (Blueprint $table) {
            $table->dropUnique('unique_user_task_submission');
            $table->dropConstrainedForeignId('assigned_task_id');
        });
    }
};
