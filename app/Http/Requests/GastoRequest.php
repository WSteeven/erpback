<?php

namespace App\Http\Requests;

use App\Models\FondosRotativos\Gasto\DetalleViatico;
use App\Models\FondosRotativos\Gasto\Gasto;
use App\Models\RecursosHumanos\EmpleadoDelegado;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;
use Src\App\EmpleadoService;
use Src\Config\PaisesOperaciones;
use Src\Shared\ValidarIdentificacion;

class GastoRequest extends FormRequest
{
    private ?string $pais;
    private int $id_wellington;
    private int $id_isabel;
    private int $id_vanessa;

    public function __construct()
    {
        $this->pais = config('app.pais');
        $this->id_wellington = 117;
        $this->id_isabel = 10;
//        $this->id_vanessa = 11;
    }

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
            'id_tarea' => 'nullable',
            'id_proyecto' => 'nullable',
            'id_usuario' => 'required|exists:empleados,id',
            'observacion_anulacion' => 'nullable',
            'estado' => 'required'
        ];
        if (!is_null($this->vehiculo) || $this->es_vehiculo_alquilado) {
            $rules = [
                'fecha_viat' => 'required|date_format:Y-m-d',
                'id_lugar' => 'required',
                'subTarea' => 'nullable',
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
                'es_vehiculo_alquilado' => 'boolean',
                'vehiculo' => $this->es_vehiculo_alquilado ? 'nullable' : 'required|integer',
                'placa' => $this->es_vehiculo_alquilado ? 'required|string' : 'nullable',
                'kilometraje' => 'required|integer',
                'id_tarea' => 'nullable',
                'id_proyecto' => 'nullable',
                'id_usuario' => 'required|exists:empleados,id',
                'observacion_anulacion' => 'nullable',
                'estado' => 'required'
            ];
        }
        return $rules;
    }

    /**
     * Esto se ejecuta despues de validar
     * Configure the validator instance.
     *
     * @param Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            try {
                if ($this->route()->getActionMethod() === 'store') {
                    $this->validarNumeroComprobante($validator);
                    $this->comprobarRuc($validator, $this->ruc);
                    // $this->comprobarTiempo($validator);
                }
                if ($this->route()->getActionMethod() === 'aprobarGasto') {
                    $gasto = Gasto::where('id', $this->id)->lockForUpdate()->first();
                    $estado = $gasto?->estado;
                    if ($estado == Gasto::APROBADO) {
                        $validator->errors()->add('estado', 'El gasto ya fue aprobado');
                    }
                    if ($gasto?->ruc !== $this->ruc) {
                        $this->comprobarRuc($validator, $this->ruc);
                    }
                }
                if ($this->route()->getActionMethod() === 'update') {
                    $gasto = Gasto::where('id', $this->id)->lockForUpdate()->first();
                    if ($gasto?->ruc !== $this->ruc) {
                        $this->comprobarRuc($validator, $this->ruc);
                    }
                }
            } catch (Exception $e) {
                throw ValidationException::withMessages(['Error al validar gasto' => $e->getMessage(), $e->getLine()]);
            }
        });
    }
    /* private function comprobarTiempo($validator)
     {
         $hora_limite = '15:00:00';
         $fechaActual = Carbon::now();
         $ultimoDiaMes = $fechaActual->copy()->endOfMonth();
         $fecha_limite = $ultimoDiaMes->format('d-m-Y');
         if ($fechaActual->isSameDay($ultimoDiaMes)) {
             if ($fechaActual->format('H:i:s') > $hora_limite) {
                 $validator->errors()->add('hora_limite', 'Solo se podrán subir facturas para aprobación hasta ' . $fecha_limite . ' ' . $hora_limite);
             }
         }
     }*/


    /**
     * @throws Exception
     */
    private function comprobarRuc(Validator $validator, $ruc)
    {
        if (substr_count($ruc, '9') < 9) {
            $validador = new ValidarIdentificacion();
            $existe_ruc = $validador->validarRUCSRI($ruc);
            if (!($validador->validarCedula($ruc) || $existe_ruc)) {
                $validator->errors()->add('ruc', 'La identificación no pudo ser validada, revisa que sea una cédula/RUC válido');
            }
        }
    }

    /**
     * @throws Exception
     */
    public function validarNumeroComprobante($validator)
    {
        $factura = Gasto::where('factura', '!=', null)
            ->where('factura', '!=', '')
            ->where('ruc', $this->ruc)
            ->where('factura', $this->factura)
            ->where('estado', Gasto::APROBADO)
            ->lockForUpdate()
            ->first();
        $factura_pendiente = Gasto::where('factura', '!=', null)
            ->where('factura', '!=', '')
            ->where('ruc', $this->ruc)
            ->where('factura', $this->factura)
            ->where('estado', Gasto::PENDIENTE)
            ->lockForUpdate()
            ->first();
        if ($factura) {
            $validator->errors()->add('ruc', 'El número de factura ya se encuentra registrado');
        }
        if ($factura_pendiente) {
            $validator->errors()->add('ruc', 'El número de factura ya se encuentra registrado');
        }
        $comprobante = Gasto::where('num_comprobante', '!=', null)
            ->where('num_comprobante', $this->num_comprobante)
            ->where('estado', Gasto::APROBADO)
            ->lockForUpdate()
            ->first();
        if ($comprobante) {
            $validator->errors()->add('num_comprobante', 'El número de comprobante ya se encuentra registrado');
        }
        $comprobante_pendiente = Gasto::where('num_comprobante', '!=', null)
            ->where('num_comprobante', $this->num_comprobante)
            ->where('estado', Gasto::PENDIENTE)
            ->lockForUpdate()
            ->first();
        if ($comprobante_pendiente) {
            $validator->errors()->add('num_comprobante', 'El número de comprobante ya se encuentra registrado');
        }
        if ($this->factura !== null) {
            switch ($this->pais) {
                case PaisesOperaciones::PERU:
                    return true;
                default:
                    $numFacturaObjeto = [
                        [
                            "detalle" => DetalleViatico::PEAJE,
                            "cantidad" => 22,
                        ],
                        [
                            "detalle" => DetalleViatico::ENVIO_ENCOMIENDA,
                            "cantidad" => 17,
                        ],
                    ];
                    $index = array_search($this->detalle, array_column($numFacturaObjeto, 'detalle'));
                    $cantidad = ($index !== false && isset($numFacturaObjeto[$index])) ? $numFacturaObjeto[$index]['cantidad'] : 15;
                    $num_fact = str_replace(' ', '', $this->factura);
                    if (!!$this->factura) {
                        if ($this->detalle == DetalleViatico::PEAJE) {
                            if (strlen($num_fact) < $cantidad || strlen($num_fact) < 15) {
                                throw new Exception('El número de dígitos en la factura es insuficiente. Por favor, ingrese al menos ' . max($cantidad, 15) . ' dígitos en la factura.');
                            }
                        } else {
                            if (strlen($num_fact) < $cantidad) {
                                throw new Exception('El número de dígitos en la factura es insuficiente. Por favor, ingrese al menos ' . max($cantidad, 15) . ' dígitos en la factura.');
                            }
                        }
                    }
            }
        }
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
        ]);
        $this->merge([
            'comprobante' => $this->comprobante1,
        ]);
        if ($this->route()->getActionMethod() === 'store') {

            if (is_null($this->aut_especial)) {
                $id_jefe = Auth::user()->empleado->jefe_id;
//                if ($id_jefe == $this->id_wellington) $id_jefe = $this->id_vanessa;
                if ($id_jefe == $this->id_wellington) $id_jefe = $this->id_isabel;
                $this->merge([
                    'aut_especial' => $id_jefe,
                ]);
            }
            $this->merge([
                'id_usuario' => $this->id_usuario ? $this->id_usuario : Auth::user()->empleado->id,
                'estado' => Gasto::PENDIENTE
            ]);
        }
        if ($this->route()->getActionMethod() === 'aprobarGasto') {
            $this->merge([
                'estado' => Gasto::APROBADO
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
        $this->merge([
            'factura' => str_replace('_', ' ', $this->factura),
        ]);
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

        // Redireccionar aprobación de gastos creados por personas que tienen configurado un AutorizadorDirecto
        $this->merge([
            'aut_especial' => EmpleadoService::obtenerAutorizadorDirecto($this->id_usuario, $this->aut_especial)
        ]);

        // Colocar el autorizador al delegado
        if($controller_method == 'store'){
            $this->merge([
               'aut_especial' => EmpleadoDelegado::obtenerDelegado($this->aut_especial)
            ]);
        }
    }
}
