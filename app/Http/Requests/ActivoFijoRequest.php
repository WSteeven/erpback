<?php

namespace App\Http\Requests;

use App\Models\ActivoFijo;
use App\Models\DetalleProducto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ActivoFijoRequest extends FormRequest
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
        $rules = [
            'cantidad' => 'required|integer',
            'fecha_desde' => 'required|string',
            'fecha_hasta' => 'nullable|string',
            'accion' => ['required', Rule::in([ActivoFijo::ASIGNACION, ActivoFijo::DEVOLUCION])],
            'observacion' => 'nullable|string',
            // 'lugar'=>'required|string',
            'detalle_id' => 'required|exists:detalles_productos,id', //|unique:activos_fijos,detalle_id',
            'empleado' => 'required|exists:empleados,id',
            'sucursal' => 'required|exists:sucursales,id',
            'condicion' => 'required|exists:condiciones_de_productos,id',
        ];

        // Log::channel('testing')->info('Log', ['activo_fijo_parameter: ', $this->route()->parameter('activo')]);
        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $activo = $this->route()->parameter('activo');

            // $rules['detalle_id'] = Rule::unique('activos_fijos')->ignore($activo);
        }

        return $rules;
    }
    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $activo = ActivoFijo::find($this->id);
            if ($activo) {
                if ($this->empleado != $activo->empleado_id) {
                    $validator->errors()->add('empleado', 'El activo no puede cambiar de custodio, primero realiza una devolución del activo y luego asigna el activo al nuevo custodio');
                }
            }
            $detalle = DetalleProducto::find($this->detalle_id);
            if ($this->cantidad > 1) {
                if (!is_null($detalle->serial)) {
                    $validator->errors()->add('detalle_id', 'La cantidad debe ser 1');
                }
            }
            if (!in_array($this->method(), ['PUT', 'PATCH'])) {
                // Log::channel('testing')->info('Log', ['activo- detalle encontrado: ', $detalle]);
                if (!is_null($detalle->serial)) {
                    // Log::channel('testing')->info('Log', ['activo- detalle serial: ', $detalle->serial]);
                    $activo = ActivoFijo::where('detalle_id', $this->detalle_id)->first();
                    if($activo){
                        // Log::channel('testing')->info('Log', ['serial del activo: ', $activo->detalle->serial]);
                        if ($activo->detalle->serial === $detalle->serial) {
                            // Log::channel('testing')->info('Log', ['activo-entro al if: ', $activo->detalle->serial, 'serial', $detalle->serial]);
                            $validator->errors()->add('detalle_id', 'El activo ya está asignado a un custodio, no se puede asignar a alguien más.');
                        }
                    }
                }
            }
        });
    }

    public function prepareForValidation()
    {
        $detalle = DetalleProducto::find($this->detalle_id);
        if (is_null($this->cantidad)) {
            $this->merge([
                'cantidad' => 1
            ]);
        }
    }

    public function messages()
    {
        return [
            'detalle_id.unique' => 'El detalle ya ha sido asignado a un custodio, verifica tus datos e intenta nuevamente.'
        ];
    }
}
