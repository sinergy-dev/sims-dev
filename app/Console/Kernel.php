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

        $schedule->command('ReviewDateRiskPM:daily')->daily('07:10');

        $schedule->command('ReminderCreateProjectCharter:daily')->daily('07:30');

        $schedule->command('SalesRemainder:weekly')
            ->weekly()
            ->thursdays()
            ->at('07:00');

        // $schedule->call(function() {
        //     if (date("n") == 4 && date("j") == 1) {
        //         if (date("H:i") == "01:30") {
        //             Artisan::call('CutiRestart:cutirestart');
        //         }
        //     }
        // })
        // ->everyMinute();

        $schedule->call(function() {
            if (date("n") == 4 && date("j") == 1) {
                Artisan::call('CutiRestart:cutirestart');
            }
        })->monthly()
        ->at('01:00');
        

        $schedule->call(function() {
            if (date("n") == 1 && date("j") == 1) {
                Artisan::call('ResetAwalTahun:resetawaltahun');
            }
        })->monthly()
        ->at('01:00');

        $schedule->call(function() {
            if (date("n") == 12 && date("j") == 30) {
                Artisan::call('HandoverCuti:handovercuti');
            }
        })->monthly()
        ->at('01:00');

        $schedule->call(function() {
            Artisan::call('UpdateStatusKaryawan:updatestatuskaryawan');
        })->daily();

        $schedule->call(function() {
            Artisan::call('RejectCuti:rejectCuti');
        })->daily();

        $schedule->call(function() {
            Artisan::call('FollowUpCuti:followupcuti');
        })->daily();

        $schedule->command('ExpirePeminjamanAssetTech:expirepeminjamanassettech')->dailyAt('00:30');

        $schedule->command('ReminderPengembalianAssetTech:reminderpengembalianassettech')->dailyAt('08:00');

        $schedule->command('pending:remind')->everyMinute();
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
