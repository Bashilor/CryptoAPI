<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\LastBlockUpdate',
        'App\Console\Commands\LastPrice'
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('cryptos:lastblockupdate')->everyMinute();
        $schedule->command('cryptos:lastprice')->everyMinute();

        $schedule->command('api:paymentstatus')->everyMinute();
    }
}
