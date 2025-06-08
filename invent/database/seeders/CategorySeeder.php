<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'name' => 'Barang',
                'description' => 'Barang Barang'
            ],
            [
                'name' => 'item',
                'description' => 'item item'
            ],
            [
                'name' => 'games',
                'description' => 'games games'
            ],
        ]);
    }
}
