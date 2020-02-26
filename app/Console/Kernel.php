<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Artisan;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\ClosingDateEmail',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('closedate:mail')->dailyAt('02:10');

        $schedule->command('SalesRemainder:weekly')
            ->weekly()
            ->thursdays()
            ->at('07:00');

        $schedule->call(function() {
            if (date("m") == 3 && date("j") == 31) {
                Artisan::call('CutiRestart:cutirestart');
            }
        })
        ->monthly()
        ->at('01:00');

        $schedule->call(function() {
            if (date("m") == 1 && date("j") == 1) {
                Artisan::call('ResetAwalTahun:resetawaltahun');
            }
        })->monthly()
        ->at('01:00');

        $schedule->call(function() {
            if (date("m") == 12 && date("j") == 31) {
                Artisan::call('HandoverCuti:handovercuti');
            }
        })->monthly()
        ->at('01:00');

        $schedule->call(function() {
            Artisan::call('UpdateStatusKaryawan:updatestatuskaryawan');
        })->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
