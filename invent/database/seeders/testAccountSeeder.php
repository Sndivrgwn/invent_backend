<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;



class testAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         for ($i = 1; $i <= 5; $i++) {
        User::create([
            'name' => 'test-user' . $i,
            'email' => 'test-user' . $i . '@gmail.com',
            'roles_id' => 2,
            'password' => Hash::make('12345678'),
        ]);
    }

    // Generate 5 test admins (roles_id = 1)
    for ($i = 1; $i <= 5; $i++) {
        User::create([
            'name' => 'test-admin' . $i,
            'email' => 'test-admin' . $i . '@gmail.com',
            'roles_id' => 1,
            'password' => Hash::make('12345678'),
        ]);
    }
    }
}
