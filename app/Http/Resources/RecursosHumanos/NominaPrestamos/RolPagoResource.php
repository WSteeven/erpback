<?php

namespace App\Http\Resources\RecursosHumanos\NominaPrestamos;

use App\Models\RecursosHumanos\NominaPrestamos\ExtensionCoverturaSalud;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoHipotecario;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoQuirorafario;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class RolPagoResource extends JsonResource
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
            'fecha' => $this->cambiar_fecha($this->created_at),
            'empleado' => $this->empleado_id,
            'empleado_info' => $this->empleado_info->nombres . ' ' . $this->empleado_info->apellidos,
            'cargo' => $this->empleado_info->cargo,
            'salario' => $this->empleado_info->salario,
            'dias' => $this->dias,
            'mes' => $this->mes,
            'anticipo' => $this->anticipo,
            'iess' => $this->iess,
            'sueldo' => $this->sueldo,
            'bonificacion' => $this->bonificacion,
            'bono_recurente' => $this->bono_recurente,
            'concepto_ingreso_info' => $this->ConceptoIngreso($this->ingreso_rol_pago),
            'descuento_general_info' => $this->Descuentos($this->egreso_rol_pago, 'DescuentosGenerales'),
            'descuento_ley_info' => $this->DescuentosLey($this->empleado_info, $this),
            'multa_info' => $this->Descuentos($this->egreso_rol_pago, 'Multa'),
            'decimo_tercero' => $this->decimo_tercero,
            'decimo_cuarto' => $this->decimo_cuarto,
            'ingresos' => $this->ingreso_rol_pago,
            'egresos' => $this->Egresos($this->egreso_rol_pago),
            'total_ingreso' => $this->total_ingreso,
            'total_egreso' => $this->total_egreso,
            'total' => $this->total,
            'estado' => $this->estado,
            'rol_pago_id' => $this->rol_pago_id
        ];
        return $modelo;
    }
    private function /* La función `DescuentosLey` calcula las distintas deducciones relacionadas con
    las leyes laborales para un empleado en una determinada nómina. Incluye
    deducciones como aporte IESS, SUPA (Sistema Unico de Pencion Alimenticia), Ampliación de
    Cobertura de Salud, y varios tipos de préstamos (como Hipotecario y
    Quirorafario). La función recupera los datos relevantes de los modelos
    correspondientes y calcula el monto de deducción total para cada tipo. Luego
    formatea la información de deducción como una cadena y la devuelve. */
    DescuentosLey($empleado, $rol_pago)
    {
        $descuentos = [
            'Aporte IESS' => number_format($rol_pago->iess, 2),
            'SUPA' => $empleado['supa'],
            'Extension de Cobertura de Salud' => number_format(ExtensionCoverturaSalud::where('empleado_id', $empleado->id)->where('mes', $rol_pago->mes)->sum('aporte'), 2),
            'Prestamo Hipotecario' => number_format(PrestamoHipotecario::where('empleado_id', $empleado->id)->where('mes', $rol_pago->mes)->sum('valor'), 2),
            'Prestamo Quirorafario' => number_format(PrestamoQuirorafario::where('empleado_id', $empleado->id)->where('mes', $rol_pago->mes)->sum('valor'), 2),
        ];

        // Filtrar los elementos con valor diferente de 0
        $descuentos = array_filter($descuentos, function ($valor) {
            return $valor != 0;
        });

        $consulta = http_build_query($descuentos, '', ', ');
        $consulta = str_replace(['%3A', '%26'], [':', ','], $consulta);
        $consulta = str_replace('=', ': ', $consulta);
        return str_replace('+', ' ', $consulta);
    }

    private function ConceptoIngreso($ingresos)
    {
        if ($ingresos->isEmpty()) {
            return null;
        }

        $ingresosArray = $ingresos->map(function ($ingreso) {
            $clave = $ingreso['concepto_ingreso_info']->nombre;
            $valor = $ingreso->monto;
            return $clave . ': ' . $valor;
        })->toArray();

        $ingresosString = implode(', ', $ingresosArray);

        return $ingresosString;
    }
    private function Descuentos($egresos, $tipo)
    {
        if ($egresos->isEmpty()) {
            return null;
        }
        $egresosArray = $egresos->filter(function ($egreso) use ($tipo) {
            $tipo_descuento = str_replace("App\\Models\\RecursosHumanos\\NominaPrestamos\\", "", $egreso['descuento_type']);
            return $tipo_descuento == $tipo;
        })->map(function ($egreso) {
            $clave = $egreso['descuento']->nombre;
            $valor = $egreso->monto;
            return $clave . ': ' . $valor;
        })->toArray();
        $egresosString = implode(', ', $egresosArray);
        return $egresosString;
    }
    private function Egresos($egresos)
    {
        $arregloOriginal = $egresos->toArray();
        // Creamos una función anónima para transformar cada elemento del arreglo
        $arregloTransformado = array_map(function ($elemento)
        {
            // Creamos un nuevo elemento con los datos del elemento original
            $nuevoElemento = [
                "id" => $elemento["id"],
                "id_rol_pago" => $elemento["id_rol_pago"],
                "id_descuento" => $elemento["descuento_id"],
                "descuento_type" => $elemento["descuento_type"],
                "tipo" => "", // Aquí agregamos la clave 'tipo' con valor vacío, lo actualizaremos a continuación
                "monto" => $elemento["monto"],
                "created_at" => $elemento["created_at"],
                "updated_at" => $elemento["updated_at"],
                "descuento" => $elemento["descuento"],
            ];

            // Verificamos el valor de 'descuento_type' para asignar el valor correcto a 'tipo'
            if ($elemento["descuento_type"] === "App\\Models\\RecursosHumanos\\NominaPrestamos\\DescuentosGenerales") {
                $nuevoElemento["tipo"] = "DESCUENTO_GENERAL";
            } elseif ($elemento["descuento_type"] === "App\\Models\\RecursosHumanos\\NominaPrestamos\\Multas") {
                $nuevoElemento["tipo"] = "MULTA";
            }

            return $nuevoElemento;
        }, $arregloOriginal);
        return $arregloTransformado;
    }
    private function cambiar_fecha($fecha)
    {
        $fecha_formateada = Carbon::parse($fecha)->format('d-m-Y');
        return $fecha_formateada;
    }
}
