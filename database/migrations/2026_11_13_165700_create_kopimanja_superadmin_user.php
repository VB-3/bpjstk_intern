<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        User::updateOrCreate(
            ['email' => 'kopimanja@superadmin.com'],
            [
                'name'     => 'Super Admin Kopi Manja',
                'password' => Hash::make('salamjp'),
                'role'     => 'super_admin',
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        User::where('email', 'kopimanja@superadmin.com')->delete();
    }
};
