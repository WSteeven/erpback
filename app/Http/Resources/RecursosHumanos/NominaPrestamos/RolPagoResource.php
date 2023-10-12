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
            'empleado_info' =>  $this->empleado_info->apellidos. ' ' .$this->empleado_info->nombres,
            'tipo_contrato' => $this->empleado_info->tipo_contrato_id,
            'cargo' => $this->empleado_info->cargo,
            'salario' => number_format($this->salario, 2, ',', '.'),
            'dias' => $this->dias,
            'mes' => $this->mes,
            'anticipo' => number_format($this->anticipo, 2, ',', '.'),
            'iess' =>  number_format($this->iess, 2, ',', '.'),
            'salario' => number_format( $this->salario, 2, ',', '.'),
            'sueldo' => number_format($this->sueldo, 2, ',', '.'),
            'supa' => $this->empleado_info->supa,
            'extension_cobertura_salud' => number_format(ExtensionCoverturaSalud::where('empleado_id', $this->empleado_id)->where('mes', $this->mes)->sum('aporte'), 2),
            'prestamo_hipotecario' => number_format($this->prestamo_hipotecario, 2),
            'prestamo_quirorafario' => number_format($this->prestamo_quirorafario, 2),
            'bonificacion' => $this->bonificacion,
            'bono_recurente' => $this->bono_recurente,
            'fondos_reserva' => number_format($this->fondos_reserva, 2, ',', '.'),
            'concepto_ingreso_info' => $this->ConceptoIngreso($this->ingreso_rol_pago),
            'descuento_general_info' => $this->Descuentos($this->egreso_rol_pago, 'DescuentosGenerales'),
            'descuento_ley_info' => $this->DescuentosLey($this->empleado_info, $this),
            'prestamo_empresarial' => $this->prestamo_empresarial,
            'multa_info' => $this->Descuentos($this->egreso_rol_pago, 'Multas'),
            'decimo_tercero' => number_format($this->decimo_tercero, 2, ',', '.'),
            'decimo_cuarto' => number_format($this->decimo_cuarto, 2, ',', '.'),
            'ingresos' =>  $this->ingreso_rol_pago,
            'egresos' => $this->Egresos($this->egreso_rol_pago),
            'total_ingreso' => number_format($this->total_ingreso, 2, ',', '.'),
            'total_egreso' =>  number_format($this->total_egreso, 2, ',', '.'),
            'total' => number_format($this->total, 2, ',', '.'),
            'estado' => $this->estado,
            'rol_pago_id' => $this->rol_pago_id,
            'es_quincena' => $this->rolPagoMes->es_quincena,
            'medio_tiempo' => $this->medio_tiempo,
            'porcentaje_anticipo' => $this->calcularPorcentajeAnticipo($this->rolPagoMes->es_quincena)
        ];
        return $modelo;
    }
    /**
     * La función calcula el porcentaje del anticipo en función del salario del empleado, teniendo en
     * cuenta si se trata de un pago quincenal o no.
     *
     * @param es_quincena Un parámetro booleano que indica si se trata de un período de pago quincenal
     * o no.
     *
     * @return el porcentaje calculado del anticipo o salario, según sea quincenal o no.
     */
    private function calcularPorcentajeAnticipo($es_quincena)
    {
        $porcentaje = $this->anticipo > 0 ? ($this->anticipo / $this->empleado_info->salario) * 100 : 0;
        if ($es_quincena) {
            $porcentaje = $this->sueldo > 0 ? ($this->sueldo / $this->empleado_info->salario) * 100 : 0;
        }
        Log::channel('testing')->info('Log', ['poncentaje', $porcentaje]);

        return $porcentaje;
    }
    /**
     * La función "DescuentosLey" calcula y devuelve una representación en cadena de las diversas
     * deducciones de la nómina de un empleado, incluyendo la contribución de IESS, SUPA, extensión de
     * cobertura de salud y deducciones de hipotecas y préstamos.
     *
     * @param empleado El parámetro `` es un objeto que representa a un empleado. Es probable
     * que contenga información como la identificación del empleado, el nombre y otros detalles.
     * @param rol_pago El parámetro `` es un objeto que representa la nómina de un mes
     * específico. Contiene información como el mes, el salario del empleado y otros detalles
     * relacionados con la nómina.
     *
     * @return una cadena que representa los descuentos aplicados a la nómina de un empleado. La cadena
     * contiene los nombres de los descuentos y sus valores correspondientes, separados por comas.
     */
    private function  DescuentosLey($empleado, $rol_pago)
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


    /**
     * La función ConceptoIngreso toma un arreglo de ingresos, extrae los valores concepto_ingreso_info
     * y monto, y devuelve una cadena con el formato "concepto: monto" para cada ingreso.
     *
     * @param ingresos Se espera que el parámetro `` sea una colección o matriz de ingresos.
     * Cada ingreso debe tener una propiedad `concepto_ingreso_info`, que es un objeto que contiene
     * información sobre el concepto_ingreso, y una propiedad `monto`, que representa el monto del
     *
     * @return una cadena que representa el concepto de ingresos.
     */
    private function  ConceptoIngreso($ingresos)
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
    /**
     * La función "Descuentos" toma una colección de "egresos" y un parámetro "tipo", filtra los egresos
     * según el tipo, asigna los egresos filtrados a un formato de cadena y devuelve la cadena
     * resultante.
     *
     * @param egresos Se espera que el parámetro `` sea una colección de objetos. Parece que se
     * usa para filtrar y procesar una lista de gastos.
     * @param tipo El parámetro "tipo" es una cadena que representa el tipo de descuento. Se utiliza
     * para filtrar la colección "egresos" e incluir solo los que tienen un valor "descuento_type"
     * coincidente.
     *
     * @return una cadena que contiene los nombres y valores de los descuentos.
     */
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
    /**
     * La función "Egresos" transforma una matriz de "egresos" añadiendo una nueva clave "tipo" basada en
     * el valor de "descuento_type".
     *
     * @param egresos Se espera que el parámetro `` sea un objeto que se pueda convertir en una
     * matriz. Parece contener datos relacionados con gastos o egresos.
     *
     * @return una matriz de elementos transformados. Cada elemento de la matriz original se transforma
     * en un nuevo elemento con pares clave-valor adicionales. Se añade la clave "tipo" con un valor
     * basado en el valor "descuento_type" del elemento original. A continuación, se devuelve la matriz
     * transformada.
     */
    private function Egresos($egresos)
    {
        $arregloOriginal = $egresos->toArray();
        // Creamos una función anónima para transformar cada elemento del arreglo
        $arregloTransformado = array_map(function ($elemento) {
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
    /**
     * La función "cambiar_fecha" toma una fecha como entrada y devuelve la fecha en formato
     * "dd-mm-yyyy".
     *
     * @param fecha El parámetro "fecha" es una cadena de fecha que debe formatearse.
     *
     * @return la fecha formateada en el formato 'd-m-Y'.
     */
    private function cambiar_fecha($fecha)
    {
        $fecha_formateada = Carbon::parse($fecha)->format('d-m-Y');
        return $fecha_formateada;
    }
}
