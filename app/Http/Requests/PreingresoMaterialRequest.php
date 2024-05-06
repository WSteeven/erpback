<?php

namespace App\Http\Requests;

use App\Models\DetalleProducto;
use App\Models\Fibra;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class PreingresoMaterialRequest extends FormRequest
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
        return [
            'observacion' => 'string|nullable',
            'cuadrilla' => 'string',
            'num_guia' => 'string',
            'courier' => 'string',
            'fecha' => 'string',
            'proyecto' =>  'sometimes|nullable|numeric|exists:proyectos,id',
            'etapa' =>  'sometimes|nullable|numeric|exists:tar_etapas,id',
            'tarea' =>  'sometimes|nullable|numeric|exists:tareas,id',
            'cliente' => 'sometimes|nullable|numeric|exists:clientes,id',
            'solicitante' => 'nullable|sometimes|numeric|exists:empleados,id',
            'autorizador' => 'nullable|sometimes|numeric|exists:empleados,id',
            'responsable' => 'required',
            'coordinador' => 'required|numeric|exists:empleados,id',
            'autorizacion' => 'sometimes|numeric|exists:autorizaciones,id',
            'observacion_aut' => 'nullable|sometimes|string',
            'listadoProductos.*.cantidad' => 'required',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!in_array($this->method(), ['PUT', 'PATCH'])) {
                foreach ($this->listadoProductos as $item) {
                    $producto = Producto::where('nombre', $item['producto'])->first();
                    //buscamos si el detalle ingresado coincide con uno ya almacenado
                    $detalle = DetalleProducto::where('producto_id', $producto->id)->where(function ($query) use ($item) {
                        $query->where('descripcion', $item['descripcion'])->orWhere('descripcion', 'LIKE', '%' . $item['descripcion']   . '%'); // busca coincidencia exacta o similitud en el texto
                    })->first();

                    if (!$detalle) {
                        $detalleGenerico = DetalleProducto::where('producto_id', $producto->id)->where(function ($query) use ($item) {
                            $query->where('descripcion', $item['descripcion_original'])->orWhere('descripcion', 'LIKE', '%' . $item['descripcion_original']   . '%'); // busca coincidencia exacta o similitud en el texto
                        })->where('es_generico', $item['es_generico'])->first();
                        Log::channel('testing')->info('Log', ['DetalleGenerico:', $detalleGenerico, $item['nombre_alternativo']]);
                        if ($detalleGenerico) {
                            if (is_null($item['serial']))
                                $validator->errors()->add('listadoProductos.*.serial', 'Es necesario un número de serie para  el ítem ' . $item['descripcion']);
                            else {
                                $detalleSerial = DetalleProducto::where('serial', $item['serial'])->first();
                                if ($detalleSerial && ($detalleGenerico->id === $detalleSerial->id)) $validator->errors()->add('listadoProductos.*.serial', 'El serial ' . $item['serial'] . ' ya está ingresado, por favor corrige y vuelve a intentar');
                            };
                        } else $validator->errors()->add('listadoProductosTransaccion.*.producto', 'El ítem ' . $item['descripcion'] . ' no es correcto');
                    } else {
                        $esFibra = !!Fibra::find($detalle->id);
                        if ($esFibra && is_null($item['punta_inicial'])) $validator->errors()->add('listadoProductos.*.punta_inicial', 'La punta inicial para el ítem ' . $item['descripcion'] . ' es requerida');
                        if ($esFibra && is_null($item['punta_final'])) $validator->errors()->add('listadoProductos.*.punta_final', 'La punta final para el ítem ' . $item['descripcion'] . ' es requerida');
                        // verificamos si el detalle necesita obligatoriamente un numero de serie o no
                        if ($item['serial'] && $item['cantidad'] > 1 && !$esFibra) $validator->errors()->add('listadoProductos.*.cantidad', 'La cantidad para el ítem ' . $item['descripcion'] . ' debe ser 1');
                        // $esFibra = $this->verificarSerialFibra($detalle, $item, $validator);

                        if (!is_null($detalle->serial) && is_null($item['serial'])) $validator->errors()->add('listadoProductos.*.serial', 'N° serial es requerido en el elemento ' . $item['descripcion'] . ' Por favor, verifica y corrige la información');

                        //verificacion de serial existente
                        if ($item['serial']) {
                            $detalleSerial = DetalleProducto::where('serial', $item['serial'])->when($item['es_generico'] === true, function ($q) use ($item) {
                                $q->where('descripcion', $item['descripcion']);
                            })->first();
                            Log::channel('testing')->info('Log', ['DetalleSerial:', $item, $esFibra, $detalleSerial]);
                            if ($detalleSerial->serial === $item['serial']) $validator->errors()->add('listadoProductos.*.serial', 'El serial ' . $item['serial'] . ' ya está ingresado. Por favor, verifica y corrige la información');
                            if ($esFibra) $validator->errors()->add('listadoProductos.*.serial', 'El serial ' . $item['serial'] . ' ya está ingresado, ten en cuenta que si se trata de fibra óptica usar extensores (-A, -B, -C, -D, ...etc.)');
                        }
                    }
                }
            }
        });
    }
    private function verificarExisteDetalle($item)
    { //
        $detalle = DetalleProducto::where('descripcion', $item['descripcion'])->first();
    }
    private function verificarSerialFibra($detalle, $item, $validator)
    {
        $esFibra = !!Fibra::find($detalle->id);
        if ($esFibra && is_null($item['punta_inicial'])) $validator->errors()->add('listadoProductos.*.punta_inicial', 'La punta inicial para el ítem ' . $item['descripcion'] . ' es requerida');
        if ($esFibra && is_null($item['punta_final'])) $validator->errors()->add('listadoProductos.*.punta_final', 'La punta final para el ítem ' . $item['descripcion'] . ' es requerida');
        // verificamos si el detalle necesita obligatoriamente un numero de serie o no
        if ($item['serial'] && $item['cantidad'] > 1 && !$esFibra) $validator->errors()->add('listadoProductos.*.cantidad', 'La cantidad para el ítem ' . $item['descripcion'] . ' debe ser 1');

        return $esFibra;
    }

    protected function prepareForValidation()
    {
        $userCoordinadorBodega = User::whereHas('roles', function ($q) {
            $q->where('name', User::ROL_COORDINADOR_BODEGA);
        })->first();

        $this->merge([
            'fecha' => date('Y-m-d', strtotime($this->fecha))
        ]);

        if (is_null($this->solicitante) || $this->solicitante == '') {
            $this->merge([
                'solicitante' => auth()->user()->empleado->id
            ]);
        }

        if (is_null($this->tarea)) { //si no hay tarea, el autorizador es el coordinador de bodega y los materiales se cargarán al stock del tecnico
            $this->merge([
                'autorizador' => $userCoordinadorBodega->empleado->id
            ]);
        } else { //caso contrario, el material se asignará al stock de tarea y el autorizador es el coordinador
            $this->merge([
                'autorizador' => $this->coordinador
            ]);
        }
    }
}
