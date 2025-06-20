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
        'password' => Hash::make('12345678'),
    ],
    [
        'name' => 'user',
        'email' => 'user@gmail.com',
        'roles_id' => 2,
        'password' => Hash::make('12345678'),
    ]
]);

}}
