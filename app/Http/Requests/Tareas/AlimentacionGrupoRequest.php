<?php

namespace App\Http\Requests\Tareas;

use Illuminate\Foundation\Http\FormRequest;

class AlimentacionGrupoRequest extends FormRequest
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
            'alimentacion_grupos' => 'required',
            'alimentacion_grupos.*.observacion' => 'nullable|string',
            'alimentacion_grupos.*.cantidad_personas' => 'required|numeric|integer|min:1',
            'alimentacion_grupos.*.precio' => 'required',
            'alimentacion_grupos.*.fecha' => 'required|string',
            'alimentacion_grupos.*.tarea_id' => 'required|numeric|integer|exists:tareas,id',
            'alimentacion_grupos.*.grupo_id' => 'required|numeric|integer|exists:grupos,id',
            'alimentacion_grupos.*.tipo_alimentacion_id' => 'required|numeric|integer|exists:sub_detalle_viatico,id',
            // Indvidual
            'observacion' => 'nullable|string',
            'cantidad_personas' => 'required|numeric|integer|min:1',
            'precio' => 'required',
            'fecha' => 'required|string',
            'tarea_id' => 'required|numeric|integer|exists:tareas,id',
            'grupo_id' => 'required|numeric|integer|exists:grupos,id',
            'tipo_alimentacion_id' => 'required|numeric|integer|exists:sub_detalle_viatico,id',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules['alimentacion_grupos'] = 'nullable';
            $rules['alimentacion_grupos.*.observacion'] = 'nullable|string';
            $rules['alimentacion_grupos.*.cantidad_personas'] = 'nullable|numeric|integer|min:1';
            $rules['alimentacion_grupos.*.precio'] = 'nullable';
            $rules['alimentacion_grupos.*.fecha'] = 'nullable|string';
            $rules['alimentacion_grupos.*.tarea_id'] = 'nullable|numeric|integer|exists:tareas,id';
            $rules['alimentacion_grupos.*.grupo_id'] = 'nullable|numeric|integer|exists:grupos,id';
            $rules['alimentacion_grupos.*.tipo_alimentacion_id'] = 'nullable|numeric|integer|exists:sub_detalle_viatico,id';
        }

        return $rules;
    }
}
