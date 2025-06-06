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
                'status' => 'READY',
                'quantity' => 10,
                'condition' => 'NOT GOOD',
                'description' => 'RB 50',
                'category_id' => 1,
                'location_id' => 1,
            ],
            [
                'name' => 'Laptop Asus ROG',
                'code' => 'RBG-001',
                'status' => 'NOT READY',
                'quantity' => 3,
                'condition' => 'GOOD',
                'description' => 'Laptop gaming untuk ruang komputer',
                'category_id' => 1,
                'location_id' => 1,
            ],
        ]);
    }
}
