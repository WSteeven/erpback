<?php

namespace App\Http\Requests\RecursosHumanos\TrabajoSocial;

use Illuminate\Foundation\Http\FormRequest;

class FichaSocioeconomicaRequest extends FormRequest
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
        return array_merge([
            'empleado_id' => 'required|exists:empleados,id',
            'lugar_nacimiento' => 'required|string',
            'canton_id' => 'required|exists:cantones,id',
            'contacto_emergencia' => 'required|string',
            'contacto_emergencia_externo' => 'required|string',
            'parentesco_contacto_emergencia' => 'required|string',
            'parentesco_contacto_emergencia_externo' => 'required|string',
            'telefono_contacto_emergencia' => 'required|string',
            'telefono_contacto_emergencia_externo' => 'required|string',
            'ciudad_contacto_emergencia_externo' => 'required|exists:cantones,id',
            'problemas_ambiente_social_familiar' => 'required|array',
            'observaciones_ambiente_social_familiar' => 'nullable|string',
            'tiene_capacitaciones' => 'boolean',
            'conocimientos' => 'required_if_accepted:tiene_capacitaciones|array',
            'capacitaciones' => 'required_if_accepted:tiene_capacitaciones|array',
            'imagen_rutagrama' => 'nullable|string',
            'vias_transito_regular_trabajo' => 'required|string',
            'conclusiones' => 'required|string',

            //conyuge
            'conyuge.nombres' => 'required_if_accepted:tiene_conyuge|nullable|string',
            'conyuge.apellidos' => 'required_if_accepted:tiene_conyuge|nullable|string',
            'conyuge.nivel_academico' => 'required_if_accepted:tiene_conyuge|nullable|string',
            'conyuge.edad' => 'required_if_accepted:tiene_conyuge|nullable|integer',
            'conyuge.profesion' => 'required_if_accepted:tiene_conyuge|nullable|string',
            'conyuge.telefono' => 'required_if_accepted:tiene_conyuge|nullable|string',
            'conyuge.tiene_dependencia_laboral' => 'boolean',
            'conyuge.negocio_propio' => 'required_if_accepted:conyuge.tiene_negocio_propio|nullable|string',
            'conyuge.promedio_ingreso_mensual' => 'required_if_accepted:tiene_conyuge|nullable|numeric',

            // hijos
            'hijos' => 'array',
            'hijos.*.tipo' => 'required_if_accepted:tiene_hijos|string',
            'hijos.*.genero' => 'required_if_accepted:tiene_hijos|string',
            'hijos.*.nombres_apellidos' => 'required_if_accepted:tiene_hijos|string',
            'hijos.*.ocupacion' => 'required_if_accepted:tiene_hijos|string',
            'hijos.*.edad' => 'required_if_accepted:tiene_hijos|string',

            // experiencia previa
            'experiencia_previa.nombre_empresa' => 'nullable|required_if_accepted:tiene_experiencia_previa|string',
            'experiencia_previa.cargo' => 'nullable|required_if_accepted:tiene_experiencia_previa|string',
            'experiencia_previa.antiguedad' => 'nullable|required_if_accepted:tiene_experiencia_previa|string',
            'experiencia_previa.asegurado_iess' => 'boolean',
            'experiencia_previa.telefono' => 'nullable|required_if_accepted:tiene_experiencia_previa|string',
            'experiencia_previa.fecha_retiro' => 'nullable|required_if_accepted:tiene_experiencia_previa|string',
            'experiencia_previa.motivo_retiro' => 'nullable|required_if_accepted:tiene_experiencia_previa|string',
            'experiencia_previa.salario' => 'nullable|required_if_accepted:tiene_experiencia_previa|numeric',

            // situacion socioeconomica
            'situacion_socioeconomica' => 'required|array',
            'situacion_socioeconomica.cantidad_personas_aportan' => 'required|integer',
            'situacion_socioeconomica.cantidad_personas_dependientes' => 'required|integer',
            'situacion_socioeconomica.recibe_apoyo_economico_otro_familiar' => 'boolean',
            'situacion_socioeconomica.familiar_apoya_economicamente' => 'nullable|required_if_accepted:situacion_socioeconomica.recibe_apoyo_economico_otro_familiar|string',
            'situacion_socioeconomica.recibe_apoyo_economico_gobierno' => 'boolean',
            'situacion_socioeconomica.institucion_apoya_economicamente' => 'nullable|required_if_accepted:situacion_socioeconomica.recibe_apoyo_economico_gobierno|string',
            'situacion_socioeconomica.tiene_prestamos' => 'boolean',
            'situacion_socioeconomica.cantidad_prestamos' => 'nullable|required_if_accepted:tiene_prestamos|integer',
            'situacion_socioeconomica.entidad_bancaria' => 'nullable|required_if_accepted:tiene_prestamos|string',
            'situacion_socioeconomica.tiene_tarjeta_credito' => 'boolean',
            'situacion_socioeconomica.cantidad_tarjetas_credito' => 'nullable|required_if_accepted:situacion_socioeconomica.tiene_tarjeta_credito|integer',
            'situacion_socioeconomica.vehiculo' => 'required|string',
            'situacion_socioeconomica.tiene_terreno' => 'boolean',
            'situacion_socioeconomica.tiene_bienes' => 'boolean',
            'situacion_socioeconomica.tiene_ingresos_adicionales' => 'boolean',
            'situacion_socioeconomica.ingresos_adicionales' => 'nullable|required_if_accepted:situacion_socioeconomica.tiene_ingresos_adicionales|numeric',
            'situacion_socioeconomica.apoya_familiar_externo' => 'boolean',
            'situacion_socioeconomica.familiar_externo_apoyado' => 'nullable|required_if_accepted:situacion_socioeconomica.apoya_familiar_externo|string',

        ],
            $this->getViviendaRules(),
            $this->getComposicionFamiliarRules(),
            $this->getSaludRequest(),
        );
    }

    public function getViviendaRules(): array
    {
        $rules = (new ViviendaRequest())->rules();
        return collect($rules)->mapWithKeys(function ($rule, $key) {
            return ["vivienda.$key" => $rule];
        })->toArray();
    }

    public function getComposicionFamiliarRules(): array
    {
        $rules = (new ComposicionFamiliarRequest())->rules();
        return collect($rules)->mapWithKeys(function ($rule, $key) {
            return ["composicion_familiar.*.$key" => $rule];
        })->toArray();
    }

    public function getSaludRequest(): array
    {
        $rules = (new SaludRequest())->rules();
        return collect($rules)->mapWithKeys(function ($rule, $key) {
            return ["salud.$key" => $rule];
        })->toArray();
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'empleado_id' => $this->empleado,
            'canton_id' => $this->canton
        ]);
    }
}
