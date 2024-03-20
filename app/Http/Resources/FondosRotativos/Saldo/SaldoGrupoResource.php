<?php

namespace App\Http\Resources\FondosRotativos\Saldo;

use App\Http\Resources\UserResource;
use App\Models\FondosRotativos\Gasto\Gasto;
use App\Models\FondosRotativos\Viatico\Viatico;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class SaldoGrupoResource extends JsonResource
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
            'fecha' => $this->fecha,
            'tipo_saldo' => $this->id_tipo_saldo,
            'usuario' => $this->id_usuario,
            'empleado_info' => $this->usuario,
            'descripcion_saldo' => $this->descripcionSaldo,
            'saldo_anterior' => $this->saldo_anterior,
            'saldo_depositado' => $this->saldo_depositado,
            'saldo_actual' => $this->saldo_actual,
            'gasto' => $this->getSaldoGrupo($this->fecha_inicio,$this->fecha_fin,$this->id_usuario),
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
        ];
        return $modelo;
    }
 /**
  * La función `getSaldoGrupo` calcula los gastos totales dentro de un rango de fechas específico para
  * un empleado específico.
  *
  * @param string fecha_inicio El parámetro `fecha_inicio` es una cadena que representa la fecha de
  * inicio en el formato 'AAAA-MM-DD'. Se utiliza para filtrar los gastos en función de la fecha de los
  * viajes.
  * @param string fecha_fin La función `getSaldoGrupo` que proporcionaste parece estar calculando los
  * gastos totales (``) para un empleado específico (``) dentro de un rango de fechas
  * determinado (`` a ``). La función utiliza el modelo `Gasto` para consultar
  * la base de datos.
  * @param empleado_id La función `getSaldoGrupo` que proporcionaste parece estar calculando los gastos
  * totales (``) para un empleado específico (``) dentro de un rango de fechas
  * determinado (`` a ``). La función filtra los gastos según el ID del empleado
  * y el rango de fechas.
  *
  * @return La función `getSaldoGrupo` está devolviendo la suma total de la columna 'total' de la tabla
  * 'Gasto' donde la fecha 'fecha_viat' cae entre los `` y `` proporcionados, la
  * columna 'estado' es igual a 1, y la columna 'id_usuario' es igual al `` proporcionado.
  */
    private function getSaldoGrupo(string $fecha_inicio,string $fecha_fin,$empleado_id){
        $gasto = Gasto::selectRaw("*, DATE_FORMAT(fecha_viat, '%d/%m/%Y') as fecha")
            ->whereBetween(DB::raw('date_format(fecha_viat, "%Y-%m-%d")'), [$fecha_inicio,$fecha_fin])
            ->where('estado', '=', 1)
            ->where('id_usuario', '=', $empleado_id)
            ->sum('total');
        return $gasto;
    }
}
