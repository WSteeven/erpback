<?php

namespace App\Http\Requests\FondosRotativos\Saldo;

use Illuminate\Foundation\Http\FormRequest;

class AcreditacionSemanaRequest extends FormRequest
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
            'semana' =>     'required|string' . $this->parroquia_id,
            'acreditar' => 'boolean',
        ];
    }
}
