<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class DeleteExpiredGuests extends Command
{
    protected $signature = 'guests:cleanup';
    protected $description = 'Hapus akun guest yang lebih dari 1 hari';

    public function handle(): int
    {
        $deleted = User::where('is_guest', true)
            ->where('created_at', '<', now()->subHour())
            ->delete();


        $this->info("Total guest dihapus: $deleted");

        return self::SUCCESS;
    }
}
