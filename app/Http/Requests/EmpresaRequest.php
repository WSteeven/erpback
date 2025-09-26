<?php

namespace App\Http\Requests;

use App\Models\Empresa;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Src\Shared\ValidarIdentificacion;

class EmpresaRequest extends FormRequest
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
        $rules =  [
            'identificacion' => 'required|min:10|max:13|unique:empresas,identificacion',
            'tipo_contribuyente' => ['required', Rule::in([Empresa::NATURAL, Empresa::SOCIEDAD])],
            'razon_social' => 'string|required',
            'nombre_comercial' => 'string|nullable',
            // 'celular' => 'string|nullable',
            // 'telefono' => 'string|nullable',
            'correo' => 'email|nullable',
            'canton' => 'integer|exists:cantones,id',
            // 'ciudad' => 'string|nullable',
            'direccion' => 'string|nullable',
            'agente_retencion' => 'boolean|required',
            'regimen_tributario' => ['required', Rule::in([Empresa::RIMPE_EMPRENDEDOR, Empresa::RIMPE_NEGOCIOS_POPULARES, Empresa::GENERAL])],
            'sitio_web' => 'string|nullable',
            'lleva_contabilidad' => 'boolean|required',
            'contribuyente_especial' => 'boolean|required',
            'actividad_economica' => 'string|nullable',
            'representante_legal' => 'nullable|sometimes|required_if:tipo_contribuyente,SOCIEDAD',
            'identificacion_representante' => 'nullable|sometimes|required_if:tipo_contribuyente,SOCIEDAD',
            'antiguedad_proveedor' => 'nullable|sometimes|string',
            'es_cliente' => 'sometimes|boolean',
            'es_proveedor' => 'sometimes|boolean',
        ];
        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $empresa = $this->route()->parameter('empresa');

            $rules['identificacion'] = ['required', 'string', Rule::unique('empresas')->ignore($empresa)];
        }
        return $rules;
    }
    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
public function withValidator($validator)
{
    $validator->after(function ($validator) {
        $validador = new \Src\Shared\ValidarIdentificacion();

        try {
            $identificacion = $this->identificacion;
            $len = strlen($identificacion);

            $esValido = false;

            if ($len === 11) {
                // ===== PERÚ =====
                // Solo validación local (estructura + dígito verificador)
                $esValido = $validador->validarRUCSRI($identificacion);
            } elseif (in_array($len, [10, 13])) {
                // ===== ECUADOR =====
                $existeRUC = Http::get(
                    'https://srienlinea.sri.gob.ec/sri-catastro-sujeto-servicio-internet/rest/ConsolidadoContribuyente/existePorNumeroRuc?numeroRuc=' . $identificacion
                );
                $esValido = ($existeRUC->body() === 'true');
            } else {
                // Ni RUC Perú ni cédula/RUC Ecuador
                $esValido = false;
            }

            if (!$esValido) {
                $validator->errors()->add(
                    'identificacion',
                    'La identificación no pudo ser validada, revisa que sea una cédula/RUC válido.'
                );
            }
        } catch (\Exception $e) {
            $validator->errors()->add('identificacion', $e->getMessage());
        }
    });
}


    /**
     * Personalizacion de atributos y mensajes
     */
    public function attributes()
    {
        return [
            //'identificacion'=>'cedula/ruc',
            'tipo_contribuyente' => 'contribuyente'
        ];
    }
    public function messages()
    {
        return [
            'identificacion.required' => 'Debe ingresar una identificación de cedula o ruc',
            'tipo_contribuyente.in' => 'El campo :attribute solo acepta uno de los siguientes valores: NATURAL, PRIVADA, PUBLICA',
            'tipo_negocio.in' => 'El campo :attribute solo acepta uno de los siguientes valores: RIMPE CON IVA, RIMPE SIN IVA',
        ];
    }
}
