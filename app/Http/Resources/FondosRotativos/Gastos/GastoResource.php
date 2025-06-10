<?php

namespace App\Http\Resources\FondosRotativos\Gastos;

use App\Http\Resources\FondosRotativos\ValijaResource;
use App\Models\Empleado;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GastoResource extends JsonResource
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
            'fecha_viat' => $this->cambiarFecha($this->fecha_viat),
            'lugar' => $this->id_lugar,
            'lugar_info' => $this->canton->canton,
            'num_tarea' => $this->id_tarea == null ? 0 : $this->id_tarea,
            'subTarea' => $this->id_subtarea == null ? 0 : $this->id_subtarea,
            'subTarea_info' => $this->subTarea != null ? $this->subTarea->codigo_subtarea . ' - ' . $this->subTarea->titulo : 'Sin Subtarea',
            'tarea_info' => $this->tarea != null ? $this->tarea->codigo_tarea . ' - ' . $this->tarea->detalle : 'Sin Tarea',
            'tarea_cliente' => $this->tarea?->codigo_tarea_cliente != null || strlen($this->tarea?->codigo_tarea_cliente) > 0 ? $this->tarea->codigo_tarea_cliente : 'Sin Tarea',
            'proyecto' => $this->id_proyecto != null ? $this->id_proyecto : 0,
            'proyecto_info' => $this->Proyecto != null ? $this->Proyecto->codigo_proyecto . ' - ' . $this->Proyecto->nombre : 'Sin Proyecto',
            'ruc' => $this->ruc,
            'factura' => strlen($this->factura) > 1 ? $this->factura : null,
            'aut_especial_user' => $this->authEspecialUser->nombres . ' ' . $this->authEspecialUser->apellidos,
            'aut_especial' => $this->aut_especial,
            'detalle_info' => $this->detalle_info->descripcion,
            'detalle_estado' => $this->detalle_estado,
            'sub_detalle_info' => $this->subdetalleInfo($this->subDetalle),
            'beneficiarios' => $this->beneficiarioGasto != null ? $this->beneficiarioGasto->pluck('empleado_id') : null,
            'beneficiarios_info' => $this->beneficiarioEmpleadoInfo($this->beneficiarioGasto),
            'sub_detalle' => $this->subDetalle != null ? $this->subDetalle->pluck('id') : null,
            'vehiculo' => $this->gastoVehiculo != null ? $this->gastoVehiculo->id_vehiculo : '',
            'placa' => $this->gastoVehiculo != null ? $this->gastoVehiculo->placa : '',
            'es_vehiculo_alquilado' => !!$this->gastoVehiculo?->es_vehiculo_alquilado,
            'kilometraje' => $this->gastoVehiculo != null ? $this->gastoVehiculo->kilometraje : '',
            'detalle' => $this->detalle,
            'cantidad' => $this->cantidad,
            'valor_u' => $this->valor_u,
            'total' => $this->total,
            'num_comprobante' => $this->num_comprobante,
            'comprobante1' => $this->comprobante ? url($this->comprobante) : null,
            'comprobante2' => $this->comprobante2 ? url($this->comprobante2) : null,
            'comprobante3' => $this->comprobante3 ? url($this->comprobante3) : null,
            'comprobante4' => $this->comprobante4 ? url($this->comprobante4) : null,
            'observacion' => $this->observacion,
            'observacion_anulacion' => $this->observacion_anulacion,
            'id_usuario' => $this->id_usuario,
            'empleado_info' => Empleado::extraerNombresApellidos($this->empleado),
            'estado' => $this->estado,
            'estado_info' => $this->estadoViatico?->descripcion,
            'id_lugar' => $this->id_lugar,
            'tiene_factura_info' => $this->subDetalle != null ? $this->subDetalle : true,
            'tiene_factura' => !($this->subDetalle != null) || $this->tieneFactura($this->subDetalle),
            'created_at' => Carbon::parse($this->created_at)
                ->format('d-m-Y H:i'),
            'updated_at' => Carbon::parse($this->updated_at)->format('Y-m-d H:i:s'),
            'centro_costo' => $this->tarea !== null ? $this->tarea->centroCosto?->nombre : '',
            'subcentro_costo' => $this->empleado?->grupo == null ? '' : $this->empleado?->grupo?->subCentroCosto?->nombre,
        ];

        if ($controller_method == 'show') {
            $modelo['nodo'] = $this->nodo_id;
            $modelo['registros_valijas'] = ValijaResource::collection($this->valijas);
            $modelo['se_envia_valija'] = $this->valijas->count()>0;
        }
        return $modelo;
    }

    /**
     * La función "tieneFactura" comprueba si algún artículo del array tiene factura y devuelve verdadero
     * si al menos un artículo tiene factura.
     *
     * @param Collection $subdetalle_info Según el fragmento de código proporcionado, la función `tieneFactura`
     * toma una matriz `` como entrada e itera sobre sus elementos para verificar si
     * alguno de los elementos tiene una clave llamada "tiene_factura" con un valor verdadero. Si se
     * encuentra tal elemento
     *
     * @return boolean La función `tieneFactura` devuelve un valor booleano, ya sea `verdadero` o `falso`, en
     * función de si algún elemento en la matriz `` tiene la clave "tiene_factura"
     * establecida en un valor verdadero.
     */
    private function tieneFactura(Collection $subdetalle_info)
    {
        $tieneFactura = false;
        foreach ($subdetalle_info as $item) {
            if ($item["tiene_factura"]) {
                $tieneFactura = true;
                break;
            }
        }
        return $tieneFactura;
    }

    /**
     * La función "subdetalleInfo" concatena las descripciones de los subdetalles en una matriz con
     * comas entre ellas.
     *
     * @param Collection $subdetalle_info Parece que la función `subdetalleInfo` está diseñada para concatenar
     * la propiedad `descripcion` de cada objeto en la matriz ``, separada por comas.
     *
     * @return string La función `subdetalleInfo` devuelve una cadena concatenada de descripciones de la matriz
     * `subdetalle_info`, separadas por comas.
     */
    private function subdetalleInfo(Collection $subdetalle_info)
    {
        $descripcion = '';
        $i = 0;
        foreach ($subdetalle_info as $sub_detalle) {
            $descripcion .= $sub_detalle->descripcion;
            $i++;
            if ($i !== count($subdetalle_info)) {
                $descripcion .= ', ';
            }
        }
        return $descripcion;
    }

    /**
     * La función `beneficiarioEmpleadoInfo` toma una serie de beneficiarios y devuelve una cadena
     * concatenada de los nombres de sus empleados.
     *
     * @param Collection $beneficiarios La función `beneficiarioEmpleadoInfo` toma una matriz de
     * `beneficiarios` como entrada. Se espera que cada `beneficiario` en la matriz tenga una propiedad
     * `empleado` que a su vez tenga propiedades `nombres` y `apellidos`.
     *
     * @return string La función `beneficiarioEmpleadoInfo` devuelve una cadena concatenada de los nombres
     * completos de los empleados asociados con el conjunto de beneficiarios dado. Los nombres
     * completos se obtienen accediendo a las propiedades `nombres` y `apellidos` del objeto `empleado`
     * dentro de cada objeto beneficiario. Los nombres se concatenan con un espacio entre ellos, y si
     * hay varios beneficiarios, se separan por un
     */
    private function beneficiarioEmpleadoInfo(Collection $beneficiarios)
    {
        $descripcion = '';
        $i = 0;
        foreach ($beneficiarios as $beneficiario) {
            $descripcion .= $beneficiario?->empleado?->nombres . ' ' . $beneficiario?->empleado?->apellidos;
            $i++;
            if ($i !== count($beneficiarios)) {
                $descripcion .= ', ';
            }
        }
        return $descripcion;
    }

    /**
     * La función "cambiarFecha" toma una cadena de fecha como entrada, la analiza usando Carbon y devuelve
     * la fecha formateada como 'Y-m-d'.
     *
     * @param string $fecha La función `cambiarFecha` toma un parámetro de cadena llamado ``, que
     * representa una fecha en un formato específico. La función utiliza la biblioteca Carbon para analizar
     * la cadena de fecha de entrada y luego formatearla como 'Y-m-d', que representa la fecha en el
     * formato año-mes-día. Finalmente,
     *
     * @return string La función `cambiarFecha` toma una cadena `` como entrada, la analiza usando Carbon y
     * luego la formatea en formato 'Y-m-d' (Año-Mes-Día). Luego se devuelve la fecha formateada.
     */
    private function cambiarFecha(string $fecha)
    {
        return Carbon::parse($fecha)->format('Y-m-d');
    }
}
