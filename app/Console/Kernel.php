<?php

namespace App\Console;

use App\Jobs\AnularProformaJob;
use App\Jobs\Bodega\NotificarPedidoParcial;
use App\Jobs\Bodega\NotificarPedidoParcialJob;
use App\Jobs\MyJobExample;
use App\Jobs\NotificarPermisoJob;
use App\Jobs\NotificarVacacionesJob;
use App\Jobs\PausarTicketsFinJornadaJob;
use App\Jobs\RechazarGastoJob;
use Carbon\Carbon;
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
        $schedule->job(new NotificarVacacionesJob)->dailyAt('09:00');
        $schedule->job(new NotificarPermisoJob)->dailyAt('09:00');
        $schedule->job(new NotificarPedidoParcialJob)->dailyAt('08:00');
        // Programación para días de semana lunes a viernes
        // ->dailyAt('17:00')
        $schedule->job(new PausarTicketsFinJornadaJob)
            ->between('17:00', '08:00')
            ->everyFourHours()
            ->when(function () {
                return !in_array(Carbon::now()->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY]);
            });

        // Programación para fines de semana
        $schedule->job(new PausarTicketsFinJornadaJob)
            ->everyFourHours()
            ->when(function () {
                return in_array(Carbon::now()->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY]);
            });

        // $schedule->job(new NotificarPedidoParcialJob)->everyMinute();

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
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
