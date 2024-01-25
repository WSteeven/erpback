<?php

namespace App\Http\Resources\FondosRotativos\Saldo;

use App\Models\FondosRotativos\Saldo\SaldoGrupo;
use App\Models\FondosRotativos\Saldo\ValorAcreditar;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ValorAcreditarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $controller_method = $request->route()->getActionMethod();
        $numeroSemana = explode("FONDO ROTATIVO SEMANA #", $this->acreditacion_semanal->semana)[1];

        $modelo = [
            'id'=>$this->id,
            'empleado_info'=>$this->empleado->nombres!=null ?$this->empleado->apellidos.' '.$this->empleado->nombres:'',
            'empleado'=>$this->empleado_id,
            'umbral_empleado'=>$this->empleado->umbral!=null ?number_format($this->empleado->umbral->valor_minimo,2):0,
            'saldo_empleado'=>$this->obtener_saldo($this->empleado_id,$numeroSemana),
            'monto_generado'=>number_format($this->monto_generado, 2),
            'monto_modificado'=> str_replace(",", "", number_format($this->monto_modificado, 2)),
            'acreditacion_semana'=>$this->acreditacion_semana_id,
            'es_acreditado'=>$this->acreditacion_semanal!=null?$this->acreditacion_semanal->acreditar:'',
            'acreditacion_semana_info'=>$this->acreditacion_semanal!=null?$this->acreditacion_semanal->semana:'',
            'motivo'=>$this->motivo,
            'estado'=>$this->estado
        ];
        return $modelo;
    }
    public function obtener_saldo($empleado_id,$numero_semana){
        $rango_fecha = $this->obtener_rango_semana($numero_semana);
        $saldo_actual = SaldoGrupo::where('id_usuario', $empleado_id)->where('fecha', '<=', $rango_fecha['startOfWeek'])->orderBy('id', 'desc')->first();
        $saldo_actual = $saldo_actual != null ? $saldo_actual->saldo_actual : 0;
        return $saldo_actual;
    }
    public  function obtener_rango_semana($weekNumber)
    {
        $startOfWeek = Carbon::now()->startOfWeek($weekNumber)->format('Y-m-d');;
        $endOfWeek = Carbon::now()->endOfWeek($weekNumber)->format('Y-m-d');
        return compact('startOfWeek', 'endOfWeek');
    }
}
