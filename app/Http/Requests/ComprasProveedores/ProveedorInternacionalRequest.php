<?php

namespace App\Http\Requests\ComprasProveedores;

use Illuminate\Foundation\Http\FormRequest;

class ProveedorInternacionalRequest extends FormRequest
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
            'nombre' => 'string|required',
            'tipo' => 'string|sometimes|nullable',
            'ruc' => 'string|sometimes|nullable',
            'pais_id' => 'integer|required|exists:paises,id',
            'direccion' => 'string|sometimes|nullable',
            'telefono' => 'string|sometimes|nullable',
            'correo' => 'sometimes|nullable|email:rfc,dns',
            'sitio_web' => 'string|sometimes|nullable',
            'banco1' => 'string|sometimes|nullable',
            'numero_cuenta1' => 'string|sometimes|nullable',
            'codigo_swift1' => 'string|sometimes|nullable',
            'moneda1' => 'string|sometimes|nullable',
            'banco2' => 'string|sometimes|nullable',
            'numero_cuenta2' => 'string|sometimes|nullable',
            'codigo_swift2' => 'string|sometimes|nullable',
            'moneda2' => 'string|sometimes|nullable',
            'activo'=>'boolean',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'pais_id' => $this->pais,
        ]);
    }
}
