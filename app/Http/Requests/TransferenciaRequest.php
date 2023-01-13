<?php

namespace App\Http\Requests;

use App\Models\Transferencia;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransferenciaRequest extends FormRequest
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
            'justificacion' => 'required|string',
            'sucursal_salida' => 'required|exists:sucursales,id',
            'sucursal_destino' => 'required|exists:sucursales,id',
            'cliente' => 'required|exists:clientes,id',
            'solicitante' => 'required|exists:empleados,id',
            'autorizacion' => 'required|exists:autorizaciones,id',
            'per_autoriza' => 'required|exists:empleados,id',
            'recibida' => 'sometimes|boolean',
            'estado' => ['required', Rule::in([Transferencia::PENDIENTE, Transferencia::TRANSITO, Transferencia::COMPLETADO])],
            'observacion_aut' => 'sometimes|string',
            'observacion_est' => 'sometimes|string',
            'listadoProductos.*.cantidades' => 'required',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            // $rules['estado'] = '';
        }

        return $rules;
    }
    public function attributes()
    {
        return [
            'listadoProductos.*.cantidades' => 'cantidad',
        ];
    }
    public function messages()
    {
        return [
            'listadoProductos.*.cantidades' => 'Debes seleccionar una cantidad para el producto del listado',
        ];
    }

    /* public function withValidator($validator){

    } */
    public function prepareForValidation()
    {
        if (!in_array($this->method(), ['PUT', 'PATCH'])) {
            $this->merge([
                'autorizacion' => 1, //pendiente
                'solicitante' => auth()->user()->empleado->id,
                'estado' => Transferencia::PENDIENTE,
                'per_autoriza' => 11, //autoriza el de activos fijos

            ]);
        }

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            // $rules['estado'] = '';
            if ($this->autorizacion == 2) {
                $this->merge([
                    'estado' => Transferencia::TRANSITO
                ]);
            }
        }
    }
}
