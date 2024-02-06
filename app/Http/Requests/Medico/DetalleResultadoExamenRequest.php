<?php

namespace App\Http\Requests\Medico;

use Illuminate\Foundation\Http\FormRequest;

class DetalleResultadoExamenRequest extends FormRequest
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
            'observacion' => 'required|string',
            'estado_solicitud_examen_id' => 'required|exists:med_examenes,id',
            // 'resultados_examenes.*.categoria_id' => 'required|numeric|integer|exists:med_categorias_examenes,id',
            'resultados_examenes.*.id' => 'nullable|numeric|integer|exists:med_resultados_examenes,id',
            'resultados_examenes.*.resultado' => 'required|numeric',
            'resultados_examenes.*.configuracion_examen_campo' => 'required|numeric|integer|exists:med_configuraciones_examenes_campos,id',
            // 'resultados_examenes.*.estado_solicitud_examen' => 'required|numeric|integer|exists:med_estados_solicitudes_examenes,id',
            /* 'resultados_examenes.*.campos.id' => 'nullable|exists:med_configuraciones_examenes_campos,id',
            'resultados_examenes.*.campos.resultado' => 'nullable|numeric', */
        ];

        /*if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules['resultados_examenes.*.id'] = 'required|numeric|integer|exists:med_resultados_examenes,id';
            $rules['resultados_examenes.*.resultado'] = 'required|numeric|integer';
            $rules['resultados_examenes.*.configuracion_examen_campo'] = 'required|numeric|integer|exists:med_configuraciones_examenes_campos,id';
        }*/

        return $rules;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'estado_solicitud_examen_id' =>  $this->estado_solicitud_examen,
        ]);
    }
}
