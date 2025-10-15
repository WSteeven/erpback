<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TareaRequest extends FormRequest
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
            'codigo_tarea_cliente' => 'nullable|string',
            'fecha_solicitud' => 'nullable|string',
            'titulo' => 'required|string',
            'observacion' => 'nullable|string',
            'novedad' => 'nullable|string',
            'para_cliente_proyecto' => 'required|string',
            'ubicacion_trabajo' => 'nullable|string',
            'cliente' => 'required|numeric|integer',
            'coordinador' =>  'nullable|numeric|integer',
            'fiscalizador' => 'nullable|numeric|integer',
            'proyecto' => 'nullable|numeric|integer',
            'etapa' => 'nullable|numeric|integer',
            'cliente_final' => 'nullable|numeric|integer',
            'medio_notificacion' => 'required|string',
            'tiene_subtareas' => 'required|boolean',
            'subtarea' => 'nullable',
            'ruta_tarea' => 'nullable|numeric|integer',
            'finalizado' => 'nullable|boolean',
            'metraje_tendido' => 'nullable|numeric|integer',
            'centro_costo' => 'nullable|numeric|integer|exists:tar_centros_costos,id',
        ];

        // Verifica si el usuario actual tiene el rol específico
        if (Auth::check() && Auth::user()->hasRole([User::ROL_COORDINADOR_BACKUP, User::ROL_JEFE_TECNICO, USer::ROL_SUPERVISOR_TECNICO, User::ROL_ADMINISTRADOR])) {
//            Log::channel('testing')->info('Log', ['Validacion Tarea:', 'Dentro del if']);
            // Agrega una regla de validación para marcar el campo como requerido
            $rules['coordinador'] = 'required|numeric|integer';
        }

//        Log::channel('testing')->info('Log', compact('rules'));
        return $rules;
    }
}
