<?php

namespace App\Http\Requests\ComprasProveedores;

use App\Models\Departamento;
use Illuminate\Foundation\Http\FormRequest;

class CriterioCalificacionRequest extends FormRequest
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
            'nombre' => 'required|string',
            'descripcion' => 'required|string',
            'ponderacion_referencia' => 'required|numeric',
            'departamento' => 'required|exists:departamentos,id',
            'oferta' => 'required|exists:ofertas_proveedores,id',
        ];
    }
    
    public function prepareForValidation(){
        $departamento = Departamento::where('responsable_id', auth()->user()->empleado->id)->first();
        if($departamento){
            $this->merge([
                'departamento' => $departamento->id
            ]);
        }
    }
}
