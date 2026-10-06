<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Convert all 'dosen' roles to 'mentor'.
     */
    public function up(): void
    {
        DB::table('users')->where('role', 'dosen')->update(['role' => 'mentor']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot reliably reverse - would need to know which users were originally 'dosen'
    }
};
