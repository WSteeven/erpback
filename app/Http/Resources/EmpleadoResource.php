<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class EmpleadoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $campos = $request->query('campos') ? explode(',', $request->query('campos')) : [];
        $controller_method = $request->route()->getActionMethod();
        $modelo = [
            'id' => $this->id,
            'identificacion' => $this->identificacion,
            'nombres' => $this->nombres,
            'apellidos' => $this->apellidos,
            'telefono' => $this->telefono,
            'email' => $this->user ? $this->user->email : '',
            'usuario' => $this->user?->name,
            'jefe' => $this->jefe ? $this->jefe->nombres . ' ' . $this->jefe->apellidos : 'N/A',
            'canton' => $this->canton ? $this->canton->canton : 'NO TIENE',
            'estado' => $this->estado, //?Empleado::ACTIVO:Empleado::INACTIVO,
            'cargo' => $this->cargo?->nombre,
            'departamento' => $this->departamento?->nombre,
            'grupo' => $this->grupo?->nombre,
            'grupo_id' => $this->grupo?->nombre,
            'cargo' => $this->cargo?->nombre,
            'firma_url' => $this->firma_url ? url($this->firma_url) : null,
            'foto_url' => $this->foto_url ? url($this->foto_url) : null,
            'convencional' => $this->convencional ? $this->convencional : null,
            'telefono_empresa' => $this->telefono_empresa ? $this->telefono_empresa : null,
            'extension' => $this->extension ? $this->extension : null,
            'coordenadas' => $this->coordenadas ? $this->coordenadas : null,
            'casa_propia' => $this->casa_propia,
            'vive_con_discapacitados' => $this->vive_con_discapacitados,
            'responsable_discapacitados' => $this->responsable_discapacitados,
            'tiene_discapacidad' => $this->tiene_discapacidad,
            'modificar_fecha_vinculacion' => $this->fecha_ingreso != $this->fecha_vinculacion,
            'fecha_vinculacion' => $this->fecha_vinculacion,
            'area' =>  $this->area_id,
            'area_info' =>  $this->area ? $this->area->nombre : null,
            'observacion' => $this->observacion,
            'esta_en_rol_pago' => $this->esta_en_rol_pago,
            'acumula_fondos_reserva' => $this->acumula_fondos_reserva,
            'familiares' => $this->familiares_info,
            'num_cuenta' => $this->num_cuenta_bancaria,
            'salario' => $this->salario,
            'supa' => $this->supa,
            'roles' => $this->user ? implode(', ', $this->user?->getRoleNames()->filter(fn ($rol) => $rol !== 'EMPLEADO')->toArray()) : [],
            'direccion' => $this->direccion,
        ];


        if ($controller_method == 'show') {
            $modelo['jefe'] = $this->jefe_id;
            $modelo['usuario'] = $this->user->name;
            $modelo['canton'] = $this->canton_id;
            $modelo['roles'] = $this->user->getRoleNames();
            $modelo['grupo'] = $this->grupo_id;
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
            $modelo['banco_info'] = $this->banco_info ? $this->banco_info->nombre : null;
            $modelo['fecha_ingreso'] = $this->fecha_ingreso;
            $modelo['antiguedad'] = $this->antiguedad($this->fecha_ingreso);
            $modelo['fecha_salida'] = $this->fecha_salida;
            $modelo['talla_zapato'] = $this->talla_zapato;
            $modelo['talla_camisa'] = $this->talla_camisa;
            $modelo['talla_guantes'] = $this->talla_guantes;
            $modelo['talla_pantalon'] = $this->talla_pantalon;
            $modelo['nivel_academico'] = $this->nivel_academico;
            $modelo['estado_civil'] = $this->estado_civil_id;
            $modelo['estado_civil_info'] = $this->estadoCivil  ? $this->estadoCivil->nombre : null;
            $modelo['area_info'] =  $this->area ? $this->area->nombre : null;
            $modelo['tipo_contrato'] = $this->tipo_contrato_id;
            $modelo['tipo_contrato_info'] = $this->tipoContrato ? $this->tipoContrato->nombre : null;
            $modelo['genero'] = $this->genero;
            $modelo['realiza_factura'] = $this->realiza_factura;
        }

        // Filtra los campos personalizados y añádelos a la respuesta si existen
        $data = [];
        foreach ($campos as $campo) {
            if (isset($modelo[$campo])) {
                // $modelo[$campo] = $this->{$campo};
                $data[$campo] = $this->{$campo};
            }
        }
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
