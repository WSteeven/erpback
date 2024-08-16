<?php

namespace App\Http\Requests\ActivosFijos;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class SeguimientoConsumoActivosFijosRequest extends FormRequest
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
            'cantidad_utilizada' => 'required|numeric|integer|min:0',
            'cantidad_anterior' => 'required|numeric|integer|min:0',
            'canton_id' => 'required|numeric|integer|exists:cantones,id',
            'motivo_consumo_activo_fijo_id' => 'required|numeric|integer|exists:af_motivos_consumo_activos_fijos,id',
            'cliente_id' => 'required|numeric|integer|exists:clientes,id',
            'detalle_producto_id' => 'required|numeric|integer|exists:detalles_productos,id',
            'empleado_id' => 'required|numeric|integer|exists:empleados,id',
            'observacion' => 'nullable|string',
            'justificativo_uso' => 'nullable|string',
            'se_reporto_sicosep' => 'nullable|boolean',
        ];

        // Para PATCH, solo validar los campos que se envían en la solicitud
        if ($this->isMethod('patch')) {
            $rules = collect($rules)->only(array_keys($this->all()))->toArray(); // Esta regla está bien para pach, verificado el 14/8/2024
           /*  Log::channel('testing')->info('Log', ['No null' => array_keys($this->all())]);
            Log::channel('testing')->info('Log', ['Rules' => $rules]);
            Log::channel('testing')->info('Log', ['Entrada' => $this->input()]); */
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        if ($this->isMethod('post')) {
            $this->merge([
                'canton_id' => $this->canton,
                'motivo_consumo_activo_fijo_id' => $this->motivo_consumo,
                'cliente_id' => $this->cliente,
                'detalle_producto_id' => $this->detalle_producto,
                'empleado_id' => auth()->user()->empleado->id,
            ]);
        }
    }
}
