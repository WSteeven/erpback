<?php

namespace App\Http\Requests;

//use App\Models\Emergencia;
use App\Models\Subtarea;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmergenciaRequest extends FormRequest
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
            'trabajo_realizado' => 'nullable|array',
            'observaciones' => 'nullable|array',
            'materiales_ocupados' => 'nullable|array',
            'materiales_stock_ocupados' => 'nullable|array',
            'materiales_devolucion' => 'nullable|array',
            'subtarea' => 'required|numeric|integer',
        ];

        /* if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $id = $this->route()->parameter('id');
            $subtarea = Subtarea::where('emergencia_id', $id)->first();

            $rules['subtarea'] = [Rule::unique('subtareas')->ignore($subtarea->id)];
        } */

        return $rules;
    }
}
