<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::insert([
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'roles_id' => 1,
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'super admin',
                'email' => 'inventaris.tkj@gmail.com',
                'roles_id' => 3,
                'password' => Hash::make('@SuperAdminTkjInvent123'),
            ]
        ]);

        $kelas = ['TKJ 1', 'TKJ 2', 'TKJ 3'];

        foreach ($kelas as $index => $nama) {
            $nomor = $index + 1;
            $email = 'Xtejekate' . $nomor . '.tkj@gmail.com';
            $passwordPlain = 'Xtjkt' . $nomor . '#*' ;

            User::create([
                'name' => $nama,
                'email' => $email,
                'roles_id' => 4,
                'password' => Hash::make($passwordPlain),
            ]);
        }

    }
}