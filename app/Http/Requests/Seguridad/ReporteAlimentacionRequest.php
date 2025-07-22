<?php

namespace App\Http\Requests\Seguridad;

use Illuminate\Foundation\Http\FormRequest;

class ReporteAlimentacionRequest extends FormRequest
{
    public function rules()
    {
        return [
            'empleado' => 'required_without_all:zona,jornada|nullable|exists:empleados,id',
            'zona' => 'nullable|exists:seg_zonas,id',
            'jornada' => 'nullable|in:DIURNA,NOCTURNA',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'accion' => 'nullable|in:consulta,excel,pdf',
        ];
    }


    public function messages()
    {
        return [
            'empleado.required' => 'Debe seleccionar un guardia.',
            'empleado.exists' => 'El guardia seleccionado no existe.',
            'zona.exists' => 'La zona seleccionada no es válida.',
            'jornada.in' => 'La jornada debe ser DIURNA o NOCTURNA.',
            'fecha_inicio.required' => 'Debe ingresar la fecha de inicio.',
            'fecha_fin.required' => 'Debe ingresar la fecha fin.',
            'fecha_fin.after_or_equal' => 'La fecha fin debe ser igual o posterior a la fecha inicio.',
            'accion.in' => 'Acción inválida. Debe ser consulta, excel o pdf.',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
