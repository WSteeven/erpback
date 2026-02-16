<?php

namespace App\Http\Requests\Tareas;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TipoCoordenadaRequest extends FormRequest
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
        $ignoreId = null;
        $routeModel = $this->route('tipo_coordenada');
        if ($routeModel) {
            $ignoreId = is_object($routeModel) ? ($routeModel->id ?? $routeModel) : $routeModel;
        }

        return [
            'nombre' => [
                'required',
                'string',
                Rule::unique('tar_tipos_coordenadas', 'nombre')->ignore($ignoreId),
            ],
        ];
    }
}
