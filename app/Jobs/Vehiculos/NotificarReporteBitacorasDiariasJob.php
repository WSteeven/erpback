<?php

namespace App\Jobs\Vehiculos;

use App\Mail\Vehiculos\EnviarReporteBitacorasDiariasMail;
use App\Models\Empleado;
use App\Models\User;
use App\Models\Vehiculos\BitacoraVehicular;
use App\Models\Vehiculos\Vehiculo;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Src\App\EmpleadoService;
use Throwable;

class NotificarReporteBitacorasDiariasJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Empleado $admin_vehiculos;

    /**
     * Create a new job instance.
     *
     * @return void
     * @throws Throwable
     */
    public function __construct()
    {
        $this->admin_vehiculos = EmpleadoService::obtenerEmpleadoRolEspecifico(User::ROL_ADMINISTRADOR_VEHICULOS);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $vehiculos_asignados = Vehiculo::where('custodio_id', '!=', null)->get();
            $vehiculos_con_bitacoras_realizadas = [];
            $vehiculos_no_realizadas = new Collection();
            $ayer = Carbon::yesterday();
            Log::channel('testing')->info('Log', ['vehiculos_asignados', $vehiculos_asignados, $ayer]);
            foreach ($vehiculos_asignados as $vehiculo) {
                $bitacora_realizada = BitacoraVehicular::where('vehiculo_id', $vehiculo->id)->whereBetween('fecha', [$ayer, Carbon::now()])->first();
                if ($bitacora_realizada) {
                    $row['vehiculo'] = $vehiculo;
                    $row['bitacora'] = $bitacora_realizada;
                    $vehiculos_con_bitacoras_realizadas[] = $row;
                } else {
                    $vehiculos_no_realizadas->push($vehiculo);
                }
//                Log::channel('testing')->info('Log', ['vehiculo', $vehiculo, $bitacora_realizada]);

            }
            Log::channel('testing')->info('Log', ['vehiculo que han realizado', $vehiculos_con_bitacoras_realizadas]);
            Log::channel('testing')->info('Log', ['ids vehiculo que no han realizado', $vehiculos_no_realizadas]);
//Mail::to($this->admin_vehiculos->user->email)->send(new EnviarReporteBitacorasDiariasMail($vehiculos_con_bitacoras_realizadas, $vehiculos_no_realizadas));
Mail::to('wcordova@jpconstrucred.com')->send(new EnviarReporteBitacorasDiariasMail($vehiculos_con_bitacoras_realizadas, $vehiculos_no_realizadas));
        } catch (Exception $e) {
            Log::channel('testing')->error('Log', ['[JOB ERROR][NotificarReporteBitacorasDiariasJob]', $e->getMessage(), $e->getLine()]);
        }
    }
}
