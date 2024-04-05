<?php

namespace App\Http\Resources\FondosRotativos\Saldo;

use App\Models\FondosRotativos\Gasto\Gasto;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class AcreditacionResource extends JsonResource
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
            'fecha' => $this->cambiarFecha($this->fecha),
            'tipo_saldo' => $this->id_tipo_saldo,
            'tipo_fondo' => $this->id_tipo_fondo,
            'id_saldo' => $this->id_saldo,
            'usuario' => $this->id_usuario,
            'empleado_info' => $this->usuario->nombres . ' ' . $this->usuario->apellidos,
            'estado' => $this->estado != null ? $this->estado->estado : ' ',
            'descripcion_acreditacion' => $this->descripcion_acreditacion,
            'motivo' => $this->motivo,
            'monto' => $this->monto,
        ];
        return $modelo;
    }
    /**
     * La función "cambiarFecha" toma una cadena de fecha como entrada, la analiza usando Carbon y
     * devuelve la fecha con el formato 'd-m-Y'.
     *
     * @param string fecha La función `cambiarFecha` toma un parámetro de cadena llamado ``, que
     * representa una fecha en un formato específico. La función utiliza la biblioteca Carbon para
     * analizar la cadena de fecha de entrada y luego formatearla en formato 'd-m-Y'. Finalmente, devuelve
     * la cadena de fecha formateada.
     *
     * @return La función `cambiarFecha` devuelve una cadena de fecha formateada en el formato 'd-m-Y'.
     */
    private function cambiarFecha(string $fecha)
    {
        $fecha_formateada = Carbon::parse($fecha)->format('d-m-Y');
        return $fecha_formateada;
    }
}
