<?php

namespace App\Http\Resources\FondosRotativos\Gastos;

use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GastoCoordinadorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        $controller_method = $request->route()->getActionMethod();
        $modelo = [
            'id' => $this->id,
            'fecha_gasto' => $this->cambiarFecha($this->created_at),
            'lugar' => $this->id_lugar,
            'grupo' => $this->id_grupo,
            'grupo_info' => $this->grupo->nombre,
            'motivo_info' => $this->detalleMotivoGasto != null ? $this->detalleMotivoGasto($this->detalleMotivoGasto) : '',
            'motivo' => $this->detalleMotivoGasto != null ? $this->detalleMotivoGasto->pluck('id') : null,
            'lugar_info' => $this->canton->canton,
            'monto' => $this->monto,
            'observacion' => $this->observacion,
            'usuario' => $this->id_usuario,
            'id_usuario' => $this->id_usuario,
            'empleado_info' => $this->empleado->nombres . ' ' . $this->empleado->apellidos,
            'estado' => $this->estado->nombre,
            'revisado' => $this->revisado,
            'observacion_contabilidad' => $this->observacion_contabilidad
        ];
        if ($controller_method == 'show') {
            $modelo['estado'] = $this->estado_id;
        }
        return $modelo;
    }

    /**
     * La funcion "detalleMotivoGasto" permite listar collecction de  motivos de los gastos solicitados
     * por el cordinador para su respectiva acreditacion de saldos.
     * @param Collection $motivo_info : motivos de gasto solicitados
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
     * @param DateTime|string $fecha La función `cambiarFecha` que proporcionó toma una cadena de fecha como entrada, la
     * analiza usando Carbon y luego la formatea al formato 'd-m-Y' antes de devolver la fecha formateada.
     *
     * @return string función `cambiarFecha` devuelve la fecha formateada en el formato 'd-m-Y'.
     */
    private function cambiarFecha(DateTime|string $fecha)
    {
        return Carbon::parse($fecha)->format('Y-m-d H:i:s');
    }
}
