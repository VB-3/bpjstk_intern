<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'coffebreak@superadmin.com',
            'password' => bcrypt('#Roamidaman'),
            'role' => 'super_admin',
        ]);

        User::factory()->create([
            'name' => 'Mentor Pembimbing',
            'email' => 'mentor@bpjstk.com',
            'password' => bcrypt('password'),
            'role' => 'mentor',
        ]);

        User::factory()->create([
            'name' => 'Peserta Magang',
            'email' => 'intern@bpjstk.com',
            'password' => bcrypt('password'),
            'role' => 'intern',
        ]);
    }
}
