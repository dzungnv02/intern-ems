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
        Commands\getTeachers::class,
        Commands\syncBranch::class,
        Commands\syncStudent::class,
        Commands\syncOwner::class,
        Commands\syncParent::class,
        Commands\syncClasses::class,
        Commands\syncStudentClasses::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /* 
        $schedule->command('zoho:teacher --getlist')->hourly();
        $schedule->command('zoho:student --getlist')->everyFifteenMinutes();
        $schedule->command('zoho:parent --getlist')->hourly();
        $schedule->command('zoho:student --map_parent')->hourly();
        $schedule->command('zoho:classes --getlist')->dailyAt('23:00');
        $schedule->command('zoho:classes --map_student')->dailyAt('23:15');
         */
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
