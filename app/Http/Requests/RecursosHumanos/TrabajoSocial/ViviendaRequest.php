<?php

namespace App\Http\Requests\RecursosHumanos\TrabajoSocial;

use Illuminate\Foundation\Http\FormRequest;

class ViviendaRequest extends FormRequest
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
            'tipo' => 'required|string',
            'numero_plantas' => 'required|string',
            'material_paredes' => 'required|string',
            'material_techo' => 'required|string',
            'material_piso' => 'required|string',
            'distribucion_vivienda' => 'required|array',
            'comodidad_espacio_familiar' => 'required|string',
            'numero_personas' => 'required|integer',
            'numero_dormitorios' => 'required|integer',
            'existe_hacinamiento' => 'boolean',
            'existe_upc_cercano' => 'boolean',
            'tiene_donde_evacuar' => 'boolean',
            'otras_consideraciones' => 'nullable|string',
            'imagen_croquis' => 'nullable|string',
            'telefono' => 'nullable|string',
            'coordenadas' => 'required|string',
            'direccion' => 'required|string',
            'referencia' => 'required|string',
            'servicios_basicos' => 'required|array',
            'servicios_basicos.luz' => 'required|string',
            'servicios_basicos.agua' => 'required|string',
            'servicios_basicos.telefono' => 'required|string',
            'servicios_basicos.internet' => 'required|string',
            'servicios_basicos.cable' => 'required|string',
            'servicios_basicos.servicios_sanitarios' => 'required|string',

            'familia_acogiente' => 'nullable|required_if_accepted:tiene_donde_evacuar|array',
            'familia_acogiente.canton' => 'nullable|required_if_accepted:tiene_donde_evacuar|exists:cantones,id',
            'familia_acogiente.parroquia' => 'nullable|required_if_accepted:tiene_donde_evacuar|exists:parroquias,id',
            'familia_acogiente.tipo_parroquia' => 'nullable|required_if_accepted:tiene_donde_evacuar|string',
            'familia_acogiente.direccion' => 'nullable|required_if_accepted:tiene_donde_evacuar|string',
            'familia_acogiente.referencia' => 'nullable|required_if_accepted:tiene_donde_evacuar|string',
            'familia_acogiente.coordenadas' => 'nullable|required_if_accepted:tiene_donde_evacuar|string',
            'familia_acogiente.nombres_apellidos' => 'nullable|required_if_accepted:tiene_donde_evacuar|string',
            'familia_acogiente.telefono' => 'nullable|required_if_accepted:tiene_donde_evacuar|string',

//            posibles amenazas
            'amenaza_inundacion' => 'array|required',
            'amenaza_deslaves' => 'array|required',
            'otras_amenazas_previstas' => 'array|required',
            'otras_amenazas' => 'nullable|string',
            'existe_peligro_tsunami' => 'boolean',
            'existe_peligro_lahares' => 'boolean',
        ];
    }

    public function prepareForValidation()
    {
//        $this->merge([
//            'familia_acogiente.provincia_id' => $this->familia_acogiente['provincia'],
//            'familia_acogiente.canton_id' => $this->familia_acogiente['canton'],
//            'familia_acogiente.parroquia_id' => $this->familia_acogiente['parroquia'],
//        ]);
//        $this->merge([
//            'familia_acogiente.provincia_id' => Arr::get($this->input('familia_acogiente', []), 'provincia'),
//            'familia_acogiente.canton_id' => Arr::get($this->input('familia_acogiente', []), 'canton'),
//            'familia_acogiente.parroquia_id' => Arr::get($this->input('familia_acogiente', []), 'parroquia'),
//        ]);
    }
}
