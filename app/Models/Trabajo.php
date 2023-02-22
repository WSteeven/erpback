<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Support\Facades\Log;
use Src\App\WhereRelationLikeCondition\TrabajoCantonWRLC;
use Src\App\WhereRelationLikeCondition\TrabajoClienteWRLC;
use Src\App\WhereRelationLikeCondition\TrabajoCoordinadorWRLC;
use Src\App\WhereRelationLikeCondition\TrabajoFechaHoraCreacionWRLC;
use Src\App\WhereRelationLikeCondition\TrabajoProyectoWRLC;
use Src\App\WhereRelationLikeCondition\TrabajoTipoTrabajoWRLC;

class Trabajo extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable, UppercaseValuesTrait;

    const CREADO = 'CREADO';
    const ASIGNADO = 'ASIGNADO';
    const EJECUTANDO = 'EJECUTANDO';
    const PAUSADO = 'PAUSADO';
    const SUSPENDIDO = 'SUSPENDIDO';
    const CANCELADO = 'CANCELADO';
    const REALIZADO = 'REALIZADO';
    const FINALIZADO = 'FINALIZADO';

    // Modo de asignacion de trabajo
    const POR_GRUPO = 'POR_GRUPO';
    const POR_EMPLEADO = 'POR_EMPLEADO';

    const PARA_PROYECTO = 'PARA_PROYECTO';
    const PARA_CLIENTE_FINAL = 'PARA_CLIENTE_FINAL';

    protected $table = 'trabajos';
    protected $fillable = [
        'codigo_trabajo',
        'codigo_trabajo_cliente',
        'titulo',
        'descripcion_completa',
        'observacion',
        'para_cliente_proyecto',
        'fecha_solicitud',
        'estado',
        'modo_asignacion_trabajo',

        'fecha_hora_creacion',
        'fecha_hora_asignacion',
        'fecha_hora_ejecucion',
        'fecha_hora_realizado',
        'fecha_hora_finalizacion',
        'fecha_hora_suspendido',
        'causa_suspencion',
        'fecha_hora_cancelacion',
        'causa_cancelacion',
        'dias_ocupados',

        'es_dependiente',
        'es_ventana',
        'tiene_subtrabajos',
        'fecha_agendado',
        'hora_inicio_agendado',
        'hora_fin_agendado',

        'tipo_trabajo_id',
        'trabajo_padre_id',
        'cliente_final_id',
        'coordinador_id',
        'fiscalizador_id',
        'proyecto_id',
        'cliente_id',
        'trabajo_dependiente_id',
    ];

    protected $casts = [
        'es_dependiente' => 'boolean',
        'es_ventana' => 'boolean',
        'tiene_subtrabajos' => 'boolean',
    ];

    /*******************
     * Eloquent Filter
     *******************/
    private static $whiteListFilter = [
        '*',
        'cliente.empresa.razon_social',
        'proyecto.codigo_proyecto',
        'tipo_trabajo.descripcion',
        'canton',
        'coordinador.nombres',
    ];

    private $aliasListFilter = [
        'cliente.empresa.razon_social' => 'cliente',
        'proyecto.codigo_proyecto' => 'proyecto',
        'tipo_trabajo.descripcion' => 'tipo_trabajo',
        'coordinador.nombres' => 'coordinador',
        // 'canton.canton' => 'canton',
    ];

    public function serializeRequestFilter($request)
    {
       $request['es_ventana'] = isset($request['es_ventana']) && $request['es_ventana']['like'] == '%true%' ? 1 : 0;
       return $request;
    }

    public function EloquentFilterCustomDetection(): array
    {
        return [
            TrabajoClienteWRLC::class,
            TrabajoProyectoWRLC::class,
            TrabajoTipoTrabajoWRLC::class,
            TrabajoFechaHoraCreacionWRLC::class,
            TrabajoCantonWRLC::class,
            TrabajoCoordinadorWRLC::class,
        ];
    }

    /**************
     * RELACIONES
     **************/

    // Relacion uno a muchos (inversa)
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
        //return $this->hasOne(Cliente::class);
    }

    public function clienteFinal()
    {
        return $this->belongsTo(ClienteFinal::class);
    }

    // Relacion uno a muchos (inversa)
    public function fiscalizador()
    {
        return $this->belongsTo(Empleado::class, 'fiscalizador_id', 'id');
    }

    // Relacion uno a muchos (inversa)
    public function coordinador()
    {
        return $this->belongsTo(Empleado::class, 'coordinador_id', 'id');
    }

    // Relacion uno a muchos (inversa)
    /*public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }*/
    public function grupos()
    {
        return $this->belongsToMany(Grupo::class);
    }

    // Relacion uno a muchos (inversa)
    public function tipo_trabajo()
    {
        return $this->belongsTo(TipoTrabajo::class);
    }

    /**
     * Relación uno a muchos .
     * Una subtarea puede tener varias transacciones
     */
    public function transacciones()
    {
        return $this->hasMany(TransaccionBodega::class);
    }

    // Relacion uno a muchos
    public function archivos()
    {
        return $this->hasMany(ArchivoSubtarea::class);
    }

    public function pausasSubtarea()
    {
        return $this->hasMany(PausaSubtarea::class);
    }

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function trabajo()
    {
        return $this->hasOne(Trabajo::class, 'id', 'trabajo_dependiente');
    }

    public function tecnicosPrincipales($empleados)
    {
        // return EmpleadoResource::collection(Empleado::whereIn('id', $ids)->get());
        // return Empleado::whereIn('id', $ids)->get()->map(fn ($item) => [

        return $empleados->map(fn ($item) => [
            'id' => $item->id,
            'identificacion' => $item->identificacion,
            'nombres' => $item->nombres,
            'apellidos' => $item->apellidos,
            'telefono' => $item->telefono,
            'fecha_nacimiento' => $item->fecha_nacimiento,
            'email' => $item->user ? $item->user->email : '',
            'jefe' => $item->jefe ? $item->jefe->nombres . ' ' . $item->jefe->apellidos : 'N/A',
            'usuario' => $item->user->name,
            'sucursal' => $item->sucursal->lugar,
            'estado' => $item->estado,
            'grupo' => $item->grupo?->nombre,
            'disponible' => $item->disponible,
            'roles' => implode(', ', $item->user->getRoleNames()->toArray()),
        ]);
    }

    public function otrosTecnicos($empleados)
    {
        //$empleados->filter(fn($item) => $item->);
        return $empleados->map(fn ($item) => [
            'id' => $item->id,
            'identificacion' => $item->identificacion,
            'nombres' => $item->nombres,
            'apellidos' => $item->apellidos,
            'telefono' => $item->telefono,
            'fecha_nacimiento' => $item->fecha_nacimiento,
            'email' => $item->user ? $item->user->email : '',
            'jefe' => $item->jefe ? $item->jefe->nombres . ' ' . $item->jefe->apellidos : 'N/A',
            'usuario' => $item->user->name,
            'sucursal' => $item->sucursal->lugar,
            'estado' => $item->estado,
            'grupo' => $item->grupo?->nombre,
            'disponible' => $item->disponible,
            'roles' => implode(', ', $item->user->getRoleNames()->toArray()),
        ]);
    }

    public function empleados()
    {
        return $this->belongsToMany(Empleado::class);
    }
}
