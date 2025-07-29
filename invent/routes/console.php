<?php

use App\Console\Commands\DeleteExpiredGuests;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

return function (Schedule $schedule) {
    // Penjadwalan commands
    $schedule->command(DeleteExpiredGuests::class)->daily();

    // Command motivasi default
    Artisan::command('inspire', function () {
        $this->comment(Inspiring::quote());
    })->purpose('Display an inspiring quote')->hourly();
};
