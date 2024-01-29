<?php

namespace App\Http\Requests\Ventas;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Http;
use Src\Shared\ValidarIdentificacion;

class ClienteClaroRequest extends FormRequest
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
            'identificacion' => 'required|unique:ventas_clientes_claro,id',
            'supervisor' => 'required|exists:empleados,id',
            'nombres' => 'required',
            'apellidos' => 'required',
            'direccion' => 'required',
            'telefono1' => 'required',
            'telefono2' => 'nullable',
            'activo' => 'boolean',
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $validador = new ValidarIdentificacion();
            if (strlen($this->identificacion) === 13) {
                //aquí se valida el RUC recibido
                $existeRUC = Http::get('https://srienlinea.sri.gob.ec/sri-catastro-sujeto-servicio-internet/rest/ConsolidadoContribuyente/existePorNumeroRuc?numeroRuc=' . $this->identificacion);
                if (!($existeRUC->body() == 'true')) $validator->errors()->add('identificacion', 'El RUC ingresado no pudo ser validado, revisa que sea un RUC válido');
            } else {
                // aqui se valida la cedula recibida
                if (!$validador->validarCedula($this->identificacion)) $validator->errors()->add('identificacion', 'La identificación no pudo ser validada, verifica que sea una cédula válida');
            }
        });
    }
}
