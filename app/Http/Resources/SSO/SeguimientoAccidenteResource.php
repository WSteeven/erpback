<?php

namespace App\Http\Resources\SSO;

use App\Models\ConfiguracionGeneral;
use App\Models\Empleado;
use App\Models\Medico\CitaMedica;
use App\Models\SSO\Certificacion;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $accidente
 */
class SeguimientoAccidenteResource extends JsonResource
{
    public ConfiguracionGeneral $configuracion;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $this->configuracion = ConfiguracionGeneral::first();
        $tarea = $this->subtarea?->tarea;

        return [
            'id' => $this['id'],
            'condiciones_climatologicas' => $this['condiciones_climatologicas'],
            'condiciones_laborales' => $this['condiciones_laborales'],
            'autorizaciones_permisos_texto' => $this['autorizaciones_permisos_texto'],
            'autorizaciones_permisos_foto' => $this['autorizaciones_permisos_foto'],
            'se_notifica_riesgos_trabajo' => $this['se_notifica_riesgos_trabajo'],
            'actividades_desarrolladas' => $this['actividades_desarrolladas'],
            'descripcion_amplia_accidente' => $this['descripcion_amplia_accidente'],
            'antes_accidente' => $this['antes_accidente'],
            'archivos' => $this->archivos->pluck('ruta'),
            'instantes_previos' => $this['instantes_previos'],
            'durante_accidente' => $this['durante_accidente'],
            'despues_accidente' => $this['despues_accidente'],
            'hipotesis_causa_accidente' => $this['hipotesis_causa_accidente'],
            'causas_inmediatas' => $this['causas_inmediatas'],
            'causas_basicas' => $this['causas_basicas'],
            'medidas_preventivas' => $this['medidas_preventivas'],
            'seguimiento_sso' => $this['seguimiento_sso'],
            'seguimiento_trabajo_social' => $this['seguimiento_trabajo_social'],
            'seguimiento_rrhh' => $this['seguimiento_rrhh'],
            'tarea' => $tarea?->id,
            'subtarea' => $this['subtarea_id'],
            'accidente' => $this['accidente_id'],
            'fecha_hora_accidente' => $this->accidente->fecha_hora_ocurrencia,
            'consultas_medicas' => $this->obtenerConsultasMedicas(),
            'ruta_tarea' => $tarea?->rutaTarea?->ruta,
            'titulo_tarea' => $tarea?->titulo,
            'accidentados_informe' => $this->obtenerAccidentadosInforme(),
            'experiencia_accidentados_informe' => $this->obtenerExperienciaAccidentadosInforme(),
            'formacion_accidentados_informe' => $this->obtenerFormacionAccidentadosInforme(),
            'metodologia_utilizada' => $this['metodologia_utilizada'],
            'actividades_subtarea' => $this->obtenerActividadesSubtarea(),
            'certificaciones' => $this->obtenerCertificaciones(),
        ];
    }

    private function obtenerConsultasMedicas()
    {
        $empleados = collect([]);
        $empleados_ids = json_decode($this->accidente->empleados_involucrados);
        foreach ($empleados_ids as $empleado_id) {
            $empleado = Empleado::find($empleado_id);
            $cita_medica = $this->accidente->citasMedicas->where('paciente_id', $empleado_id)->first();
            $empleados->push([
                'empleado_id' => $empleado_id,
                'empleado' => Empleado::extraerNombresApellidos($empleado),
                'telefono' => $empleado['telefono'],
                'grupo' => $empleado->grupo?->nombre,
                'cargo' => $empleado->cargo?->nombre,
                'cita_medica' => $cita_medica?->id,
                'cita_medica_atendida' => $cita_medica?->estado_cita_medica == CitaMedica::ATENDIDO,
                'dias_descanso' => $cita_medica?->consultaMedica?->dias_descanso,
                'dado_alta' => $cita_medica?->consultaMedica?->dado_alta,
            ]);
        }
        return $empleados;
    }

    private function obtenerAccidentadosInforme()
    {
        $empleados = collect([]);
        $empleados_ids = json_decode($this->accidente->empleados_involucrados);
        foreach ($empleados_ids as $empleado_id) {
            $empleado = Empleado::find($empleado_id);
            $cita_medica = $this->accidente->citasMedicas->where('paciente_id', $empleado_id)->first();
            $empleados->push([
                'nombre_accidentado' => Empleado::extraerNombresApellidos($empleado),
                'nacionalidad' => 'ECUATORIANO',
                'identificacion' => $empleado->identificacion,
                'fecha_lugar_nacimiento' => $empleado->fecha_nacimiento,
                'edad' => Empleado::obtenerEdad($empleado) . ' años',
                'cargo' => $empleado->cargo->nombre,
                'empresa' => $this->configuracion->razon_social,
                'actividad_durante_accidente' => $this['actividades_desarrolladas'],
                'tipo_lesion' => $cita_medica?->consultaMedica?->diagnosticosCitaMedica
                    ? implode(", ", $cita_medica->consultaMedica->diagnosticosCitaMedica->pluck('cie')->pluck('nombre_enfermedad')->toArray())
                    : null,
            ]);
        }
        return $empleados;
    }

    private function obtenerExperienciaAccidentadosInforme()
    {
        $empleados = collect([]);
        $empleados_ids = json_decode($this->accidente->empleados_involucrados);
        foreach ($empleados_ids as $empleado_id) {
            $empleado = Empleado::find($empleado_id);

            $empleados->push([
                'nombre_accidentado' => Empleado::extraerNombresApellidos($empleado),
                'fecha_ingreso' => Carbon::now()->diffInYears($empleado->fecha_ingreso) . ' años de experiencia en el cargo de ' . $empleado->cargo->nombre,
            ]);
        }
        return $empleados;
    }

    private function obtenerFormacionAccidentadosInforme()
    {
        $empleados = collect([]);
        $empleados_ids = json_decode($this->accidente->empleados_involucrados);
        foreach ($empleados_ids as $empleado_id) {
            $empleado = Empleado::find($empleado_id);

            $empleados->push([
                'nombre_accidentado' => Empleado::extraerNombresApellidos($empleado),
                'nivel_academico' => $empleado->nivel_academico,
            ]);
        }
        return $empleados;
    }

    private function obtenerActividadesSubtarea()
    {
        $tipoTrabajo = $this->subtarea?->tipo_trabajo?->descripcion;
        return $this->subtarea?->trabajosRealizados->map(fn($trabajoRealizado) => [
            'trabajo_realizado' => $trabajoRealizado->trabajo_realizado,
            'fecha_hora' => $trabajoRealizado->fecha_hora,
            'actividad' => $tipoTrabajo,
        ]);
    }

    private function obtenerCertificaciones()
    {
        $empleados = collect([]);
        $empleados_ids = json_decode($this->accidente->empleados_involucrados);
        foreach ($empleados_ids as $empleado_id) {
            $empleado = Empleado::find($empleado_id);

            $empleados->push([
                'nombre_accidentado' => Empleado::extraerNombresApellidos($empleado),
                'certificaciones' => $empleado->certificacionesEmpleado->map(fn($certificacion) => [
                    'id' => $certificacion->id,
                    'certificaciones_id' => collect(json_decode($certificacion->certificaciones_id))->map(fn($id) => Certificacion::find($id)->descripcion,
                    ),
                ]),
            ]);
        }
        return $empleados;
    }
}
