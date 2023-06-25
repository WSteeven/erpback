<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SolicitudPrestamoEmpresarialRequest extends FormRequest
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
            'fecha' => 'required|date_format:Y-m-d',
            'solicitante'=>'required|numeric',
            'monto' => 'required|numeric',
            'plazo' => 'nullable|string',
            'motivo' => 'nullable|string',
            'foto' => 'nullable|string',
            'estado' => 'required|numeric',
            'observacion' => 'required|string',
        ];
    }
    protected function prepareForValidation()
    {
        $fecha = Carbon::createFromFormat('d-m-Y', $this->fecha);
        $this->merge([
            'solicitante'=> Auth::user()->empleado->id,
            'fecha'=>$fecha->format('Y-m-d'),
            'estado' =>$this->estado==null? 1:$this->estado
        ]);
    }

}
