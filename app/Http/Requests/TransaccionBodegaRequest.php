<?php

namespace App\Http\Requests;

use App\Models\DetalleProducto;
use App\Models\EstadoTransaccion;
use App\Models\Fibra;
use App\Models\Inventario;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class TransaccionBodegaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        // Log::channel('testing')->info('Log', ['Datos recibidos en TransaccionBodegaRequest', $this->request->all()]);
        $rules = [
            'autorizacion_id' => 'required|exists:autorizaciones,id',
            'observacion_aut' => 'nullable|string|sometimes',
            'justificacion' => 'required|string',
            'comprobante' => 'sometimes|string|nullable',
            'proveedor' => 'sometimes|string|nullable|required_with_all:comprobante',
            'fecha_limite' => 'nullable|string',
            'estado_id' => 'required|exists:estados_transacciones_bodega,id',
            'observacion_est' => 'nullable|string|sometimes',
            'devolucion_id' => 'sometimes|nullable|exists:devoluciones,id', //|unique:transacciones_bodega,devolucion_id,NULL,id,id,'.$this->id,
            'pedido_id' => 'sometimes|nullable|exists:pedidos,id', //|unique:transacciones_bodega,devolucion_id,NULL,id,id,'.$this->id,
            'transferencia_id' => 'sometimes|nullable|exists:transferencias,id', //|unique:transacciones_bodega,devolucion_id,NULL,id,id,'.$this->id,
            'solicitante_id' => 'required|exists:empleados,id',
            'tipo_id' => 'sometimes|nullable|exists:tipos_transacciones,id',
            'motivo_id' => 'required|exists:motivos,id',
            'sucursal_id' => 'required|exists:sucursales,id',
            'per_autoriza_id' => 'required|exists:empleados,id',
            'per_atiende_id' => 'sometimes|nullable|exists:empleados,id',
            'per_retira_id' => 'sometimes|nullable|exists:empleados,id',
            'proyecto_id' => 'sometimes|nullable|exists:proyectos,id',
            'etapa_id' => 'sometimes|nullable|exists:tar_etapas,id',
            'tarea_id' => 'sometimes|nullable|exists:tareas,id',
            'cliente_id' => 'sometimes|exists:clientes,id',
            'listadoProductosTransaccion.*.cantidad' => 'required',
            'codigo_permiso_traslado' => 'nullable|string',
        ];
        if ($this->route()->uri() === 'api/transacciones-egresos') {
            // $rules['autorizacion'] = 'nullable';
            $rules['responsable_id'] = 'required|exists:empleados,id';
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'listadoProductosTransaccion.*.cantidad' => 'listado',
        ];
    }


    public function messages()
    {
        return [
            'listadoProductosTransaccion.*.cantidad' => 'Debes seleccionar una cantidad para el producto del :attribute',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->route()->uri() === 'api/transacciones-ingresos') {
                foreach ($this->listadoProductosTransaccion as $listado) {
                    if (!array_key_exists('cantidad', $listado)) $validator->errors()->add('listadoProductosTransaccion.*.cantidad', 'Ingresa la cantidad del ítem ' . $listado['descripcion']);
                    else if ($listado['cantidad'] <= 0) $validator->errors()->add('listadoProductosTransaccion.*.cantidad', 'La cantidad para el ítem ' . $listado['descripcion'] . ' debe ser mayor que 0');
                    else {
                        $item_inventario = !!Inventario::where('detalle_id', $listado['detalle_id'])->where('cantidad', '>', 0)->first();
                        // $this->transferencia? Log::channel('testing')->info('Log', ['Elemento en transferencia: ', Inventario::where('detalle_id', $listado['detalle_id'])->where('cantidad', '>', 0)->first()]): Log::channel('testing')->info('Log', ['Elemento en normal: ', Inventario::where('detalle_id', $listado['id'])->where('cantidad', '>', 0)->first(),!!Inventario::where('detalle_id', $listado['id'])->where('cantidad', '>', 0)->first()]);
                        //valida que se ingrese cantidad 1 cuando el elemento tiene un serial(identificador de elemento unico)
                        if (array_key_exists('serial', $listado)) {
                            Log::channel('testing')->info('Log', ['Datos 88 ', $listado, !!Fibra::find($listado['id'])]);
                            $producto = Producto::where('nombre', $listado['producto'])->first();
                            $detalle = DetalleProducto::where('producto_id', $producto->id)->where('descripcion', $listado['descripcion'])->where('serial', $listado['serial'])->first();
                            $es_fibra = !!Fibra::find($detalle->id);
                            if ($listado['serial'] && $listado['cantidad'] > 1 && !$es_fibra) $validator->errors()->add('listadoProductosTransaccion.*.cantidad', 'La cantidad para el ítem ' . $listado['descripcion'] . ' debe ser 1');
                            if ($listado['serial'] && $item_inventario && !$es_fibra) $validator->errors()->add('listadoProductosTransaccion.*.descripcion', 'Ya existe el ítem ' . $listado['descripcion'] . ' registrado en una bodega. Revisa el inventario');
                        }
                        //válida si no hay ingreso masivo que se envie el estado util de todos los productos ingresados
                        if (!$this->ingreso_masivo) {
                            if (array_key_exists('condiciones', $listado)) {
                                // Log::channel('testing')->info('Log', ['Datos recibidos', $listado, $listado['condiciones']]);
                            } else {
                                $validator->errors()->add('listadoProductosTransaccion.*.condiciones', 'Debe ingresar el estado del item ' . $listado['descripcion']);
                            }
                        }
                    }
                }
            } else {
                /**
                 * CORREGIR LOS EGRESOS, VERIFICAR QUE FUNCIONE BIEN
                 *
                 *
                 *
                 *
                 */
                foreach ($this->listadoProductosTransaccion as $listado) {
                    // Log::channel('testing')->info('Log', ['Datos recibidos en foreach del TRANSACCIONBODEGAREQUEST', $listado]);
                    $item_inventario = Inventario::find($listado['id']);
                    if ($listado['cantidad'] <= 0) $validator->errors()->add('listadoProductoTransaccion.*.cantidad', 'La cantidad para el item ' . $listado['descripcion'] . ' debe ser mayor a cero');
                    if ($listado['cantidad'] > $item_inventario->cantidad) {
                        $validator->errors()->add('listadoProductoTransaccion.*.cantidad', 'La cantidad para el item ' . $listado['descripcion'] . ' no debe ser superior a la existente en el inventario. En inventario: ' . $item_inventario->cantidad);
                    }
                }
            }
            if (!in_array($this->method(), ['PUT', 'PATCH'])) {
                if (!is_null($this->fecha_limite)) {
                    // Log::channel('testing')->info('Log', ['Datos recibidos', $this->fecha_limite]);
                    if (date('Y-m-d', strtotime($this->fecha_limite)) < now()) {
                        $validator->errors()->add('fecha_limite', 'La fecha límite debe ser superior a la fecha actual');
                    }
                }
            }
        });
    }

    protected function prepareForValidation()
    {
        $estado_completo = EstadoTransaccion::where('nombre', EstadoTransaccion::COMPLETA)->first();

        if (!is_null($this->fecha_limite)) {
            $this->merge([
                'fecha_limite' => date('Y-m-d', strtotime($this->fecha_limite)),
            ]);
        }
        /**
         * **********************************************************************************     INGRESOS     ****************************************
         */
        if ($this->route()->uri() === 'api/transacciones-ingresos') {
            if (is_null($this->autorizacion) || $this->autorizacion == '') {
                // Log::channel('testing')->info('Log', ['autorizacion', $this->autorizacion]);
                $this->merge([
                    'autorizacion' => 2,
                ]);
            }
            if (is_null($this->tipo) || $this->tipo == '') {
                $this->merge([
                    'tipo' => 1,
                ]);
            }

            $this->merge([
                'estado' => $estado_completo->id
            ]);
            // }
            if (is_null($this->solicitante) || $this->solicitante === '') {
                $this->merge([
                    'solicitante' => auth()->user()->empleado->id,
                ]);
            }

            $this->merge([
                'per_atiende' => auth()->user()->empleado->id
            ]);
        }
        /**
         * **********************************************************************************     EGRESOS     ***************************************
         */
        if ($this->route()->uri() === 'api/transacciones-egresos') {
            if ($this->autorizacion == '') {
                $this->merge([
                    'autorizacion' => 1,
                ]);
            }
            if (is_null($this->solicitante) || $this->solicitante === '') {
                $this->merge([
                    'solicitante' => auth()->user()->empleado->id,
                ]);
            }
            $this->merge([
                'estado' => $estado_completo->id,
            ]);
            if ($this->fecha_limite === "N/A" || is_null($this->fecha_limite)) {
                $this->merge([
                    'fecha_limite' => null
                ]);
            }
            if (is_null($this->per_retira)) {
                $this->merge([
                    'per_retira' => $this->responsable,
                ]);
            }
        }
        if ($this->cliente === '' || is_null($this->cliente)) {
            $this->merge([
                'cliente' => 1,
            ]);
        }
        /* if ($this->estado === '' || is_null($this->estado)) {
            $this->merge([
                'estado' => 1,
            ]);
        } */
        /* if($this->motivo){
            $this->merge([
                'tipo'=>Motivo::where('id',$this->motivo)->get('tipo_transaccion_id')
            ]);
        } */

        if ($this->estado === 2) {
            $this->merge([
                'per_atiende' => auth()->user()->empleado->id
            ]);
        }

        if (is_null($this->per_autoriza)) {
            if (auth()->user()->hasRole([User::ROL_ACTIVOS_FIJOS, User::ROL_BODEGA, User::ROL_GERENTE])) {
                $this->merge([
                    'per_autoriza' => auth()->user()->empleado->id,
                ]);
            }
        }

        if (is_null($this->per_autoriza)) {
            $this->merge([
                'per_autoriza' => auth()->user()->empleado->jefe_id,
            ]);
        }
        if (auth()->user()->hasRole([User::ROL_BODEGA, User::ROL_BODEGA_TELCONET])) {
            $this->merge([
                'autorizacion' => 2
            ]);
        }
        // Casteo de llaves foraneas
        if ($this->transferencia)  $this->merge(['transferencia_id' => $this->transferencia]);
        if ($this->per_atiende)  $this->merge(['per_atiende_id' => $this->per_atiende]);
        if ($this->pedido)  $this->merge(['pedido_id' => $this->pedido]);
        if ($this->proyecto)  $this->merge(['proyecto_id' => $this->proyecto]);
        if ($this->etapa)  $this->merge(['etapa_id' => $this->etapa]);

        $this->merge([
            'autorizacion_id' => $this->autorizacion,
            'devolucion_id' => $this->devolucion,
            'motivo_id' => $this->motivo,
            'solicitante_id' => $this->solicitante,
            'sucursal_id' => $this->sucursal,
            'per_autoriza_id' => $this->per_autoriza,
            'cliente_id' => $this->cliente,
            'estado_id' => $this->estado,
            'tarea_id' => $this->tarea,
            'responsable_id' => $this->responsable,
        ]);
    }
}
