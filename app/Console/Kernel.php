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
        Commands\Commodity::class,
        Commands\Division::class,
        Commands\Overtime::class,
        Commands\Retrieve::class,
        Commands\TeamProfit::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('commodity:deal')->dailyAt('00:00'); //每天0点执行一次
        $schedule->command('division:deal')->dailyAt('02:00'); //每天2点执行一次
        $schedule->command('over:deal')->everyMinute(); //每分钟执行一次
        $schedule->command('retrieve:deal')->everyTenMinutes(); //每10分钟执行一次
        $schedule->command('team:profit')->dailyAt('04:00'); //每天4点执行一次
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        //
    }
}
