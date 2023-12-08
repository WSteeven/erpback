<?php

namespace App\Http\Requests;

use App\Models\Producto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductoRequest extends FormRequest
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
            'nombre' => 'required|string|unique:productos',
            'categoria' => 'required|exists:categorias,id',
            'unidad_medida' => 'required|exists:unidades_medidas,id',
            'tipo' => ['required', Rule::in([Producto::BIEN, Producto::SERVICIO])]
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $producto = $this->route()->parameter('producto');

            $rules['nombre'] = ['required', 'string', Rule::unique('productos')->ignore($producto)];
        }

        return $rules;
    }
}
