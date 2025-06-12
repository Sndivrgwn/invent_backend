<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ItemLoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('item_loan')->insert([
            [
                'loan_id' => 1,
                'item_id' => 1,
                'quantity' => 2,
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            [
                'loan_id' => 2,
                'item_id' => 2,
                'quantity' => 1,
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'loan_id' => 3,
                'item_id' => 3,
                'quantity' => 3,
                'created_at' => Carbon::now()->subDays(7),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'loan_id' => 4,
                'item_id' => 2,
                'quantity' => 1,
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now(),
            ],
            [
                'loan_id' => 5,
                'item_id' => 1,
                'quantity' => 4,
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => Carbon::now()->subDays(5),
            ],
        ]);
    }
}
