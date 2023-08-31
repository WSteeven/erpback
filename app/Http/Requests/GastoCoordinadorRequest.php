<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class GastoCoordinadorRequest extends FormRequest
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
            'fecha_gasto' => 'required|date_format:Y-m-d',
            'lugar' => 'required|integer',
            'monto' => 'required|numeric',
            'grupo' => 'required|integer',
            'observacion' => 'required|string',
            'id_usuario' => 'required|integer',
        ];
    }
    protected function prepareForValidation()
    {
        $user =Auth::user()->empleado->id;
        $this->merge([
            'fecha_gasto' =>  date('Y-m-d'),
            'id_usuario' => $user,
        ]);

    }

}
