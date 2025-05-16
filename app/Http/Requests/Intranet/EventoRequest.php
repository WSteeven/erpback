<?php

namespace App\Http\Requests\Intranet;

use Illuminate\Foundation\Http\FormRequest;

class EventoRequest extends FormRequest
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
            'titulo'=>'required|string',
            'tipo_evento_id'=>'required|exists:intra_tipos_eventos,id',
            'anfitrion_id'=>'required|exists:empleados,id',
            'descripcion'=>'required|string',
            'fecha_hora_inicio'=>'required|string',
            'fecha_hora_fin'=>'required|string',
            'es_editable'=>'boolean',
            'es_personalizado'=>'boolean',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'tipo_evento_id'=>$this->tipo_evento,
            // 'anfitrion_id'=>$this->anfitrion,
            'anfitrion_id'=>auth()->user()->empleado->id,
        ]);
    }
}
