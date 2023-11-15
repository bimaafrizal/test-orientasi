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
        \Illuminate\Console\KeyGenerateCommand::class,
        // \App\Console\Commands\InputAdminCommand::class,
        // \App\Console\Commands\ReciverRabbitMQCommand::class,
        // \App\Console\Commands\PublisherInputRabbitMQCommand::class,
        // \App\Console\Commands\ReceiverInputRabbitMQCommand::class,
        \App\Console\Commands\ReceiverOtherServiceCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command("input-admin:corn")->everyMinute()->appendOutputTo(storage_path('logs/cron.log'));
        $schedule->command('rabbitmq:publish')->everyMinute();
    }
}
