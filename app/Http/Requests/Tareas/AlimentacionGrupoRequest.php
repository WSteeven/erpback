<?php

namespace App\Http\Requests\Tareas;

use App\Models\FondosRotativos\Gasto\SubDetalleViatico;
use App\Models\Grupo;
use App\Models\Tareas\AlimentacionGrupo;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

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
            'alimentacion_grupos.*.tarea_id' => 'nullable|numeric|integer|exists:tareas,id',
            'alimentacion_grupos.*.subtarea_id' => 'nullable|numeric|integer|exists:subtareas,id',
            'alimentacion_grupos.*.grupo_id' => 'required|numeric|integer|exists:grupos,id',
            'alimentacion_grupos.*.tipo_alimentacion_id' => 'required|numeric|integer|exists:sub_detalle_viatico,id',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules['alimentacion_grupos'] = 'nullable';
            $rules['alimentacion_grupos.*.observacion'] = 'nullable|string';
            $rules['alimentacion_grupos.*.cantidad_personas'] = 'nullable|numeric|integer|min:1';
            $rules['alimentacion_grupos.*.precio'] = 'nullable';
            $rules['alimentacion_grupos.*.fecha'] = 'nullable|string';
            $rules['alimentacion_grupos.*.subtarea_id'] = 'nullable|numeric|integer|exists:subtareas,id';
            $rules['alimentacion_grupos.*.tarea_id'] = 'nullable|numeric|integer|exists:tareas,id';
            $rules['alimentacion_grupos.*.grupo_id'] = 'nullable|numeric|integer|exists:grupos,id';
            $rules['alimentacion_grupos.*.tipo_alimentacion_id'] = 'nullable|numeric|integer|exists:sub_detalle_viatico,id';

            // Indvidual
            $rules['observacion'] = 'nullable|string';
            $rules['cantidad_personas'] = 'required|numeric|integer|min:1';
            $rules['precio'] = 'required';
            $rules['fecha'] = 'required|string';
            $rules['tarea_id'] = 'nullable|numeric|integer|exists:tareas,id';
            $rules['subtarea_id'] = 'nullable|numeric|integer|exists:subtareas,id';
            $rules['grupo_id'] = 'required|numeric|integer|exists:grupos,id';
            $rules['tipo_alimentacion_id'] = 'required|numeric|integer|exists:sub_detalle_viatico,id';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'alimentacion_grupos' => 'Debe agregar al menos un registro.',
        ];
    }

    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->existenItemsIguales()) $validator->errors()->add('422', 'Verifique que no se repita la misma alimentación para un mismo grupo.');

            foreach ($this->alimentacion_grupos as $alimentacion) {
                $grupo = Grupo::find($alimentacion['grupo_id'])->nombre;
                $tipo_alimentacion = SubDetalleViatico::find($alimentacion['tipo_alimentacion_id'])->descripcion;

                $existe = AlimentacionGrupo::where('fecha', Carbon::now()->format('Y-m-d'))->where('grupo_id', $alimentacion['grupo_id'])->where('tipo_alimentacion_id', $alimentacion['tipo_alimentacion_id'])->exists();
                if ($existe) $validator->errors()->add('422', 'Ya se ha registrado ' . $tipo_alimentacion . ' para el grupo ' . $grupo . ' en la fecha seleccionada.');
            }
        });
    }

    // Todos los items deben de ser diferentes sino se da el caso de un grupo con dos almuerzos en el dia
    private function existenItemsIguales()
    {
        $counts = collect($this->alimentacion_grupos)->countBy(function ($item) {
            return $item['grupo_id'] . '-' . $item['tipo_alimentacion_id'];
        });

        return $counts->some(function ($count) {
            return $count > 1;
        });
    }
}
