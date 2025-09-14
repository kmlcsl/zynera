<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Cleanup expired OTP every hour
        $schedule->command('cleanup:expired-otp')
            ->hourly()
            ->withoutOverlapping()
            ->runInBackground();

        $schedule->command('registrations:cleanup')->everyThirtyMinutes();

        // Example: Backup database daily at 2 AM
        // $schedule->command('backup:database')
        //          ->dailyAt('02:00')
        //          ->withoutOverlapping();

        // Example: Send reminders every Monday at 8 AM
        // $schedule->command('send:weekly-reminders')
        //          ->weeklyOn(1, '08:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
