<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class MentorSeeder extends Seeder
{
    /**
     * Seed mentor accounts.
     */
    public function run(): void
    {
        $accounts = [
            ['name' => 'ARI ISMOYO',                  'email' => 'ari.ismoyo@mentor.com',                'password' => 'Xk9#mPqL2w'],
            ['name' => 'EKA MEILIND',                  'email' => 'eka.meilind@mentor.com',               'password' => 'Rz4!vNbT8s'],
            ['name' => 'FARIZA SEPTIAN NASUTION',      'email' => 'fariza.septian.nasution@mentor.com',   'password' => 'Jw7@cHdF3y'],
            ['name' => 'FITRIANI HASIBUAN',            'email' => 'fitriani.hasibuan@mentor.com',         'password' => 'Qm5$gYkR6e'],
            ['name' => 'GHAFFAR HADI',                 'email' => 'ghaffar.hadi@mentor.com',              'password' => 'Bt2&nWxS9a'],
            ['name' => 'RISNIAR ARITONANG',            'email' => 'risniar.aritonang@mentor.com',         'password' => 'Lp8*fDjU1o'],
            ['name' => 'SAKINAH RAMZAH',               'email' => 'sakinah.ramzah@mentor.com',            'password' => 'Vc3^hKmE7i'],
            ['name' => 'SENDI YULIANA',                'email' => 'sendi.yuliana@mentor.com',             'password' => 'Hn6!tQzA4u'],
        ];

        foreach ($accounts as $account) {
            User::updateOrCreate(
                ['email' => $account['email']],
                [
                    'name' => $account['name'],
                    'password' => Hash::make($account['password']),
                    'role' => 'mentor',
                ]
            );
        }

        $this->command->info('8 mentor accounts created successfully!');
    }
}


