<?php

namespace App\Http\Requests\Intranet;

use Illuminate\Foundation\Http\FormRequest;

class EtiquetaRequest extends FormRequest
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
            'categoria_id'=>'required|exists:intra_categorias_noticias,id',
            'nombre'=>'required|string',
            'activo'=>'boolean',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'categoria_id'=>$this->categoria,
        ]);
    }
}
