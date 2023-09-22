<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfiguracionGeneralRequest extends FormRequest
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
            'logo_claro' => 'required|string',
            'logo_oscuro' => 'required|string',
            'logo_marca_agua' => 'required|string',
            'ruc' => 'required|string',
            'representante' => 'required|string',
            'razon_social' => 'required|string',
            'nombre_comercial' => 'required|string',
            'direccion_principal' => 'required|string',
            'telefono' => 'required|string',
            'moneda' => 'required|string',
            'tipo_contribuyente' => 'required|string',
            'celular1' => 'required|string',
            'celular2' => 'required|string',
            'correo_principal' => 'required|string',
            'correo_secundario' => 'required|string',
            'sitio_web' => 'required|string',
            'direccion_secundaria1' => 'required|string',
            'direccion_secundaria2' => 'required|string',
        ];
    }
}
