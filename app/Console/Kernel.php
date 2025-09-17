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
        // Simple cache cleanup task
        $schedule->command('cache:clear')
                 ->daily()
                 ->at('02:00');

        // Log cleanup task
        $schedule->command('queue:prune-failed')
                 ->daily()
                 ->at('03:00');

        // Test command to verify scheduler is working
        $schedule->call(function () {
            \Log::info('Scheduler is working: ' . now());
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
