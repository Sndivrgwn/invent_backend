<?php

namespace Database\Seeders;

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
                'name' => 'Switch Cisco',
                'code' => 'NET-001',
                'brand' => 'Cisco',
                'type' => 'Switch 24 Port',
                'status' => 'READY',
                'condition' => 'GOOD',
                'description' => 'Switch manageable 24 port',
                'category_id' => 3,
                'location_id' => 1,
            ],
            [
                'name' => 'Monitor Dell 24"',
                'code' => 'SCR-024',
                'brand' => 'Dell',
                'type' => '24 Inch',
                'status' => 'NOT READY',
                'condition' => 'NOT GOOD',
                'description' => 'Monitor rusak sebagian',
                'category_id' => 1,
                'location_id' => 2,
            ],
            [
                'name' => 'Monitor Dell 24"',
                'code' => 'SCR-0224',
                'brand' => 'Dell',
                'type' => '24 Inch',
                'status' => 'READY',
                'condition' => 'GOOD',
                'description' => 'Monitor cadangan ruang meeting',
                'category_id' => 2,
                'location_id' => 2,
            ],
            [
                'name' => 'Printer Epson L3110',
                'code' => 'PRN-110',
                'brand' => 'Epson',
                'type' => 'L3110',
                'status' => 'READY',
                'condition' => 'GOOD',
                'description' => 'Digunakan untuk admin kantor',
                'category_id' => 1,
                'location_id' => 3,
            ],
            [
                'name' => 'Printer Epson L3110',
                'code' => 'PRN-1210',
                'brand' => 'Epson',
                'type' => 'L3110',
                'status' => 'NOT READY',
                'condition' => 'NOT GOOD',
                'description' => 'Tidak bisa menarik kertas',
                'category_id' => 3,
                'location_id' => 3,
            ],
            [
                'name' => 'Projector BenQ',
                'code' => 'PRJ-001',
                'brand' => 'BenQ',
                'type' => 'Standard',
                'status' => 'READY',
                'condition' => 'GOOD',
                'description' => 'Digunakan untuk presentasi',
                'category_id' => 3,
                'location_id' => 4,
            ],
            [
                'name' => 'Projector BenQ',
                'code' => 'PRJ-0011',
                'brand' => 'BenQ',
                'type' => 'Standard',
                'status' => 'READY',
                'condition' => 'GOOD',
                'description' => 'Digunakan untuk presentasi',
                'category_id' => 1,
                'location_id' => 4,
            ],
            [
                'name' => 'Laptop Dell XPS 13',
                'code' => 'LAP-0101',
                'brand' => 'Dell',
                'type' => 'XPS 13',
                'status' => 'READY',
                'condition' => 'GOOD',
                'description' => 'Laptop untuk dosen',
                'category_id' => 3,
                'location_id' => 1,
            ],
            [
                'name' => 'Laptop Lenovo ThinkPad',
                'code' => 'LAP-002',
                'brand' => 'Lenovo',
                'type' => 'ThinkPad',
                'status' => 'NOT READY',
                'condition' => 'NOT GOOD',
                'description' => 'Laptop rusak, butuh perbaikan',
                'category_id' => 2,
                'location_id' => 2,
            ],
        ]);
    }
}
