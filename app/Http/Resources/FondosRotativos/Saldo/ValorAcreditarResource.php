<?php

namespace App\Http\Resources\FondosRotativos\Saldo;

use App\Models\FondosRotativos\Saldo\SaldoGrupo;
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
        $modelo = [
            'id'=>$this->id,
            'empleado_info'=>$this->empleado->nombres!=null ?$this->empleado->nombres.' '.$this->empleado->apellidos:'',
            'empleado'=>$this->empleado_id,
            'umbral_empleado'=>number_format($this->empleado->umbral->valor_minimo,2),
            'saldo_empleado'=>$this->obtener_saldo($this->empleado_id),
            'monto_generado'=>number_format($this->monto_generado, 2),
            'monto_modificado'=>number_format($this->monto_modificado, 2),
            'acreditacion_semana'=>$this->acreditacion_semana_id,
            'es_acreditado'=>$this->acreditacion_semanal!=null?$this->acreditacion_semanal->acreditar:'',
            'acreditacion_semana_info'=>$this->acreditacion_semanal!=null?$this->acreditacion_semanal->semana:'',
        ];
        return $modelo;
    }
    public function obtener_saldo($empleado_id){
        $saldo_actual = SaldoGrupo::where('id_usuario', $empleado_id)->orderBy('id', 'desc')->first();
        $saldo_actual = $saldo_actual != null ? $saldo_actual->saldo_actual : 0;
        return $saldo_actual;
    }
}
