<?php

namespace App\Http\Requests;

use App\Models\FondosRotativos\Saldo\EstadoAcreditaciones;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class AcreditacionRequest extends FormRequest
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
            'fecha' => 'required',
            'monto' => 'required|numeric',
            'id_saldo' => 'required',
            'id_tipo_fondo' => 'required',
            'id_tipo_saldo' => 'required',
            'id_usuario' => 'required',
            'descripcion_acreditacion' => 'required',
            'id_estado' => 'required',
        ];
    }
    protected function prepareForValidation()
    {
//        $date = Carbon::now();
        $this->merge([
//            'fecha' =>  $date->format('Y-m-d'),
            'id_tipo_fondo' =>  $this->tipo_fondo,
            'id_tipo_saldo' =>  $this->tipo_saldo,
            'id_usuario' =>  $this->usuario,
            'monto' => round( $this->monto, 2)
        ]);
        if ($this->route()->getActionMethod() === 'store') {
            $this->merge([
                'id_estado' =>  EstadoAcreditaciones::REALIZADO,
            ]);
        }
    }
}
