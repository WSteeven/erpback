<?php

namespace App\Http\Requests\Medico;

use Illuminate\Foundation\Http\FormRequest;

class ConfiguracionExamenCampoRequest extends FormRequest
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
            'campo' => 'required|string',
            'unidad_medida' => 'required|string',
            'intervalo_referencia' => 'required|string',
            'configuracion_examen_categoria_id' => 'required|exists:med_configuraciones_examenes_categorias,id',
        ];
    }
    protected function prepareForValidation()
    {
            $this->merge([
                'configuracion_examen_categoria_id' => $this->configuracion_examen_categoria
            ]);
    }
}
