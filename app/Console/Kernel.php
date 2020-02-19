<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

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

        $schedule->command('CustomRemainder:all_remainder')
            ->weekly()
            ->thursdays()
            ->at('07:00');

        $schedule->call(function() {
            if (date("m") == 3 && date("j") == 31) {
                Artisan::call('CutiRestart:cutirestart');
            }
            // syslog(1, 'helooo');
        })
        ->monthly()
        ->at('01:00');

        $schedule->call(function() {
            if (date("m") == 1 && date("j") == 1) {
                Artisan::call('ResetAwalTahun:resetawaltahun');
            }
            // syslog(1, 'helooo');
        })->monthly()
        ->at('01:00');

        $schedule->call(function() {
            if (date("m") == 12 && date("j") == 31) {
                Artisan::call('HandoverCuti:handovercuti');
            }
            // syslog(1, 'helooo');
        })->monthly()
        ->at('01:00');

        $schedule->call(function() {
            Artisan::call('UpdateStatusKaryawan:updatestatuskaryawan');
        })->dailyAt('17:35');
        // $schedule->call(function() {
        //     syslog(1, 'helooo');
        // })->everyMinute();
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
