<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class LoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('loans')->insert([
            [
                'id' => 1,
                'code_loans' => 'LN-' . Str::upper(Str::random(6)),
                'loan_date' => Carbon::now()->subDays(10),
                'return_date' => Carbon::now()->subDays(3),
                'status' => 'returned',
                'user_id' => 1,
                'item_id' => 1,
                'loaner_name' => 'Sandi Virgiawan',
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            [
                'id' => 2,
                'code_loans' => 'LN-' . Str::upper(Str::random(6)),
                'loan_date' => Carbon::now()->subDays(5),
                'return_date' => Carbon::now()->addDays(2),
                'status' => 'borrowed',
                'user_id' => 1,
                'item_id' => 2,
                'loaner_name' => 'Sandi Virgiawan',
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'id' => 3,
                'code_loans' => 'LN-' . Str::upper(Str::random(6)),
                'loan_date' => Carbon::now()->subDays(7),
                'return_date' => Carbon::now()->addDays(1),
                'status' => 'borrowed',
                'user_id' => 1,
                'item_id' => 3,
                'loaner_name' => 'Sandi Virgiawan',
                'created_at' => Carbon::now()->subDays(7),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'id' => 4,
                'code_loans' => 'LN-' . Str::upper(Str::random(6)),
                'loan_date' => Carbon::now()->subDays(3),
                'return_date' => Carbon::now(),
                'status' => 'borrowed',
                'user_id' => 1,
                'item_id' => 2,
                'loaner_name' => 'Sandi Virgiawan',
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 5,
                'code_loans' => 'LN-' . Str::upper(Str::random(6)),
                'loan_date' => Carbon::now()->subDays(15),
                'return_date' => Carbon::now()->subDays(5),
                'status' => 'returned',
                'user_id' => 1,
                'item_id' => 1,
                'loaner_name' => 'Sandi Virgiawan',
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => Carbon::now()->subDays(5),
            ],
        ]);
    }
}
