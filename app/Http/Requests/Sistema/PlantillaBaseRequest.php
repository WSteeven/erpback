<?php

namespace App\Http\Requests\Sistema;

use Illuminate\Foundation\Http\FormRequest;

class PlantillaBaseRequest extends FormRequest
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
            'nombre' => 'required|string|unique:conf_plantillas,nombre',
            'url' => 'required',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $plantilla = $this->route()->parameter('plantilla');
            $rules['nombre'] = 'required|string|unique:conf_plantillas,nombre,' . $plantilla->id;
        }

        return $rules;
    }
}
