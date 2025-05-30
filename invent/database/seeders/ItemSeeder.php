<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('items')->insert([
            [
                'name' => 'Mikrotk',
                'code' => 'RBG-002',
                'status' => 'tersedia',
                'quantity' => 10,
                'condition' => 'baik',
                'description' => 'RB 50',
                'category_id' => 1,
                'location_id' => 1,
            ],
            [
                'name' => 'Laptop Asus ROG',
                'code' => 'RBG-001',
                'status' => 'tersedia',
                'quantity' => 3,
                'condition' => 'baik',
                'description' => 'Laptop gaming untuk ruang komputer',
                'category_id' => 1,
                'location_id' => 1,
            ],
        ]);
    }
}
