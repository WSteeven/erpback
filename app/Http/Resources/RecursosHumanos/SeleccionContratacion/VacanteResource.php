<?php

namespace App\Http\Resources\RecursosHumanos\SeleccionContratacion;

use App\Models\Empleado;
use App\Models\RecursosHumanos\SeleccionContratacion\Conocimiento;
use App\Models\RecursosHumanos\SeleccionContratacion\Postulacion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Src\Shared\ObtenerInstanciaUsuario;
use Src\Shared\Utils;

class VacanteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        $controller_method = $request->route()->getActionMethod();
//        Log::channel('testing')->info('Log', ['metodo', $controller_method, Auth::user()]);
        [, , $user] = ObtenerInstanciaUsuario::tipoUsuario();
        $modelo = [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'fecha_caducidad' => (Carbon::createFromFormat('Y-m-d', $this->fecha_caducidad)->setTime(17, 0))->format('Y-m-d H:i'),
            'imagen_referencia' => $this->imagen_referencia ? url($this->imagen_referencia) : null,
            'imagen_publicidad' => $this->imagen_publicidad ? url($this->imagen_publicidad) : null,
            'anios_experiencia' => $this->anios_experiencia,
            'numero_postulantes' => $this->numero_postulantes,
            'tipo_puesto' => $this->tipoPuesto->nombre,
            'publicante' => Empleado::extraerNombresApellidos($this->publicante),
            'solicitud' => $this->solicitud->nombre,
            'modalidad' => $this->modalidad->nombre,
            'activo' => $this->activo,
            'areas_conocimiento' => Conocimiento::whereIn('id', array_map('intval', Utils::convertirStringComasArray($this->areas_conocimiento)))->pluck('nombre'),
            'requiere_experiencia' => !!$this->anios_experiencia,
            'requiere_formacion_academica' => !!count($this->formacionesAcademicas),
            'disponibilidad_viajar' => $this->disponibilidad_viajar,
            'requiere_licencia' => $this->requiere_licencia,
            'es_favorita' => !!$this->favorita,
            'created_at' => $this->created_at,
            'ya_postulada' => !!$this->postulacion,
            'postulantes_preseleccionados' => $this->postulacionesPreseleccionadas(),
            'canton' => $this->canton->canton,
            'num_plazas' => $this->num_plazas,
            'es_completada' => $this->es_completada,
        ];
        if ($controller_method == 'showPreview' || $controller_method == 'favorite') {
            $modelo['formaciones_academicas'] = $this->formacionesAcademicas;
            $modelo['estado_mi_postulacion'] = $user?->postulaciones()->where('vacante_id', $this->id)->first()?->estado;
        }
        if ($controller_method == 'show') {
            $modelo['tipo_puesto'] = $this->tipo_puesto_id;
            $modelo['descripcion'] = $this->descripcion;
            $modelo['modalidad'] = $this->modalidad_id;
            $modelo['publicante'] = $this->publicante_id;
            $modelo['solicitud'] = $this->solicitud_id;
            $modelo['canton'] = $this->canton_id;
            $modelo['areas_conocimiento'] = array_map('intval', Utils::convertirStringComasArray($this->areas_conocimiento));
            $modelo['requiere_experiencia'] = !!$this->anios_experiencia;
            $modelo['requiere_formacion_academica'] = !!count($this->formacionesAcademicas);
            $modelo['formaciones_academicas'] = $this->formacionesAcademicas;
        }
        return $modelo;
    }

    private function postulacionesPreseleccionadas()
    {
        return Postulacion::where('vacante_id', $this->id)->where('estado', Postulacion::PRESELECCIONADO)->count();
    }
}
