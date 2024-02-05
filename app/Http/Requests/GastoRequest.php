<?php

namespace App\Http\Requests;

use App\Models\FondosRotativos\Gasto\DetalleViatico;
use App\Models\FondosRotativos\Gasto\EstadoViatico;
use App\Models\FondosRotativos\Gasto\Gasto;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Exception;
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
        $rules = [
            'fecha_viat' => 'required|date_format:Y-m-d',
            'id_lugar' => 'required',
            'subTarea' => 'nullable',
            'beneficiarios' => 'nullable',
            'ruc' => 'nullable|string',
            'factura' => 'nullable|string|max:30|min:17',
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
            'id_tarea' => 'nullable',
            'id_proyecto' => 'nullable',
        ];
        if (!is_null($this->vehiculo)) {
            $rules = [
                'fecha_viat' => 'required|date_format:Y-m-d',
                'id_lugar' => 'required',
                'subTarea' => 'nullable',
                'beneficiarios' => 'nullable',
                'ruc' => 'nullable|string',
                'factura' => 'nullable|string|max:30|min:17',
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
                'vehiculo' => 'required|integer',
                'kilometraje' => 'required|integer',
                'id_tarea' => 'nullable',
                'id_proyecto' => 'nullable',
            ];
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
            try {
                if ($this->route()->getActionMethod() === 'store') {
                    $this->validar_numero_comprobante($validator);
                }

                if ($this->route()->getActionMethod() === 'aprobar_gasto') {
                    $gasto = Gasto::find($this->id);
                    $estado = $gasto->estado;
                    if ($estado == 1) {
                        $validator->errors()->add('estado', 'El gasto ya fue aprobado');
                    }
                }
                if (substr_count($this->ruc, '9') < 9) {
                    $validador = new ValidarIdentificacion();
                    $existeRUC = Http::get('https://srienlinea.sri.gob.ec/sri-catastro-sujeto-servicio-internet/rest/ConsolidadoContribuyente/existePorNumeroRuc?numeroRuc=' . $this->ruc);

                    if (!(($validador->validarCedula($this->ruc)) || ($existeRUC->body() == 'true'))) {
                        $validator->errors()->add('ruc', 'La identificación no pudo ser validada, revisa que sea una cédula/RUC válido');
                    }
                }
            } catch (Exception $e) {
                throw ValidationException::withMessages([
                    'Error al validar gasto' => ['No se puede validar RUC con el servicio del SRI, porfavor intentelo mas tarde'],
                ]);
            }
        });
    }

    public function validar_numero_comprobante($validator)
    {
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
            ->where('estado', Gasto::PENDIENTE)
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
        if($this->factura !== null){
            $numFacturaObjeto = [
                [
                    "detalle" => 16,
                    "cantidad" => 22,
                ],
                [
                    "detalle" => 10,
                    "cantidad" => 17,
                ],
            ];
            $index = array_search($this->detalle, array_column($numFacturaObjeto, 'detalle'));
            $cantidad = ($index !== false && isset($numFacturaObjeto[$index])) ? $numFacturaObjeto[$index]['cantidad'] : 15;
            $num_fact = str_replace(' ', '',  $this->factura);
            if ($this->detalle == 16) {
                if (strlen($num_fact) < $cantidad || strlen($num_fact) < 15) {
                    throw ValidationException::withMessages([
                        '404' => ['El número de dígitos en la factura es insuficiente. Por favor, ingrese al menos ' . max($cantidad, 15) . ' dígitos en la factura.'],
                    ]);
                }
            } else {
                if (strlen($num_fact) < $cantidad) {
                    throw ValidationException::withMessages([
                        '404' => ['El número de dígitos en la factura es insuficiente. Por favor, ingrese al menos ' . max($cantidad, 15) . ' dígitos en la factura.'],
                    ]);
                }
            }
        }


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
        $this->merge([
            'comprobante' =>  $this->comprobante1,
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
        if (is_null($this->kilometraje)) {
            $this->merge([
                'kilometraje' => 0,
            ]);
        }
        $tarea = null;
        $proyecto = null;
        if ($this->num_tarea !== 0) {
            $tarea = $this->num_tarea;
        }
        if ($this->proyecto !== 0) {
            $proyecto = $this->proyecto;
        }
        $this->merge([
            'id_tarea' => $tarea,
            'id_proyecto' => $proyecto,
            'id_lugar' => $this->lugar,
        ]);
    }
}
