<?php

namespace App\Http\Requests;

use App\Models\User;
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
        $usuario = Auth::user();
        $usuario_ac = User::where('id', $usuario->id)->first();
        if ($usuario_ac->hasRole('GERENTE') || $usuario_ac->hasRole('RECURSOS HUMANOS')) {
            $rules = [
                'fecha' => 'required|date_format:Y-m-d',
                'solicitante' => 'required|numeric',
                'monto' => 'required|numeric',
                'plazo' => 'nullable|numeric',
                'periodo_id' => 'nullable|exists:periodos,id',
                'valor_utilidad' => 'nullable|numeric',
                'motivo' => 'nullable|string',
                'foto' => 'nullable|string',
                 'estado' => 'required|numeric',
                'observacion' => 'required|string',
            ];
        } else {
            $rules = [
                'fecha' => 'required|date_format:Y-m-d',
                'solicitante' => 'required|numeric',
                'monto' => 'required|numeric',
                'motivo' => 'nullable|string',
                'periodo_id' => 'nullable|exists:periodos,id',
                'valor_utilidad' => 'nullable|numeric',
                'foto' => 'nullable|string',
                'estado' => 'required|numeric',
            ];
        }
        return $rules;
    }
    protected function prepareForValidation()
    {
        $fecha = Carbon::createFromFormat('d-m-Y', $this->fecha);
        $this->merge([
            'solicitante' => $this->solicitante != null ? $this->solicitante : Auth::user()->empleado->id,
            'fecha' => $fecha->format('Y-m-d'),
            'estado' => $this->estado == null ? 1 : $this->estado
        ]);
        if ($this->periodo != null) {
            $this->merge([
                'periodo_id' => $this->periodo,
            ]);
        }
    }
}
