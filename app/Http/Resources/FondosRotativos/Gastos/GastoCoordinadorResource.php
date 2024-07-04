<?php

namespace App\Http\Resources\FondosRotativos\Gastos;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class GastoCoordinadorResource extends JsonResource
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
            'id' => $this->id,
            'fecha_gasto' => date_format($this->created_at, 'Y-m-d h:i:s a'),
            'lugar' => $this->id_lugar,
            'grupo' => $this->id_grupo,
            'grupo_info' => $this->grupo->nombre,
            'motivo_info' => $this->detalleMotivoGasto != null ? $this->detalleMotivoGasto($this->detalleMotivoGasto) : '',
            'motivo' => $this->detalleMotivoGasto != null ? $this->detalleMotivoGasto->pluck('id') : null,
            'lugar_info' => $this->canton->canton,
            'monto' => $this->monto,
            'observacion' => $this->observacion,
            'usuario' => $this->id_usuario,
            'empleado_info' => $this->empleado->nombres . ' ' . $this->empleado->apellidos,
        ];
        return $modelo;
    }
    /**
     * La funcion "detalleMotivoGasto" permite listar collecction de  motivos de los gastos solicitados
     * por el cordinador para su respectiva acreditacion de saldos.
     * @param motivo_info: motivos de gasto solicitados
     * @return 'devuelve listado de motivos de gastos en un string  separado por comas'
     */
    private function detalleMotivoGasto(Collection $motivo_info)
    {
        $descripcion = '';
        $i = 0;
        foreach ($motivo_info as $motivo) {
            $descripcion .= $motivo->nombre;
            $i++;
            if ($i < count($motivo_info)) {
                $descripcion .= ', ';
            }
        }
        return $descripcion;
    }



    /**
     * La función "cambiarFecha" toma una entrada de fecha, la analiza usando Carbon y la devuelve en el
     * formato 'd-m-Y'.
     *
     * @param fecha La función `cambiarFecha` que proporcionó toma una cadena de fecha como entrada, la
     * analiza usando Carbon y luego la formatea al formato 'd-m-Y' antes de devolver la fecha formateada.
     *
     * @return La función `cambiarFecha` devuelve la fecha formateada en el formato 'd-m-Y'.
     */
    private function cambiarFecha($fecha)
    {
        $fecha_formateada = Carbon::parse($fecha)->format('d-m-Y');
        return $fecha_formateada;
    }
}
