<?php

namespace App\Jobs\ComprasProveedores;

use App\Events\ComprasProveedores\NotificarRecalificacionDepartamentoEvent;
use App\Models\ComprasProveedores\DetalleDepartamentoProveedor;
use App\Models\Proveedor;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class CrearDetalleDepartamentoProveedorJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Este job crea registros para recalificacion solo de los proveedores calificados
     * que necesitan recalificacion por haber transcurrido un año.
     *
     * @return void
     * @throws Throwable
     */
    public function handle()
    {
        try {
            $proveedores_calificados = Proveedor::where('estado_calificado', Proveedor::CALIFICADO)->where('estado', true)->get();
            foreach ($proveedores_calificados as $proveedor) {
                $registro_calificacion_no_calificado = $proveedor->calificacionesDepartamentos()->whereNull('fecha_calificacion')->count();
                if ($registro_calificacion_no_calificado > 0) {
                    continue;
                } else {
                    $registro_calificacion = $proveedor->calificacionesDepartamentos()->whereNotNull('fecha_calificacion')->orderBy('fecha_calificacion', 'desc')->first();
                    if ($registro_calificacion->fecha_calificacion <= Carbon::now()->subYear()) {
                        foreach ($proveedor->calificacionesDepartamentos()->groupBy('departamento_id')->get() as $detalle_departamento_proveedor) {
//                        Log::channel('testing')->info('Log', ['detalle_departamento_proveedr',$detalle_departamento_proveedor]);
                            $nuevo_detalle_departamento_proveedor = DetalleDepartamentoProveedor::create([
                                'departamento_id' => $detalle_departamento_proveedor->departamento_id,
                                'proveedor_id' => $proveedor->id]);
                            event(new NotificarRecalificacionDepartamentoEvent($nuevo_detalle_departamento_proveedor));
                        }
                    }
                }

            }
        }catch (Exception $e){
            Log::channel('testing')->error('Log', ['error en el job de crear detalle departamento',$e->getLine(), $e->getCode(), $e->getMessage()]);
        }
    }
}
