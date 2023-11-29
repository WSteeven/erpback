<?php

namespace App\Http\Requests\Tareas;

use App\Models\Proyecto;
use Illuminate\Foundation\Http\FormRequest;

class EtapaRequest extends FormRequest
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
            'nombre'=>'string|required',
            'responsable'=>'required|exists:empleados,id',
            'proyecto'=>'required|exists:proyectos,id',
        ];
    }

    public function prepareForValidation(){
        if($this->responsable ==''||is_null($this->responsable)){
            $proyecto = Proyecto::find($this->proyecto);
            $this->merge(['responsable'=>$proyecto->coordinador_id]);
        }
    }
}
