<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TipoTrabajoRequest extends FormRequest
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
            'cliente_id' => 'required|exists:clientes,id',
            'descripcion' => 'required|string',
            'activo' => 'required|boolean',
            'url_plantilla' => 'nullable|file|mimes:doc,docx',
        ];
    }


    protected function prepareForValidation()
    {
        $this->merge([
            'cliente_id'=>$this->cliente,
            'activo'=>boolval($this->activo),
        ]);
    }
}
