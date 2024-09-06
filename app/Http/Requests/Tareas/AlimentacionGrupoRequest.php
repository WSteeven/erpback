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
        return [
            'alimentacion_grupos' => 'required',
            'alimentacion_grupos.*.observacion' => 'nullable',
            'alimentacion_grupos.*.cantidad_personas' => 'required',
            'alimentacion_grupos.*.precio' => 'required',
            'alimentacion_grupos.*.fecha' => 'required',
            'alimentacion_grupos.*.tarea_id' => 'required|numeric|integer|exists:tareas,id',
            'alimentacion_grupos.*.grupo_id' => 'required|numeric|integer|exists:grupos,id',
            'alimentacion_grupos.*.tipo_alimentacion_id' => 'required|numeric|integer|exists:sub_detalle_viatico,id',
        ];
    }
}
