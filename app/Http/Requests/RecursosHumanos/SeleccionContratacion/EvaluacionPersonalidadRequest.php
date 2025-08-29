<?php

namespace App\Http\Requests\RecursosHumanos\SeleccionContratacion;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Src\App\RecursosHumanos\SeleccionContratacion\PostulacionService;
use Src\Shared\ObtenerInstanciaUsuario;

class EvaluacionPersonalidadRequest extends FormRequest
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
            'postulacion_id' => 'required|exists:rrhh_contratacion_postulaciones,id',
            'respuestas' => 'required|array',
            'fecha_realizacion' => 'required|string',
            'completado' => 'boolean',
            'user_id' => 'required|integer',
            'user_type' => 'required|string',
            'observacion' => [
                'nullable',
                'string',
                Rule::requiredIf($this->route()->getActionMethod() === 'update'),
            ],
        ];
    }

    protected function prepareForValidation()
    {
        [$user_id, $user_type, $user] = ObtenerInstanciaUsuario::tipoUsuario();
        $this->merge([
            'postulacion_id' => $this->postulacion ?? PostulacionService::obtenerIdPostulacionByToken($this->token),
            'fecha_realizacion' => $this->fecha_realizacion ?? Carbon::now()->format('Y-m-d'),
            'user_id' => $user_id,
            'user_type' => $user_type,
            'completado' => !in_array(null, $this->respuestas, true),
        ]);
    }
}
