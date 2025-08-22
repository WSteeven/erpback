<?php

namespace App\Http\Resources;

use App\Models\Empleado;
use App\Models\RecursosHumanos\DiscapacidadUsuario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmpleadoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        $campos = $request->query('campos') ? explode(',', $request->query('campos')) : [];
        $controller_method = $request->route()->getActionMethod();
        $modelo = array(
            'id' => $this->id,
            'identificacion' => $this->identificacion,
            'nombres' => $this->nombres,
            'apellidos' => $this->apellidos,
            'nombres_apellidos' => $this->nombres . ' ' . $this->apellidos,
            'fecha_nacimiento' => date('Y-m-d', strtotime($this->fecha_nacimiento)),
            'telefono' => $this->telefono,
            'email' => $this->user ? $this->user->email : '',
            'usuario' => $this->user?->name,
            'jefe' => $this->jefe ? $this->jefe->nombres . ' ' . $this->jefe->apellidos : 'N/A',
            'jefe_id' => $this->jefe_id,
            'canton' => $this->canton ? $this->canton->canton : 'NO TIENE',
            'edad' => Empleado::obtenerEdad($this),
            // 'nombre_canton' => $this->canton ? $this->canton->canton : 'NO TIENE',
            'estado' => $this->estado, //?Empleado::ACTIVO:Empleado::INACTIVO,
            'cargo' => $this->cargo?->nombre,
            'nombre_cargo' => $this->cargo?->nombre,
            'departamento' => $this->departamento?->nombre,
            'grupo' => $this->grupo?->nombre,
            'grupo_id' => $this->grupo_id,
            'firma_url' => $this->firma_url ? url($this->firma_url) : null,
            'fecha_ingreso' => $this->fecha_ingreso,
            'foto_url' => $this->foto_url ? url($this->foto_url) : url('/storage/sinfoto.png'),
            'convencional' => $this->convencional ?: null,
            'telefono_empresa' => $this->telefono_empresa ?: null,
            'extension' => $this->extension ?: null,
            'coordenadas' => $this->coordenadas ?: null,
            'casa_propia' => $this->casa_propia,
            'vive_con_discapacitados' => $this->vive_con_discapacitados,
            'responsable_discapacitados' => $this->responsable_discapacitados,
            'tiene_discapacidad' => $this->tiene_discapacidad,
            'modificar_fecha_vinculacion' => $this->fecha_ingreso != $this->fecha_vinculacion,
            'fecha_vinculacion' => $this->fecha_vinculacion,
            'fecha_salida' => $this->fecha_salida,
            'area' => $this->area_id,
            'area_info' => $this->area ? $this->area->nombre : null,
            'observacion' => $this->observacion,
            'esta_en_rol_pago' => $this->esta_en_rol_pago,
            'acumula_fondos_reserva' => $this->acumula_fondos_reserva,
            'familiares' => $this->familiares,
            'num_cuenta' => $this->num_cuenta_bancaria,
            'salario' => $this->salario,
            'supa' => $this->supa,
            'roles' => $this->user ? implode(', ', $this->user->getRoleNames()->filter(fn($rol) => $rol !== 'EMPLEADO')->toArray()) : array(),
            'direccion' => $this->direccion,
            'nivel_academico' => $this->nivel_academico,
            'autoidentificacion_etnica' => $this->autoidentificacion_etnica,
            'trabajador_sustituto' => $this->trabajador_sustituto,
            'orientacion_sexual_info' => $this->orientacionSexual,
            'orientacion_sexual' => $this->orientacion_sexual_id,
            'identidad_genero' => $this->identidad_genero_id,
            'identidad_genero_info' => $this->identidadGenero,
            'religion' => $this->religion_id,
            'religion_info' => $this->religion,
            'archivos' => $this->archivos->count(),
            'estado_civil' => $this->estadoCivil?->nombre,
        );
        if ($controller_method == 'show') {
            $modelo['jefe'] = $this->jefe_id;
            $modelo['jefe_inmediato'] = Empleado::extraerNombresApellidos($this->jefe);
            $modelo['usuario'] = $this->user->name;
            $modelo['canton'] = $this->canton_id;
            $modelo['nombre_canton'] = $this->canton?->canton;
            $modelo['roles'] = $this->user->getRoleNames();
            $modelo['grupo'] = $this->grupo_id;
            $modelo['tiene_grupo'] = !!$this->grupo_id;
            $modelo['cargo'] = $this->cargo_id;
            $modelo['departamento'] = $this->departamento_id;
            $modelo['fecha_nacimiento'] = $this->fecha_nacimiento;
            $modelo['permisos'] = $this->user?->getAllPermissions();
            $modelo['correo_personal'] = $this->correo_personal;
            $modelo['tipo_sangre'] = $this->tipo_sangre;
            $modelo['direccion'] = $this->direccion;
            $modelo['supa'] = $this->supa;
            $modelo['salario'] = $this->salario;
            $modelo['num_cuenta'] = $this->num_cuenta_bancaria;
            $modelo['banco'] = $this->banco;
            $modelo['banco_info'] = $this->bancoInfo ? $this->bancoInfo->nombre : null;
            $modelo['fecha_ingreso'] = $this->fecha_ingreso;
            $modelo['antiguedad'] = $this->antiguedad($this->fecha_ingreso);
            $modelo['fecha_salida'] = $this->fecha_salida;
            $modelo['talla_zapato'] = $this->talla_zapato;
            $modelo['talla_camisa'] = $this->talla_camisa;
            $modelo['talla_guantes'] = $this->talla_guantes;
            $modelo['talla_pantalon'] = $this->talla_pantalon;
            $modelo['titulo'] = $this->titulo;
            $modelo['estado_civil'] = $this->estado_civil_id;
            $modelo['estado_civil_info'] = $this->estadoCivil ? $this->estadoCivil->nombre : null;
            $modelo['area_info'] = $this->area ? $this->area->nombre : null;
            $modelo['tipo_contrato'] = $this->tipo_contrato_id;
            $modelo['tipo_contrato_info'] = $this->tipoContrato ? $this->tipoContrato->nombre : null;
            $modelo['genero'] = $this->genero;
            $modelo['realiza_factura'] = $this->realiza_factura;
            $modelo['conductor'] = $this->conductor;
            $modelo['discapacidades'] = DiscapacidadUsuario::mapearDiscapacidades($this->user->discapacidades()->get());
        }

        // Filtra los campos personalizados y añádelos a la respuesta si existen
        $data = [];
        foreach ($campos as $campo) {
            if (isset($modelo[$campo])) {
                // $modelo[$campo] = $this->{$campo};
                $data[$campo] = $modelo[$campo];
            }
        }
//        Log::channel('testing')->info('Log', ['EmpleadoResource', $modelo]);
        return count($campos) ? $data : $modelo;
    }

    public function antiguedad($fecha_ingreso)
    {
        // Obtén la fecha actual con Carbon
        $fechaActual = Carbon::now();

        // Convierte la fecha de ingreso a un objeto Carbon
        $fechaIngreso = Carbon::parse($fecha_ingreso);

        // Calcula la diferencia en años, meses y días usando Carbon
        $diff = $fechaActual->diff($fechaIngreso);

        // Obtiene los valores de diferencia en años, meses y días
        $diffYears = $diff->y;
        $diffMonths = $diff->m;
        $diffDays = $diff->d;

        // Verifica si los valores de diferencia son válidos
        if (!is_int($diffYears) || !is_int($diffMonths) || !is_int($diffDays)) {
            return null;
        }

        // Retorna la diferencia en el formato deseado
        return $diffYears . ' Años ' . $diffMonths . ' Meses ' . $diffDays . ' Días';
    }

}
