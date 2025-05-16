<?php

namespace App\Http\Requests\ComprasProveedores;

use App\Models\Proveedor;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @method files()
 */
class ProveedorRequest extends FormRequest
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
            'empresa_id' => 'required|exists:empresas,id',
            'sucursal' => 'required|string',
            'parroquia_id' => 'required|exists:parroquias,id',
            'direccion' => 'required|string',
            'celular' => 'nullable|string',
            'telefono' => 'nullable|string',
            'correo' => 'nullable|email',
            'estado' => 'boolean',
            //listados de relaciones muchos a muchos
            'tipos_ofrece.*' => 'required',
            'categorias_ofrece.*' => 'required',
            'departamentos.*' => 'required',
            "calificacion" => 'nullable|numeric',
            "estado_calificado" => 'required|string',

            "forma_pago" => 'array|sometimes|nullable',
            "referencia" => 'string|sometimes|nullable',
            "plazo_credito" => 'string|sometimes|nullable',
            "anticipos" => 'string|sometimes|nullable',
        ];
    }

    public function messages()
    {
        return [
            'empresa' => 'Ya existe un proveedor registrado con esta razón social'
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // aqui va toda la validacion donde se lanzan errores segun sea necesario
        });
    }
    public function prepareForValidation()
    {
        $this->merge([
           'empresa_id'=> $this->empresa,
           'parroquia_id'=> $this->parroquia,

        ]);
        if (is_null($this->celular)) {
            $this->merge(['celular' => '0999999999']);
        }
        if ($this->route()->getActionMethod() == 'store') {
            $this->merge(['calificacion' => 0.00]);
        }
        if (is_null($this->estado_calificado) || $this->estado_calificado === '') {
            $this->merge(['estado_calificado' => Proveedor::SIN_CALIFICAR]);
        }
    }
}
