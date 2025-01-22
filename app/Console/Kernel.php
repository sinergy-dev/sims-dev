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


    protected $routeMiddleware = [
        // Other middlewares
        'noindex' => \App\Http\Middleware\NoIndexMiddleware::class,
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

        $schedule->command('ReviewDateRiskPM:daily')->daily()->at('07:00');
        $schedule->command('SalesUpdateStatusHold:daily')->daily()->at('02:00');

        $schedule->command('ReminderCreateProjectCharter:daily')->daily()->at('07:30');
        $schedule->command('ReminderMaintenanceEndAsset:daily')->daily()->at('07:40');

        $schedule->command('AssetManagementScheduling:daily')->daily()->at('00:40');


        // $schedule->command('UpdateStatusTimesheet:daily')->daily()->at('23:59:59');

        $schedule->command('ReminderUpdateStatusTimesheet:daily')->daily()->at('07:00');


        // $schedule->command('ReminderUpdateStatusTimesheet:daily')->monthly()->at('01:00');

        $schedule->command('AddWorkdays:monthly')->when(function () {
            return \Carbon\Carbon::now()->startOfMonth()->isToday();
        });

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
        

        // $schedule->call(function() {
        //     if (date("n") == 1 && date("j") == 1) {
        //         Artisan::call('ResetAwalTahun:resetawaltahun');
        //     }
        // })->monthly()
        // ->at('01:00');

        $schedule->command('ResetAwalTahun:resetawaltahun')->yearly()->at('00:15');

        $schedule->command('UpdateYearByClosingDate:UpdateYearByClosingDate')->yearly()->at('00:45');

        // $schedule->command('CutiRestart:cutirestart')->monthlyOn(4, '01:00');


        // $schedule->call(function() {
        //     if (date("n") == 1 && date("j") == 1) {
        //         Artisan::call('UpdateYearByClosingDate:UpdateYearByClosingDate');
        //     }
        // })->monthly()
        // ->at('01:00');

        // $schedule->call(function() {
        //     if (date("n") == 12 && date("j") == 30) {
        //         Artisan::call('HandoverCuti:handovercuti');
        //     }
        // })->monthly()
        // ->at('01:00');

        // $schedule->call(function() {
        //     Artisan::call('UpdateStatusKaryawan:updatestatuskaryawan');
        // })->daily();

        $schedule->command('UpdateStatusKaryawan:updatestatuskaryawan')->dailyAt('02:30');

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
