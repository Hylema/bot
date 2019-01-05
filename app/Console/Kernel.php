<?php

namespace App\Console;

use App\Console\Commands\BotProcessMessage;
use App\Console\Commands\BotSendNotifications;
use App\Console\Commands\BotSendResponse;
use App\Console\Commands\FetchTelegramUpdates;
use App\Console\Commands\FetchConsoleUpdates;
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
        FetchTelegramUpdates::class,
        BotProcessMessage::class,
        BotSendResponse::class,
        BotSendNotifications::class,
        FetchConsoleUpdates::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
