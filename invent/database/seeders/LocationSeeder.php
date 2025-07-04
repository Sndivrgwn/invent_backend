<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('locations')->insert([
            [
                'name' => 'Lab TKJ',
                'description' => 'Lemari Ruang Kang Her'
            ],
            [
                'name' => 'Lab TKJ',
                'description' => 'rak switch',
            ],
            [
                'name' => 'Ruang Guru TKJ',
                'description' => 'gudang ruang komputer',
            ],
            [
                'name' => 'Ruang Guru TKJ',
                'description' => 'gudang ruang server',
            ],
        ]);
    }
}
