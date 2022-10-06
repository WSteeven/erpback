<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpanRequest extends FormRequest
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
            'nombre'=>'sometimes|numeric|unique:spans,nombre',
        ];
    }

    public function attributes()
    {
        return [
            'nombre'=>'span'
        ];
    }
}
