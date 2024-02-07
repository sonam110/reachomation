<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

         $schedule->command('send:mail')
         ->everyMinute()
         //->everyFiveMinutes()
         ->timezone(env('TIME_ZONE', 'Asia/Calcutta'));

         $schedule->command('check:mail-bounce')
         ->everyMinute()
         //->everyFiveMinutes()
         ->timezone(env('TIME_ZONE', 'Asia/Calcutta'));

         $schedule->command('get:reply')
         ->everyMinute()
         //->everyFiveMinutes()
         ->timezone(env('TIME_ZONE', 'Asia/Calcutta'));

         $schedule->command('update:daily-limit')
                ->dailyAt('00:05')
                ->timezone(env('TIME_ZONE', 'Asia/Calcutta'));

        $schedule->command('plan:deactivate')
                ->dailyAt('00:05')
                ->timezone(env('TIME_ZONE', 'Asia/Calcutta'));

         $schedule->command('campiagn:process')
         //->everyMinute()
         ->everyFiveMinutes()
         ->timezone(env('TIME_ZONE', 'Asia/Calcutta'));

         $schedule->call('App\Http\Controllers\MailController@mailGetList')->hourly()->appendOutputTo(storage_path().'/logs/laravel_output.log');
        $schedule->call('App\Http\Controllers\OutlookMailController@mailGetList')->hourly()->appendOutputTo(storage_path().'/logs/laravel_output_outlook.log');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {

        Commands\sendMail::class;
        Commands\GetCampignMailReply::class;
        Commands\UpdateUserDailyLimit::class;
        Commands\AddToolsCsvDataToDb::class;
        Commands\getSendMailBounceStatus::class;

        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
