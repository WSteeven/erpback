<?php

namespace App\Http\Requests\RecursosHumanos\TrabajoSocial;

use Illuminate\Foundation\Http\FormRequest;

class VisitaDomiciliariaRequest extends FormRequest
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
            'parentesco_contacto_emergencia' => 'required|string',
            'telefono_contacto_emergencia' => 'required|string',
            'diagnostico_social'=>'required|string',
            'imagen_genograma'=>'nullable|string',
            'imagen_visita_domiciliaria'=>'required|string',
            'observaciones'=>'required|string',
            // economia familiar
            'economia_familiar.ingresos'=>'required|array',
            'economia_familiar.ingresos.*.id'=>'required|integer',
            'economia_familiar.ingresos.*.nombres_apellidos'=>'required|string',
            'economia_familiar.ingresos.*.ocupacion'=>'required|string',
            'economia_familiar.ingresos.*.ingreso_mensual'=>'required|numeric',

            'economia_familiar.eg_vivienda'=>'required|numeric',
            'economia_familiar.eg_servicios_basicos'=>'required|numeric',
            'economia_familiar.eg_educacion'=>'required|numeric',
            'economia_familiar.eg_salud'=>'required|numeric',
            'economia_familiar.eg_vestimenta'=>'required|numeric',
            'economia_familiar.eg_alimentacion'=>'required|numeric',
            'economia_familiar.eg_transporte'=>'required|numeric',
            'economia_familiar.eg_prestamos'=>'required|numeric',
            'economia_familiar.eg_otros_gastos'=>'required|numeric',
        ],
            (new FichaSocioeconomicaRequest())->getViviendaRules(),
            (new FichaSocioeconomicaRequest())->getComposicionFamiliarRules(),
            (new FichaSocioeconomicaRequest())->getSaludRequest(),
        );
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'empleado_id' => $this->empleado,
            'canton_id' => $this->canton
        ]);
    }

}
