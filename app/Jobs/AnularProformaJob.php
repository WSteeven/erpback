<?php

namespace App\Jobs;

use App\Events\ComprasProveedores\NotificarProformaEvent;
use App\Models\ComprasProveedores\Proforma;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class AnularProformaJob implements ShouldQueue
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
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $proformas = Proforma::where('estado_id', 1)->get();
        foreach ($proformas as $proforma){
            $fecha_caducidad = Utils::obtenerDiasRestantes($proforma->created_at, Utils::obtenerNumeroEnCadena($proforma->tiempo));
            $fecha_anulacion = Utils::obtenerDiasRestantes($proforma->created_at, Utils::obtenerNumeroEnCadena($proforma->tiempo)+3);
            // Log::channel('testing')->info('Log', ['Proforma en el  job', $proforma->created_at, Utils::obtenerNumeroEnCadena($proforma->tiempo)]);
            // Log::channel('testing')->info('Log', ['Días restantes para caducidad: ', $fecha_caducidad]);
            // Log::channel('testing')->info('Log', ['Días restantes para anulación', $fecha_anulacion]);
            if($fecha_caducidad<1 && $fecha_anulacion>0){
                event(new NotificarProformaEvent($proforma));
            }
            if($fecha_anulacion==0){
                // Log::channel('testing')->info('Log', ['Se procede a anular la proforma', $proforma->estado_id]);
                $proforma->estado_id = 4;
                $proforma->causa_anulacion = 'ANULADA AUTOMATICAMENTE POR EL SISTEMA';
                $proforma->save();
            }
            
        }
    }
}
