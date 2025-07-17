<?php

namespace App\Http\Requests\Seguridad;

use Illuminate\Foundation\Http\FormRequest;

class ReporteAlimentacionRequest extends FormRequest
{
    public function rules()
    {
        return [
            'empleado' => 'nullable|exists:empleados,id',
            'zona' => 'nullable|exists:zonas,id',
            'jornada' => 'nullable|in:DIURNA,NOCTURNA',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'accion' => 'nullable|in:consulta,excel,pdf',
        ];
    }
}
