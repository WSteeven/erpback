<?php

namespace App\Http\Requests\ComprasProveedores;

use App\Models\ComprasProveedores\CuentaBancaria;
use Illuminate\Foundation\Http\FormRequest;

class BeneficiarioRequest extends FormRequest
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
            'codigo_beneficiario' => 'required|string',
            'tipo_documento' => 'required|string|in:R,C,P',
            // 'identificacion_beneficiario' => 'required|string',
            'nombre_beneficiario' => 'required|string',
            'direccion' => 'nullable|string',
            'telefono' => 'nullable|string',
            'localidad' => 'nullable|string',
            'correo' => 'nullable|string',
            'canton_id' => 'nullable|numeric|integer|exists:cantones,id',
            'cuentas_bancarias' => 'required',
            'cuentas_bancarias.*.id' => 'nullable|numeric|integer',
            'cuentas_bancarias.*.tipo_cuenta' => 'required|string|in:' . CuentaBancaria::AHORRO . ',' .  CuentaBancaria::CORRENTE,
            'cuentas_bancarias.*.numero_cuenta' => 'required|string',
            'cuentas_bancarias.*.banco_id' => 'required|numeric|integer|exists:bancos,id',
            'identificacion_beneficiario' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $tipo = $this->input('tipo_documento');

                    if ($tipo === 'C' && !preg_match('/^\d{10}$/', $value)) {
                        return $fail('La cédula debe contener exactamente 10 dígitos numéricos.');
                    }

                    if ($tipo === 'R' && !preg_match('/^\d{13}$/', $value)) {
                        return $fail('El RUC debe contener exactamente 13 dígitos numéricos.');
                    }

                    if ($tipo === 'P' && !preg_match('/^[A-Za-z0-9]{6,20}$/', $value)) {
                        return $fail('El pasaporte debe tener entre 6 y 20 caracteres alfanuméricos.');
                    }
                }
            ],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'canton_id' => $this['canton'],
            'cuentas_bancarias' => collect($this->input('cuentas_bancarias', []))->map(function ($cuenta) {
                return [
                    'id' => $cuenta['id'] ?? null,
                    'tipo_cuenta' => $cuenta['tipo_cuenta'] ?? null,
                    'numero_cuenta' => $cuenta['numero_cuenta'] ?? null,
                    'banco_id' => $cuenta['banco'] ?? null, // Convertimos "banco" a "banco_id"
                ];
            })->toArray(),
        ]);
    }


    public function messages()
    {
        return [
            'tipo_documento.in' => 'El tipo de documento debe ser R, C o P.',
        ];
    }
}
