<?php

namespace App\Http\Requests\Tareas;

use App\Models\Tareas\TipoCoordenada;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CoordenadasRequest extends FormRequest
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
        $routeModel = $this->route('coordenada') ?? $this->route('coordenadas') ?? $this->route('coordenada_id');
        if ($routeModel) {
            $ignoreId = is_object($routeModel) ? ($routeModel->id ?? $routeModel) : $routeModel;
        }

        return [
            'subtarea_id' => 'required|exists:subtareas,id',
            'tipo_id' => 'nullable|exists:tar_tipos_coordenadas,id',
            'nombre' => ['required', 'string'],
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'direccion' => 'nullable|string',
            'observacion' => 'nullable|string',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'subtarea_id' => $this->subtarea,
            'tipo_id'=> TipoCoordenada::getTipoCoordenadaByNombre($this->tipo),
        ]);
    }
}
