<?php

namespace App\Models;

use App\Models\ComprasProveedores\OrdenCompra;
use App\Models\FondosRotativos\Gasto\Gasto;
use App\Models\FondosRotativos\Saldo\SaldoGrupo;
use App\Models\FondosRotativos\UmbralFondosRotativos;
use App\Models\RecursosHumanos\Area;
use App\Models\RecursosHumanos\Banco;
use App\Models\RecursosHumanos\NominaPrestamos\EgresoRolPago;
use App\Models\RecursosHumanos\NominaPrestamos\Familiares;
use App\Models\RecursosHumanos\NominaPrestamos\RolPago;
use App\Models\Vehiculos\BitacoraVehicular;
use App\Models\Vehiculos\Vehiculo;
use App\Models\Ventas\Vendedor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UppercaseValuesTrait;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Empleado extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable, Searchable;

    protected $table = "empleados";
    protected $fillable = [
        'identificacion',
        'nombres',
        'apellidos',
        'telefono',
        'fecha_nacimiento',
        'jefe_id',
        'canton_id',
        'estado',
        'grupo_id',
        'cargo_id',
        'departamento_id',
        'es_tecnico',
        'firma_url',
        'foto_url',
        'convencional',
        'telefono_empresa',
        'extension',
        'coordenadas',
        'casa_propia',
        'vive_con_discapacitados',
        'responsable_discapacitados',
        'tipo_sangre',
        'direccion',
        'estado_civil_id',
        'correo_personal',
        'area_id',
        'num_cuenta_bancaria',
        'salario',
        'fecha_ingreso',
        'fecha_vinculacion',
        'fecha_salida',
        'tipo_contrato_id',
        'tiene_discapacidad',
        'observacion',
        'nivel_academico',
        'titulo',
        'supa',
        'talla_zapato',
        'talla_camisa',
        'talla_guantes',
        'talla_pantalon',
        'banco',
        'genero',
        'esta_en_empleado',
        'esta_en_rol_pago',
        'acumula_fondos_reserva',
        'realiza_factura',
    ];

    private static $whiteListFilter = [
        'id',
        'identificacion',
        'nombres',
        'apellidos',
        'telefono',
        'fecha_nacimiento',
        'jefe_id',
        'canton_id',
        'grupo_id',
        'cargo_id',
        'departamento_id',
        'estado',
        'esta_en_rol_pago',
        'es_tecnico',
        'tipo_sangre',
        'dirrecion',
        'estado_civil',
        'correo_personal',
        'area_id',
        'num_cuenta',
        'salario',
        'fecha_ingreso',
        'fecha_vinculacion',
        'fecha_salida',
        'tipo_contrato',
        'tiene_discapacidad',
        'observacion',
        'nivel_academico',
        'titulo',
        'supa',
        'talla_zapato',
        'talla_camisa',
        'talla_guantes',
        'talla_pantalon',
        'banco',
        'genero',
        'esta_en_empleado',
        'acumula_fondos_reserva',
        'realiza_factura',
        'es_reporte__saldo_actual'
    ];

    const ACTIVO = 'ACTIVO';
    const INACTIVO = 'INACTIVO';

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'es_responsable_grupo' => 'boolean',
        'esta_en_empleado' => 'boolean',
        'realiza_factura' => 'boolean',
        'estado' => 'boolean',
        'casa_propia' => 'boolean',
        'vive_con_discapacitados' => 'boolean',
        'responsable_discapacitados' => 'boolean',
        'esta_en_rol_pago' => 'boolean',
        'tiene_discapacidad' => 'boolean',
        'acumula_fondos_reserva' => 'boolean',

    ];

    public function toSearchableArray()
    {
        return [
            'nombres' => $this->nombres,
            'apellidos' => $this->apellidos,
            'identificacion' => $this->identificacion,
        ];
    }

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */

    //Relacion uno a muchos polimorfica
    public function telefonos()
    {
        return $this->morphMany('App\Models\Telefono', 'telefonable');
    }

    /**
     * Obtiene el usuario que posee el perfil.
     */
    // Relacion uno a uno (inversa)
    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id', 'id');
    }

    /**
     * Relacion uno a muchos.
     * Un empleado tiene muchos registros de saldo.
     */
    public function saldo()
    {
        return $this->hasMany(SaldoGrupo::class, 'id_usuario');
    }

    // Relacion muchos a muchos
    public function grupo()
    {
        return $this->belongsTo(Grupo::class)->with('subCentroCosto');
    }

    /**
     * Relación uno a muchos (inversa).
     * Uno o más empleados pertenecen a una sede o canton.
     */
    public function canton()
    {
        return $this->belongsTo(Canton::class);
    }

    // Relacion uno a uno
    public function jefe()
    {
        return $this->belongsTo(Empleado::class, 'jefe_id');
    }

    /**
     * Relación uno a muchos.
     * Un empleado tiene uno o muchos activos fijos en custodia.
     */
    public function activos()
    {
        return $this->hasMany(ActivoFijo::class);
    }

    /**
     * Relacion uno a muchos
     * Un empleado es solicitante de varias transacciones
     */
    public function transacciones()
    {
        return $this->hasMany(TransaccionBodega::class);
    }
    public function rolesPago()
    {
        return $this->hasMany(RolPago::class, 'empleado_id');
    }

    /**
     * Relacion uno a muchos.
     * Un empleado con rol superior o igual a COORDINADOR puede autorizar todas las transacciones de sus empleados a cargo
     */
    public function autorizadas()
    {
        return $this->hasMany(TransaccionBodega::class);
    }

    /**
     * Relacion uno a muchos.
     * Un empleado con rol BODEGA puede entregar cualquier transaccion
     */
    public function atendidas()
    {
        return $this->hasMany(TransaccionBodega::class);
    }

    /**
     * Relacion uno a muchos.
     * Un empleado puede retirar cualquier transaccion asignada
     */
    public function retiradas()
    {
        return $this->hasMany(TransaccionBodega::class);
    }

    /**
     * Relacion uno a muchos.
     * Un empleado puede hacer muchas devoluciones de materiales
     */
    public function devoluciones()
    {
        return $this->hasMany(Devolucion::class);
    }

    /* public function subtareas()
    {
        return $this->belongsToMany(Subtarea::class);e
    } */
    /**
     * Relacion uno a muchos.
     * Un empleado BODEGUERO puede registrar muchos movimientos
     */
    public function movimientos()
    {
        return $this->hasMany(MovimientoProducto::class);
    }

    /**
     * Relacion uno a muchos
     * Un empleado es solicitante de varios pedidos
     */
    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }

    /**
     * Realación muchos a muchos.
     * Un empleado registra varias bitacoras
     */
    public function bitacoras()
    {
        return $this->belongsToMany(Vehiculo::class, 'bitacora_vehiculos', 'chofer_id', 'vehiculo_id')
            ->withPivot('fecha', 'hora_salida', 'hora_llegada', 'km_inicial', 'km_final', 'tanque_inicio', 'tanque_final', 'firmada')->withTimestamps();
    }
    public function ultimaBitacora()
    {
        return $this->hasOne(BitacoraVehicular::class, 'chofer_id', 'id')->latestOfMany();
    }

    /**
     * Relacion uno a muchos
     * Un empleado es solicitante de varias transferencias
     */
    public function transferencias()
    {
        return $this->hasMany(Transferencia::class);
    }

    public function subtareas()
    {
        return $this->hasMany(Subtarea::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'responsable_id', 'id');
    }

    public function ticketsSolicitados()
    {
        return $this->hasMany(Ticket::class, 'solicitante_id', 'id');
    }

    /**
     * Relación uno a uno.
     * Un empleado tiene solo un cargo.
     */
    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }
    /**
     * Relación uno a uno.
     * Un empleado pertenece a una sola area.
     */
    public function area()
    {
        return $this->belongsTo(Area::class);
    }
    /**
     * Relación uno a uno.
     * Un empleado tiene un solo estado civil.
     */
    public function estadoCivil()
    {
        return $this->hasOne(EstadoCivil::class, 'id', 'estado_civil_id');
    }
    public function familiares_info()
    {
        return $this->hasMany(Familiares::class, 'empleado_id', 'id');
    }
    /**
     * Relación uno a uno.
     * Un empleado tiene uncuente aen un banco.
     */
    public function banco_info()
    {
        return $this->hasOne(Banco::class, 'id', 'banco');
    }
    /**
     * Relación uno a uno.
     * Un empleado tiene solo tipo de contrato
     */
    public function tipoContrato()
    {
        return $this->belongsTo(TipoContrato::class, 'tipo_contrato_id', 'id');
    }
    /**
     * Relación uno a uno.
     * Un empleado tiene solo tipo de sangre
     */



    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    public function tareasCoordinador()
    {
        return $this->hasMany(Tarea::class, 'coordinador_id');
    }

    public function subtareasCoordinador(): HasManyThrough
    {
        return $this->hasManyThrough(Subtarea::class, Tarea::class, 'coordinador_id');
        //Log::channel('testing')->info('Log', ['Coordinador: ', $coordinador]);
        //return DB::table('subtareas')->join('tareas', 'subtareas.tarea_id', '=', 'tareas.id')->where('tareas.coordinador_id', 3);
    }

    public static function extraerNombresApellidos(Empleado $empleado)
    {
        // if (!$empleado) return null;
        return $empleado->nombres . ' ' . $empleado->apellidos;
    }
    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }
    public function umbral()
    {
        return $this->hasOne(UmbralFondosRotativos::class, 'empleado_id', 'id');
    }
    public function egresoRolPago()
    {
        return $this->hasMany(EgresoRolPago::class, 'empleado_id', 'id');
    }

    public function ordenesCompras()
    {
        return $this->hasMany(OrdenCompra::class, 'solicitante_id');
    }

    public function gastos()
    {
        return $this->hasMany(Gasto::class, 'id_usuario');
    }

    public function vendedor()
    {
        return $this->hasOne(Vendedor::class);
    }
    public static function empaquetarListado($empleados)
    {
        $results = [];
        $id = 0;
        $row = [];

        foreach ($empleados as $empleado) {

            $row['item'] = $id + 1;
            $row['id'] =  $empleado->id;
            $row['apellidos'] =  $empleado->apellidos;
            $row['nombres'] =   $empleado->nombres;
            $row['identificacion'] =  $empleado->identificacion;
            $row['departamento'] =  $empleado->departamento != null ? $empleado->departamento->nombre : '';
            $row['area'] =  $empleado->area != null ? $empleado->area->nombre : '';
            $row['cargo'] =  $empleado->cargo != null ? $empleado->cargo->nombre : '';
            $row['salario'] =  $empleado->salario;
            $results[$id] = $row;
            $id++;
        }
        return $results;
    }
}
