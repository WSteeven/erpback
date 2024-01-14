<?php

namespace App\Http\Requests\Tareas;

use Illuminate\Foundation\Http\FormRequest;

class SubCentroCostoRequest extends FormRequest
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
            'nombre' => 'required',
            'centro_costo' => 'required|exists:tar_centros_costos,id',
            'activo' => 'boolean',
        ];
    }
}
