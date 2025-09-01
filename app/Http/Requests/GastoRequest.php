<?php

namespace App\Http\Requests;

use App\Models\FondosRotativos\Gasto\Gasto;
use App\Models\RecursosHumanos\EmpleadoDelegado;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;
use Src\App\EmpleadoService;
use Src\App\FondosRotativos\GastoValidatorService;

class GastoRequest extends FormRequest
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
        $rules = $this->baseRules();

        if (!is_null($this->vehiculo) || $this->es_vehiculo_alquilado) {
            $rules = array_merge($rules, $this->vehiculoExtraRules());
        }
        return array_merge($rules, $this->valijaRules());
    }

    private function baseRules(): array
    {
        return [
            'fecha_viat' => 'required|date_format:Y-m-d',
            'id_lugar' => 'required',
            'beneficiarios' => 'nullable',
            'ruc' => 'nullable|string',
            'factura' => 'nullable|string|max:30|min:13',
            'num_comprobante' => 'nullable|string|max:13',
            'aut_especial' => 'required',
            'detalle' => 'required|exists:detalle_viatico,id',
            'sub_detalle' => 'required|array',
            'cantidad' => 'required|numeric',
            'valor_u' => 'required|numeric',
            'total' => 'required|numeric',
            'observacion' => 'required|string',
            'comprobante' => 'required|string',
            'comprobante2' => 'required|string',
            'detalle_estado' => 'nullable|string',
            'nodo_id' => 'nullable|exists:tar_nodos,id',
            'cliente_id' => 'required|exists:clientes,id',
            'id_tarea' => 'nullable',
            'id_proyecto' => 'nullable',
            'id_usuario' => 'required|exists:empleados,id',
            'observacion_anulacion' => 'nullable',
            'estado' => 'required',
        ];
    }

    private function valijaRules(): array
    {
        if ($this->route()->uri() !== 'api/fondos-rotativos/aprobar-gasto') {
            return [
                'envio_valija' => 'sometimes|array',
                'envio_valija.courier' => 'sometimes|string',
                'envio_valija.fotografia_guia' => 'sometimes|string',
                'registros_valijas' => 'sometimes|array',
                'registros_valijas.*.departamento_id' => 'sometimes|nullable|exists:departamentos,id',
                'registros_valijas.*.descripcion' => 'required|string',
                'registros_valijas.*.destinatario_id' => 'sometimes|nullable|exists:empleados,id',
                'registros_valijas.*.imagen_evidencia' => 'required|string'
            ];
        }
        return [];
    }

    private function vehiculoExtraRules(): array
    {
        return [
            'comprobante3' => 'nullable|sometimes|string',
            'comprobante4' => 'nullable|sometimes|string',
            'es_vehiculo_alquilado' => 'boolean',
            'vehiculo' => 'required_if:es_vehiculo_alquilado,false|nullable|integer',
            'placa' => 'required_if:es_vehiculo_alquilado,true|nullable|string',
            'kilometraje' => 'required|integer',
        ];
    }

    /**
     * Esto se ejecuta despues de validar
     * Configure the validator instance.
     *
     * @param Validator $validator
     * @return void
     */
    public function withValidator(Validator $validator)
    {
        $validatorService = new GastoValidatorService();
        $validator->after(function ($validator) use ($validatorService) {
            try {
                match ($this->route()->getActionMethod()) {
                    'store' => $validatorService->validarStore($validator, $this),
                    'update' => $validatorService->validarUpdate($validator, $this),
                    'aprobarGasto' => $validatorService->validarAprobacion($validator, $this),
                    default => null
                };
            } catch (Exception $e) {
                throw ValidationException::withMessages([
                    'error' => "Error: {$e->getMessage()} en línea {$e->getLine()}"]);
            }
        });
    }


    /**
     * Esto se ejecuta antes de validar
     */
    protected function prepareForValidation()
    {
        $controller_method = $this->route()->getActionMethod();
        $date_viat = Carbon::createFromFormat('Y-m-d', $this->fecha_viat);
        if (!is_null($this->factura))
            $this->merge([
                'factura' => str_replace('_', ' ', $this->factura),
            ]);
        $this->merge([
            'fecha_viat' => $date_viat->format('Y-m-d'),
            'comprobante' => $this->comprobante1,
        ]);

        if ($controller_method === 'store') {
            $this->merge([
                'aut_especial' => $this->aut_especial ?? Auth::user()->empleado->jefe_id,
                'id_usuario' => $this->id_usuario ? $this->id_usuario : Auth::user()->empleado->id,
                'estado' => Gasto::PENDIENTE
            ]);
        }
        if ($controller_method === 'aprobarGasto') {
            $this->merge([
                'estado' => Gasto::APROBADO
            ]);
        }
        $this->merge([
            'ruc' => $this->ruc ?? '9999999999999',
        ]);

        $this->merge([
            'kilometraje' => $this->kilometraje ?: 0,
        ]);

        $this->merge([
            'id_tarea' => $this->num_tarea !== 0 ? $this->num_tarea : null,
            'id_proyecto' => $this->proyecto !== 0 ? $this->proyecto : null,
            'id_lugar' => $this->lugar,
        ]);

        // Redireccionar aprobación de gastos creados por personas que tienen configurado un AutorizadorDirecto
        $this->merge([
            'aut_especial' => EmpleadoService::obtenerAutorizadorDirecto($this->id_usuario, $this->aut_especial)
        ]);

        // Colocar el autorizador al delegado
        if ($controller_method == 'store') {
            $this->merge([
                'aut_especial' => EmpleadoDelegado::obtenerDelegado($this->aut_especial)
            ]);
        }

        $this->merge([
            'nodo_id' => $this->nodo ?: null,
            'cliente_id' => $this->cliente ?? $this->cliente_id,
        ]);

        // aqui va la parte de valija
        if (is_array($this->registros_valijas)) {
            $valijas = array_map(function ($item) {
                return [
                    'departamento_id' => $item['departamento'] ?? null,
                    'descripcion' => $item['descripcion'] ?? null,
                    'destinatario_id' => $item['destinatario'] ?? null,
                    'imagen_evidencia' => $item['imagen_evidencia'] ?? null,
                ];
            }, $this->registros_valijas);

            $this->merge([
                'registros_valijas' => $valijas
            ]);
        }
    }
}
