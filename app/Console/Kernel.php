<?php

namespace App\Console;

use App\Jobs\AnularProformaJob;
use App\Jobs\Bodega\NotificarPedidoParcialJob;
use App\Jobs\ClearCacheJob;
use App\Jobs\ComprasProveedores\CrearDetalleDepartamentoProveedorJob;
use App\Jobs\FinalizarTareasReactivadasJob;
use App\Jobs\NotificarPermisoJob;
use App\Jobs\NotificarVacacionesJob;
use App\Jobs\PausarTicketsFinJornadaJob;
use App\Jobs\RechazarGastoJob;
use App\Jobs\RecursosHumanos\CrearVacacionesEmpleadoJob;
use App\Jobs\RecursosHumanos\DesactivarEmpleadoDelegadoJob;
use App\Jobs\RecursosHumanos\NotificarExpiracionPeriodoPruebaJob;
use App\Jobs\RecursosHumanos\NotificarPotencialesVacacionesEmpleadoJob;
use App\Jobs\TrabajoSocial\NotificarActualizacionFichaSocioeconomicaJob;
use App\Jobs\Vehiculos\ActualizarEstadoSegurosVehiculares;
use App\Jobs\Vehiculos\ActualizarMantenimientoVehiculoJob;
use App\Jobs\Vehiculos\CrearMatriculasAnualesVehiculosJob;
use App\Jobs\Vehiculos\NotificarMatriculacionVehicularJob;
use App\Jobs\Vehiculos\NotificarReporteBitacorasDiariasJob;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('clean:temp-images')->daily();
        $schedule->command('clean:old-files')->daily(); // para borrar diariamente las imagenes que ya cumplan el periodo valido

        $schedule->command('hikvision:fetch-records')->daily()->everyThirtyMinutes();

        // $schedule->command('inspire')->hourly();
        // $schedule->job(new MyJobExample)->everyMinute(); // Execute job every 5 minutes
        $schedule->job(new ClearCacheJob)->daily(); // Execute job every day at 08:00
        $schedule->job(new AnularProformaJob)->dailyAt('08:00'); // Execute job every day at 08:00
        $schedule->job(new RechazarGastoJob)->monthlyOn(1, '12:00');
        $schedule->job(new NotificarVacacionesJob)->dailyAt('09:00');
        $schedule->job(new NotificarPermisoJob)->dailyAt('09:00');
        $schedule->job(new NotificarPedidoParcialJob)->dailyAt('08:00');

        // $schedule->job(new NotificarPedidoParcialJob)->everyMinute();

        // $schedule->job(new MyJobExample)->dailyAt('08:00'); // Execute job every

        // $colocar el job que envia el comprobante a recursos humanos y sso cuando ya finalice
        /************************
         * COMPRAS Y PROVEEDORES
         ***********************/
//        $schedule->job(new CrearDetalleDepartamentoProveedorJob())->everyMinute();
        $schedule->job(new CrearDetalleDepartamentoProveedorJob())->daily();

        /*****************
         * VEHICULOS
         ****************/
        $schedule->job(new CrearMatriculasAnualesVehiculosJob())->yearlyOn(1, 5);
        $schedule->job(new NotificarMatriculacionVehicularJob())->weekdays()->at('08:00'); // Execute job every weekday(monday-friday) at 08:00
        $schedule->job(new ActualizarEstadoSegurosVehiculares())->daily();
        $schedule->job(new ActualizarMantenimientoVehiculoJob())->dailyAt('07:00');
        $schedule->job(new NotificarReporteBitacorasDiariasJob())->dailyAt('06:00');
        // $schedule->job(new ActualizarMantenimientoVehiculoJob())->everyMinute();

        /*****************
         * RECURSOS HUMANOS
         ****************/
        $schedule->job(new CrearVacacionesEmpleadoJob())->daily();
        $schedule->job(new NotificarPotencialesVacacionesEmpleadoJob())->dailyAt('08:00');
        $schedule->job(new DesactivarEmpleadoDelegadoJob())->everyMinute();
        $schedule->job(new NotificarExpiracionPeriodoPruebaJob())->dailyAt('08:00');
//        $schedule->job(new NotificarExpiracionPeriodoPruebaJob())->everyMinute();

        /*****************
         * TRABAJO SOCIAL
         ****************/
        $schedule->job(new NotificarActualizacionFichaSocioeconomicaJob())->daily();


        /*********
         * TAREAS
         *********/
        $schedule->job(new FinalizarTareasReactivadasJob())->hourly();

        /***********
         * Tickets
         ***********/
        // Programación para días de semana lunes a viernes
        $schedule->job(new PausarTicketsFinJornadaJob())->weekdays()->at('17:05');
        $schedule->job(new PausarTicketsFinJornadaJob())
            ->timezone('America/Guayaquil')
            ->days([Schedule::MONDAY, Schedule::TUESDAY, Schedule::WEDNESDAY, Schedule::THURSDAY, Schedule::FRIDAY])
            ->between('17:00', '8:00')
            ->everyFourHours();

        // Programación para fines de semana
        $schedule->job(new PausarTicketsFinJornadaJob())
            ->everyFourHours()
            ->when(function () {
                return in_array(Carbon::now()->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY]);
            });

        $schedule->command('tickets:generate-recurring')->dailyAt('08:00');

        // Revisar tickets de usuarios inactivos
        $schedule->command('tickets:revisar-inactivos')->dailyAt('00:00');



        // En producción podrías usar ->dailyAt('08:00');
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
