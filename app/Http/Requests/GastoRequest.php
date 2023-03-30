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
            if($this->comprobante1 == $this->comprobante2)
            {
                $validator->errors()->add('comprobante1', 'Los comprobantes no pueden ser del mismo lado, por favor cambie uno de los dos');
            }

            $factura = Gasto::where('ruc', $this->ruc)
            ->where('factura',$this->factura)
            ->where('estado',3)
            ->first();
            if ($factura) {
                $validator->errors()->add('ruc', 'El número de factura ya se encuentra registrado');
            }
           $comprobante = Gasto::whereNotNull('num_comprobante')->where('num_comprobante',$this->num_comprobante)->first();
            if ($comprobante) {
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
            'fecha_viat' =>  $date_viat->format('Y-m-d'),
        ]);
        if(is_null($this->aut_especial)){
            $id_jefe = $this->obtener_usuario(Auth::user()->empleado->jefe_id)->id;
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
    protected function obtener_usuario($id){
        $user = User::find($id);
        return $user;
    }
}
