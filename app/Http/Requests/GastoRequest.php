<?php

namespace App\Http\Requests;

use App\Models\FondosRotativos\Gasto\DetalleViatico;
use App\Models\FondosRotativos\Gasto\EstadoViatico;
use App\Models\FondosRotativos\Gasto\Gasto;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use PgSql\Lob;
use Psy\CodeCleaner\AssignThisVariablePass;
use Src\Shared\ValidarIdentificacion;

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
        return [
            'fecha_viat' => 'required|date_format:Y-m-d',
            'lugar' => 'required',
            'num_tarea' => 'required',
            'subTarea' => 'nullable',
            'proyecto' => 'required',
            'ruc' => 'nullable|string',
            'factura' => 'nullable|string|max:17',
            'num_comprobante' => 'nullable|string|max:13',
            'aut_especial' => 'required',
            'detalle' => 'required|exists:detalle_viatico,id',
            'sub_detalle' => 'required|array',
            'cantidad' => 'required|numeric',
            'valor_u' => 'required|numeric',
            'total' => 'required|numeric',
            'observacion' => 'required|string',
            'comprobante1' => 'required|string',
            'comprobante2' => 'required|string',
            'detalle_estado' => 'nullable|srtring',
        ];
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
            $date = Carbon::parse($this->fecha_viat);
           /* if (!$date->greaterThan('2023-03-31')) {
                $validator->errors()->add('fecha_viat', 'La fecha no puede ser menor a 2023-03-31');
            }*/
            $factura = Gasto::where('factura', '!=', null)
                ->where('factura', '!=', '')
                ->where('ruc', $this->ruc)
                ->where('factura', $this->factura)
                ->where('estado', 1)
                ->first();
            $factura_pendiente = Gasto::where('factura', '!=', null)
                ->where('factura', '!=', '')
                ->where('ruc', $this->ruc)
                ->where('factura', $this->factura)
                ->where('estado', 3)
                ->first();
            if ($factura) {
                $validator->errors()->add('ruc', 'El número de factura ya se encuentra registrado');
            }
            if ($factura_pendiente) {
                $validator->errors()->add('ruc', 'El número de factura ya se encuentra registrado');
            }
            $comprobante = Gasto::where('num_comprobante', '!=', null)
                ->where('num_comprobante', $this->num_comprobante)
                ->where('estado', 1)
                ->first();
            if ($comprobante) {
                $validator->errors()->add('num_comprobante', 'El número de comprobante ya se encuentra registrado');
            }
            $comprobante_pendiente = Gasto::where('num_comprobante', '!=', null)
                ->where('num_comprobante', $this->num_comprobante)
                ->where('estado', 3)
                ->first();
            if ($comprobante_pendiente) {
                $validator->errors()->add('num_comprobante', 'El número de comprobante ya se encuentra registrado');
            }
            if (substr_count($this->ruc, '9') < 9) {
                $validador = new ValidarIdentificacion();
                $existeRUC = Http::get('https://srienlinea.sri.gob.ec/sri-catastro-sujeto-servicio-internet/rest/ConsolidadoContribuyente/existePorNumeroRuc?numeroRuc=' . $this->ruc);
                if (!(($validador->validarCedula($this->ruc)) || ($existeRUC->body() == 'true'))) {
                    $validator->errors()->add('ruc', 'La identificación no pudo ser validada, revisa que sea una cédula/RUC válido');
                }
            }
        });
    }
    protected function prepareForValidation()
    {
        $date_viat = Carbon::createFromFormat('d-m-Y', $this->fecha_viat);
        $this->merge([
            'factura' => str_replace('_', ' ', $this->factura),
        ]);
        $this->merge([
            'fecha_viat' =>  $date_viat->format('Y-m-d'),
        ]);
        if (is_null($this->aut_especial)) {
            $id_jefe = Auth::user()->empleado->jefe_id;
            $this->merge([
                'aut_especial' => $id_jefe,
            ]);
        }
        if (is_null($this->ruc)) {
            $this->merge([
                'ruc' => '9999999999999',
            ]);
        }
    }
}
