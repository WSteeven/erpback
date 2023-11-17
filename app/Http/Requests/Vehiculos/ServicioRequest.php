<?php

namespace App\Http\Requests\Vehiculos;

use App\Models\Vehiculos\Servicio;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServicioRequest extends FormRequest
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
            'nombre' => 'required|string|unique:veh_servicios,nombre',
            'tipo' => ['string', 'required', Rule::in(Servicio::PREVENTIVO, Servicio::CORRECTIVO)],
            'intervalo' => 'nullable|numeric',
            'estado' => 'boolean',
        ];
    }
}
