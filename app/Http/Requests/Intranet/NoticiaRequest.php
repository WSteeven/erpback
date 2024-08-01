<?php

namespace App\Http\Requests\Intranet;

use Illuminate\Foundation\Http\FormRequest;

class NoticiaRequest extends FormRequest
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
            'titulo'=>'required|string',
            'descripcion'=>'required|string',
            'autor_id'=>'required|exists:empleados,id',
            'categoria_id'=>'required|exists:empleados,id',
            'etiquetas'=>'sometimes|nullable|string',
            'imagen_noticia'=>'sometimes|nullable|string',
            'fecha_vencimiento'=>'required|string',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'categoria_id'=>$this->categoria,
            // 'autor_id'=>$this->autor,
            'autor_id'=>auth()->user()->empleado->id,
        ]);
        if (count($this->etiquetas) == 0) {
            $this->merge(['etiquetas' => null]);
        } else {
            $this->merge(['etiquetas' => implode(',', $this->etiquetas)]);
        }
    }
}
