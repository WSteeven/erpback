<?php

namespace App\Http\Requests;

use App\Models\DetalleProducto;
use App\Models\EstadoTransaccion;
use App\Models\Fibra;
use App\Models\Inventario;
use App\Models\Motivo;
use App\Models\Producto;
use App\Models\Tarea;
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
            'autorizacion' => 'required|exists:autorizaciones,id',
            'observacion_aut' => 'nullable|string|sometimes',
            'justificacion' => 'required|string',
            'comprobante' => 'sometimes|string|nullable',
            'proveedor' => 'sometimes|string|nullable|required_with_all:comprobante',
            'fecha_limite' => 'nullable|string',
            'estado' => 'required|exists:estados_transacciones_bodega,id',
            'observacion_est' => 'nullable|string|sometimes',
            'devolucion' => 'sometimes|nullable|exists:devoluciones,id', //|unique:transacciones_bodega,devolucion_id,NULL,id,id,'.$this->id,
            'pedido' => 'sometimes|nullable|exists:pedidos,id', //|unique:transacciones_bodega,devolucion_id,NULL,id,id,'.$this->id,
            'transferencia' => 'sometimes|nullable|exists:transferencias,id', //|unique:transacciones_bodega,devolucion_id,NULL,id,id,'.$this->id,
            'solicitante' => 'required|exists:empleados,id',
            'tipo' => 'sometimes|nullable|exists:tipos_transacciones,id',
            'motivo' => 'required|exists:motivos,id',
            'sucursal' => 'required|exists:sucursales,id',
            'per_autoriza' => 'required|exists:empleados,id',
            'per_atiende' => 'sometimes|nullable|exists:empleados,id',
            'per_retira' => 'sometimes|nullable|exists:empleados,id',
            'tarea' => 'sometimes|nullable|exists:tareas,id',
            'cliente' => 'sometimes|exists:clientes,id',
            'listadoProductosTransaccion.*.cantidad' => 'required'
        ];
        if ($this->route()->uri() === 'api/transacciones-egresos') {
            // $rules['autorizacion'] = 'nullable';
            $rules['responsable'] = 'required|exists:empleados,id';
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
                        $itemInventario = $this->transferencia ? !!Inventario::where('detalle_id', $listado['detalle_id'])->where('cantidad', '>', 0)->first() : !!Inventario::where('detalle_id', $listado['id'])->where('cantidad', '>', 0)->first();
                        // $this->transferencia? Log::channel('testing')->info('Log', ['Elemento en transferencia: ', Inventario::where('detalle_id', $listado['detalle_id'])->where('cantidad', '>', 0)->first()]): Log::channel('testing')->info('Log', ['Elemento en normal: ', Inventario::where('detalle_id', $listado['id'])->where('cantidad', '>', 0)->first(),!!Inventario::where('detalle_id', $listado['id'])->where('cantidad', '>', 0)->first()]);
                        //valida que se ingrese cantidad 1 cuando el elemento tiene un serial(identificador de elemento unico)
                        if (array_key_exists('serial', $listado)) {
                            Log::channel('testing')->info('Log', ['Datos 88 ', $listado, !!Fibra::find($listado['id'])]);
                            $producto = Producto::where('nombre', $listado['producto'])->first();
                            $detalle = DetalleProducto::where('producto_id', $producto->id)->where('descripcion', $listado['descripcion'])->where('serial', $listado['serial'])->first();
                            $esFibra = !!Fibra::find($detalle->id);
                            if ($listado['serial'] && $listado['cantidad'] > 1 && !$esFibra) $validator->errors()->add('listadoProductosTransaccion.*.cantidad', 'La cantidad para el ítem ' . $listado['descripcion'] . ' debe ser 1');
                            if ($listado['serial'] && $itemInventario && !$esFibra) $validator->errors()->add('listadoProductosTransaccion.*.descripcion', 'Ya existe el ítem ' . $listado['descripcion'] . ' registrado en una bodega. Revisa el inventario');
                        }
                        //valida si no hay ingreso masivo que se envie el estado util de todos los productos ingresados
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
                    $itemInventario = Inventario::find($listado['id']);
                    if ($listado['cantidad'] <= 0) $validator->errors()->add('listadoProductoTransaccion.*.cantidad', 'La cantidad para el item ' . $listado['descripcion'] . ' debe ser mayor a cero');
                    if ($listado['cantidad'] > $itemInventario->cantidad) {
                        $validator->errors()->add('listadoProductoTransaccion.*.cantidad', 'La cantidad para el item ' . $listado['descripcion'] . ' no debe ser superior a la existente en el inventario. En inventario: ' . $itemInventario->cantidad);
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
        $user_activo_fijo = User::whereHas("roles", function ($q) {
            $q->where("name", User::ROL_ACTIVOS_FIJOS);
        })->get();

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
            // if ($this->ingreso_masivo) {
            //     $this->merge([
            //         'estado' => $estado_completo->id
            //     ]);
            // } else {
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
        //Log::channel('testing')->info('Log', ['Usuario es coordinador?:', auth()->user()->hasRole(User::ROL_COORDINADOR)]);
    }
}
