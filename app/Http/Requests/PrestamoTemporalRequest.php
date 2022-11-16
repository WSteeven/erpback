<?php

namespace App\Http\Requests;

use App\Models\Inventario;
use App\Models\PrestamoTemporal;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule as ValidationRule;

class PrestamoTemporalRequest extends FormRequest
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
            'fecha_salida' => 'required|string',
            'fecha_devolucion' => 'nullable|string',
            'observacion' => 'nullable|string|sometimes',
            'solicitante' => 'required|exists:empleados,id',
            'per_entrega' => 'required|exists:empleados,id',
            'per_recibe' => 'nullable|sometimes|exists:empleados,id',
            'listadoProductos.*.cantidades' => 'required',
            'estado' => ['required', Rule::in([PrestamoTemporal::PENDIENTE, PrestamoTemporal::DEVUELTO])],
        ];
    }

    public function attributes()
    {
        return [
            'listadoProductos.*.cantidades' => 'listado',
        ];
    }
    public function messages()
    {
        return [
            'listadoProductos.*.cantidades' => 'Debes seleccionar una cantidad para el producto del :attribute',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            Log::channel('testing')->info('Log', ['Listado en el prestamorequest:', $this->listadoProductos]);
            foreach ($this->listadoProductos as $listado) {
                // Log::channel('testing')->info('Log', ['Listado en el foreach del prestamorequest:', $listado['id'], $listado['cantidades']]);
                $item = Inventario::find($listado['id']);
                Log::channel('testing')->info('Log', ['Cantidad encontrada:', $item]);
                Log::channel('testing')->info('Log', ['Listado:', $listado['cantidad']]);
                
                if(array_key_exists('cantidades', $listado)){
                    Log::channel('testing')->info('Log', ['Listado:', $listado['cantidades']]);
                    if ($listado['cantidades'] < 1) {
                        $validator->errors()->add('listadoProductos.*.cantidad', 'La cantidad ingresada debe ser mínimo 1');
                    } else {
                        if (in_array($this->method(), ['POST'])) {
                            if ($item->cantidad < $listado['cantidades']) {
                                $validator->errors()->add('listadoProductos.*.cantidad', 'La cantidad ingresada no debe ser mayor a la existente en el inventario');
                            }
                        }
                    }
                }
            }
        });
    }

    protected function prepareForValidation()
    {
        if (!is_null($this->fecha_salida)) {
            $this->merge([
                'fecha_salida' => date('Y-m-d', strtotime($this->fecha_salida)),
            ]);
        }
        if (!is_null($this->fecha_devolucion)) {
            $this->merge([
                'fecha_devolucion' => date('Y-m-d', strtotime($this->fecha_devolucion)),
            ]);
        }
        $this->merge([
            'per_entrega' => auth()->user()->empleado->id
        ]);
        if ($this->estado === PrestamoTemporal::DEVUELTO) {
            $this->merge([
                'per_recibe' => auth()->user()->empleado->id
            ]);
        }
    }
}
