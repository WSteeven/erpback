<?php

namespace App\Console;

use App\Jobs\AnularProformaJob;
use App\Jobs\MyJobExample;
use App\Jobs\RechazarGastoJob;
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
        // $schedule->job(new MyJobExample)->everyMinute(); // Execute job every 5 minutes
        $schedule->job(new AnularProformaJob)->dailyAt('08:00'); // Execute job every day at 08:00
        $schedule->job(new RechazarGastoJob)->monthly();
        // $schedule->job(new MyJobExample)->dailyAt('08:00'); // Execute job every

        // $colocar el job que envia el comprobante a recursos humanos y sso cuando ya finalice


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
