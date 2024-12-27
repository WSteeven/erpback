<?php

namespace App\Http\Requests\RecursosHumanos\TrabajoSocial;

use Illuminate\Foundation\Http\FormRequest;

class ViviendaRequest extends FormRequest
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
//            'empleado_id' => 'required|exists:empleados,id',
            'tipo' => 'required|string',
            'material_paredes' => 'required|string',
            'material_techo' => 'required|string',
            'material_piso' => 'required|string',
            'distribucion_vivienda' => 'required|array',
            'comodidad_espacio_familiar' => 'required|string',
            'numero_dormitorios' => 'required|integer',
            'existe_hacinamiento' => 'boolean',
            'existe_upc_cercano' => 'boolean',
            'otras_consideraciones' => 'nullable|string',
            'imagen_croquis' => 'nullable|string',
            'telefono' => 'nullable|string',
            'coordenadas' => 'required|string',
            'direccion' => 'required|string',
            'referencia' => 'required|string',
            'servicios_basicos' => 'required|array',
            'servicios_basicos.luz' => 'required|string',
            'servicios_basicos.agua' => 'required|string',
            'servicios_basicos.telefono' => 'required|string',
            'servicios_basicos.internet' => 'required|string',
            'servicios_basicos.cable' => 'required|string',
            'servicios_basicos.servicios_sanitarios' => 'required|string',
            'model_id',
            'model_type',
        ];
    }
}
