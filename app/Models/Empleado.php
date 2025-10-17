<?php

namespace App\Models;

use App\Models\ComprasProveedores\OrdenCompra;
use App\Models\FondosRotativos\Gasto\Gasto;
use App\Models\FondosRotativos\Saldo\SaldoGrupo;
use App\Models\FondosRotativos\UmbralFondosRotativos;
use App\Models\Medico\IdentidadGenero;
use App\Models\Medico\OrientacionSexual;
use App\Models\Medico\Religion;
use App\Models\Medico\RespuestaCuestionarioEmpleado;
use App\Models\RecursosHumanos\Area;
use App\Models\RecursosHumanos\Banco;
use App\Models\RecursosHumanos\EmpleadoDelegado;
use App\Models\RecursosHumanos\NominaPrestamos\EgresoRolPago;
use App\Models\RecursosHumanos\NominaPrestamos\Familiares;
use App\Models\RecursosHumanos\NominaPrestamos\RolPago;
use App\Models\RecursosHumanos\TipoDiscapacidad;
use App\Models\RecursosHumanos\TrabajoSocial\FichaSocioeconomica;
use App\Models\RecursosHumanos\TrabajoSocial\VisitaDomiciliaria;
use App\Models\Seguridad\Zona;
use App\Models\SSO\CertificacionEmpleado;
use App\Models\Vehiculos\BitacoraVehicular;
use App\Models\Vehiculos\Conductor;
use App\Models\Vehiculos\Vehiculo;
use App\Models\Ventas\Vendedor;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UppercaseValuesTrait;
use Carbon\Carbon;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\Empleado
 *
 * @method static findOrFail(mixed $empleado_id)
 * @property int $id
 * @property string|null $identificacion
 * @property string $nombres
 * @property string $apellidos
 * @property string|null $telefono
 * @property string|null $fecha_nacimiento
 * @property bool $estado
 * @property int|null $grupo_id
 * @property int|null $jefe_id
 * @property int $usuario_id
 * @property int|null $canton_id
 * @property int|null $cargo_id
 * @property int|null $departamento_id
 * @property string|null $foto_url
 * @property string|null $firma_url
 * @property string|null $convencional
 * @property string|null $telefono_empresa
 * @property string $correo_personal
 * @property string|null $extension
 * @property string|null $coordenadas
 * @property bool $casa_propia
 * @property bool $vive_con_discapacitados
 * @property bool $responsable_discapacitados
 * @property string $tipo_sangre
 * @property string $direccion
 * @property string|null $supa
 * @property string|null $salario
 * @property string|null $num_cuenta_bancaria
 * @property bool $tiene_discapacidad
 * @property string|null $fecha_ingreso
 * @property string|null $fecha_vinculacion
 * @property string|null $nivel_academico
 * @property string|null $titulo
 * @property string|null $fecha_salida
 * @property string|null $observacion
 * @property int|null $estado_civil_id
 * @property string $genero
 * @property int|null $area_id
 * @property int|null $tipo_contrato_id
 * @property string|null $talla_zapato
 * @property string|null $talla_camisa
 * @property string|null $talla_guantes
 * @property string|null $talla_pantalon
 * @property int|null $banco
 * @property bool|null $esta_en_rol_pago
 * @property bool|null $acumula_fondos_reserva
 * @property bool|null $realiza_factura
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $autoidentificacion_etnica
 * @property bool $trabajador_sustituto
 * @property int|null $orientacion_sexual_id
 * @property int|null $identidad_genero_id
 * @property int|null $religion_id
 * @property-read Collection<int, ActivoFijo> $activos
 * @property-read int|null $activos_count
 * @property-read Collection<int, Archivo> $archivos
 * @property-read int|null $archivos_count
 * @property-read Area|null $area
 * @property-read Collection<int, TransaccionBodega> $atendidas
 * @property-read int|null $atendidas_count
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Collection<int, TransaccionBodega> $autorizadas
 * @property-read int|null $autorizadas_count
 * @property-read Banco|null $bancoInfo
 * @property-read Collection<int, Vehiculo> $bitacoras
 * @property-read int|null $bitacoras_count
 * @property-read Canton|null $canton
 * @property-read Cargo|null $cargo
 * @property-read Conductor|null $conductor
 * @property-read Departamento|null $departamento
 * @property-read Collection<int, Devolucion> $devoluciones
 * @property-read int|null $devoluciones_count
 * @property-read Collection<int, EgresoRolPago> $egresoRolPago
 * @property-read int|null $egreso_rol_pago_count
 * @property-read EstadoCivil|null $estadoCivil
 * @property-read Collection<int, Familiares> $familiares
 * @property-read int|null $familiares_count
 * @property-read Collection<int, Gasto> $gastos
 * @property-read int|null $gastos_count
 * @property-read Grupo|null $grupo
 * @property-read IdentidadGenero|null $identidadGenero
 * @property-read Empleado|null $jefe
 * @property-read Collection<int, MovimientoProducto> $movimientos
 * @property-read int|null $movimientos_count
 * @property-read Collection<int, Notificacion> $notificaciones
 * @property-read int|null $notificaciones_count
 * @property-read Collection<int, OrdenCompra> $ordenesCompras
 * @property-read int|null $ordenes_compras_count
 * @property-read OrientacionSexual|null $orientacionSexual
 * @property-read Collection<int, Pedido> $pedidos
 * @property-read int|null $pedidos_count
 * @property-read Religion|null $religion
 * @property-read Collection<int, RespuestaCuestionarioEmpleado> $respuestaCuestionarioEmpleado
 * @property-read int|null $respuesta_cuestionario_empleado_count
 * @property-read Collection<int, TransaccionBodega> $retiradas
 * @property-read int|null $retiradas_count
 * @property-read Collection<int, RolPago> $rolesPago
 * @property-read int|null $roles_pago_count
 * @property-read Collection<int, SaldoGrupo> $saldo
 * @property-read int|null $saldo_count
 * @property-read Collection<int, Subtarea> $subtareas
 * @property-read int|null $subtareas_count
 * @property-read Collection<int, Subtarea> $subtareasCoordinador
 * @property-read int|null $subtareas_coordinador_count
 * @property-read Collection<int, Tarea> $tareasCoordinador
 * @property-read int|null $tareas_coordinador_count
 * @property-read Collection<int, Ticket> $tickets
 * @property-read int|null $tickets_count
 * @property-read Collection<int, Ticket> $ticketsSolicitados
 * @property-read int|null $tickets_solicitados_count
 * @property-read TipoContrato|null $tipoContrato
 * @property-read Collection<int, TipoDiscapacidad> $tiposDiscapacidades
 * @property-read int|null $tipos_discapacidades_count
 * @property-read Collection<int, TransaccionBodega> $transacciones
 * @property-read int|null $transacciones_count
 * @property-read Collection<int, Transferencia> $transferencias
 * @property-read int|null $transferencias_count
 * @property-read BitacoraVehicular|null $ultimaBitacora
 * @property-read UmbralFondosRotativos|null $umbral
 * @property-read User|null $user
 * @property-read Vendedor|null $vendedor
 * @method static Builder|Empleado acceptRequest(?array $request = null)
 * @method static Builder|Empleado filter(?array $request = null)
 * @method static Builder|Empleado habilitado()
 * @method static Builder|Empleado ignoreRequest(?array $request = null)
 * @method static Builder|Empleado newModelQuery()
 * @method static Builder|Empleado newQuery()
 * @method static Builder|Empleado query()
 * @method static Builder|Empleado find($value)
 * @method static Builder|Empleado setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Empleado setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Empleado setLoadInjectedDetection($load_default_detection)
 * @method static Builder|Empleado whereAcumulaFondosReserva($value)
 * @method static Builder|Empleado whereApellidos($value)
 * @method static Builder|Empleado whereAreaId($value)
 * @method static Builder|Empleado whereAutoidentificacionEtnica($value)
 * @method static Builder|Empleado whereBanco($value)
 * @method static Builder|Empleado whereCantonId($value)
 * @method static Builder|Empleado whereCargoId($value)
 * @method static Builder|Empleado whereCasaPropia($value)
 * @method static Builder|Empleado whereConvencional($value)
 * @method static Builder|Empleado whereCoordenadas($value)
 * @method static Builder|Empleado whereCorreoPersonal($value)
 * @method static Builder|Empleado whereCreatedAt($value)
 * @method static Builder|Empleado whereDepartamentoId($value)
 * @method static Builder|Empleado whereDireccion($value)
 * @method static Builder|Empleado whereEstaEnRolPago($value)
 * @method static Builder|Empleado whereEstado($value)
 * @method static Builder|Empleado whereEstadoCivilId($value)
 * @method static Builder|Empleado whereExtension($value)
 * @method static Builder|Empleado whereFechaIngreso($value)
 * @method static Builder|Empleado whereFechaNacimiento($value)
 * @method static Builder|Empleado whereFechaSalida($value)
 * @method static Builder|Empleado whereFechaVinculacion($value)
 * @method static Builder|Empleado whereFirmaUrl($value)
 * @method static Builder|Empleado whereFotoUrl($value)
 * @method static Builder|Empleado whereGenero($value)
 * @method static Builder|Empleado whereGrupoId($value)
 * @method static Builder|Empleado whereId($value)
 * @method static Builder|Empleado whereIdentidadGeneroId($value)
 * @method static Builder|Empleado whereIdentificacion($value)
 * @method static Builder|Empleado whereJefeId($value)
 * @method static Builder|Empleado whereNivelAcademico($value)
 * @method static Builder|Empleado whereNombres($value)
 * @method static Builder|Empleado whereNumCuentaBancaria($value)
 * @method static Builder|Empleado whereObservacion($value)
 * @method static Builder|Empleado whereOrientacionSexualId($value)
 * @method static Builder|Empleado whereRealizaFactura($value)
 * @method static Builder|Empleado whereReligionId($value)
 * @method static Builder|Empleado whereResponsableDiscapacitados($value)
 * @method static Builder|Empleado whereSalario($value)
 * @method static Builder|Empleado whereSupa($value)
 * @method static Builder|Empleado whereTallaCamisa($value)
 * @method static Builder|Empleado whereTallaGuantes($value)
 * @method static Builder|Empleado whereTallaPantalon($value)
 * @method static Builder|Empleado whereTallaZapato($value)
 * @method static Builder|Empleado whereTelefono($value)
 * @method static Builder|Empleado whereTelefonoEmpresa($value)
 * @method static Builder|Empleado whereTieneDiscapacidad($value)
 * @method static Builder|Empleado whereTipoContratoId($value)
 * @method static Builder|Empleado whereTipoSangre($value)
 * @method static Builder|Empleado whereTitulo($value)
 * @method static Builder|Empleado whereTrabajadorSustituto($value)
 * @method static Builder|Empleado whereUpdatedAt($value)
 * @method static Builder|Empleado where($string, $value, ?$value)
 * @method static Builder|Empleado whereUsuarioId($value)
 * @method static Builder|Empleado whereViveConDiscapacitados($value)
 * @mixin Eloquent
 */
class Empleado extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable, Searchable;

    //generos
    const MASCULINO = 'M';
    const FEMENINO = 'F';
    //Identificaciones etnicas
    //    const INDIGENA = 'INDIGENA';
    //    const AFRODECENDIENTE = 'AFRODECENDIENTE';
    //    const MESTIZO = 'MESTIZO';
    //    const BLANCO = 'BLANCO';
    //    const MONTUBIO = 'MONTUBIO';

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
        'desvinculado',
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
        'autoidentificacion_etnica',
        'trabajador_sustituto',
        'orientacion_sexual_id',
        'identidad_genero_id',
        'religion_id'
    ];

    private static array $whiteListFilter = [
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
        'es_reporte__saldo_actual',
        'empleados_autorizadores_gasto',

        'autoidentificacion_etnica',
        'trabajador_sustituto',
        'orientacion_sexual_id',
        'identidad_genero_id',
        'religion_id'
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
        'desvinculado' => 'boolean',
        'casa_propia' => 'boolean',
        'vive_con_discapacitados' => 'boolean',
        'responsable_discapacitados' => 'boolean',
        'esta_en_rol_pago' => 'boolean',
        'tiene_discapacidad' => 'boolean',
        'acumula_fondos_reserva' => 'boolean',
        'trabajador_sustituto' => 'boolean',

    ];

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'nombres' => $this->nombres,
            'apellidos' => $this->apellidos,
            'identificacion' => $this->identificacion,
            'apellidos_nombres' => $this->apellidos . ' ' . $this->nombres, // Concatenación
            'nombres_apellidos' => $this->nombres . ' ' . $this->apellidos, // Concatenación
            'estado' => $this->estado,
        ];
    }

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */

    /**
     * Obtiene el usuario que posee el perfil.
     */
    // Relacion uno a uno (inversa)
    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id', 'id');
    }

    public function delegado()
    {
        return $this->hasOne(EmpleadoDelegado::class);
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
        return $this->belongsTo(Canton::class)->with('provincia');
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
        return $this->belongsToMany(Vehiculo::class, 'veh_bitacoras_vehiculos', 'chofer_id', 'vehiculo_id')
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

    public function fichaSocioeconomica()
    {
        return $this->hasOne(FichaSocioeconomica::class);
    }

    public function visitasDomiciliarias()
    {
        return $this->hasMany(VisitaDomiciliaria::class);
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

    public function familiares()
    {
        return $this->hasMany(Familiares::class, 'empleado_id', 'id');
    }

    /**
     * Relación uno a uno.
     * Un empleado tiene uncuente aen un banco.
     */
    public function bancoInfo()
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
     * La función departamento() establece una relación de pertenencia con el modelo Departamento en PHP.
     *
     * @return BelongsTo función `departamento()` devuelve una definición de relación en Eloquent ORM de Laravel.
     * Se especifica que el modelo actual pertenece al modelo `Departamento`.
     */
    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    /**
     * La función "tareasCoordinador" define una relación donde un coordinador tiene muchas tareas.
     *
     * @return HasMany Esta función devuelve una colección de tareas asociadas con un coordinador. Utiliza la
     * relación `hasMany` en Laravel para definir la relación entre el modelo `Coordinador` y el modelo
     * `Tarea`, donde la clave externa `coordinador_id` se usa para vincular los dos modelos.
     */
    public function tareasCoordinador()
    {
        return $this->hasMany(Tarea::class, 'coordinador_id');
    }

    /**
     * La función `subtareasCoordinador` devuelve una relación que recupera subtareas a través de las
     * tareas de un coordinador.
     *
     * @return HasManyThrough Se está devolviendo un método de relación `subtareasCoordinador()`, que
     * define una relación HasManyThrough entre el modelo actual y el modelo `Subtarea` a través del modelo
     * `Tarea` usando la clave externa `coordinador_id`. Este método permite acceder a las subtareas
     * asociadas a un coordinador a través de la relación de tareas.
     */
    public function subtareasCoordinador(): HasManyThrough
    {
        return $this->hasManyThrough(Subtarea::class, Tarea::class, 'coordinador_id');
        //Log::channel('testing')->info('Log', ['Coordinador: ', $coordinador]);
        //return DB::table('subtareas')->join('tareas', 'subtareas.tarea_id', '=', 'tareas.id')->where('tareas.coordinador_id', 3);
    }

    /**
     * La función `extraerNombresApellidos` en PHP toma un objeto `Empleado` como entrada y devuelve el
     * nombre completo concatenando las propiedades `nombres` y `apellidos`.
     *
     * @param Empleado|null $empleado La función `extraerNombresApellidos` toma como parámetro un objeto
     * `Empleado`. La clase `Empleado` probablemente tenga propiedades como `nombres` y `apellidos` que
     * almacenan el nombre y apellido de un empleado respectivamente.
     *
     * @return string devuelve el nombre completo del empleado concatenando
     * las propiedades `nombres` y `apellidos` del objeto `Empleado` con un espacio en medio.
     */
    public static function extraerNombresApellidos(Empleado|null $empleado)
    {
        if (is_null($empleado)) return null;
        return $empleado->nombres . ' ' . $empleado->apellidos;
    }

    public static function extraerApellidosNombres(Empleado|null $empleado)
    {
        if (is_null($empleado)) return null;
        return $empleado->nombres . ' ' . $empleado->apellidos;
    }

    public static function obtenerNombresApellidosEmpleados(array $empleados_id)
    {
        $empleados = Empleado::whereIn('id', $empleados_id)->get();
        $nombresEmpleados = collect();
        foreach ($empleados as $empleado) {
            $nombresEmpleados->push(self::extraerNombresApellidos($empleado));
        }

        return $nombresEmpleados;
    }

    public static function obtenerEdad($empleado)
    {
        // Obtener la fecha actual
        $fechaActual = Carbon::now();

        // Calcular la diferencia de años
        return $fechaActual->diffInYears($empleado->fecha_nacimiento);
    }

    /**
     * La función "notificaciones" devuelve una colección de notificaciones asociadas a una instancia de
     * modelo específica.
     *
     * @return MorphMany devuelve una relación morphMany. Este método define una relación polimórfica "muchos"
     * entre el modelo actual y el modelo de Notificación. Permite que el modelo actual tenga múltiples
     * notificaciones asociadas a través de la relación polimórfica 'notificable'.
     */
    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }

    /**
     * La función umbral establece una relación uno a uno entre el objeto actual y la clase
     * UmbralFondosRotativos basada en los campos 'empleado_id' e 'id'.
     *
     * @return HasOne devuelve un método de relación denominado "umbral". Este método define una relación uno a
     * uno entre el modelo actual y el modelo "UmbralFondosRotativos" utilizando la columna "empleado_id"
     * del modelo actual y la columna "id" del modelo "UmbralFondosRotativos".
     */
    public function umbral()
    {
        return $this->hasOne(UmbralFondosRotativos::class, 'empleado_id', 'id');
    }

    /**
     * La función `egresoRolPago` define una relación donde un empleado tiene muchas instancias de
     * EgresoRolPago.
     *
     * @return HasMany devuelve un método de relación denominado `egresoRolPago`. Este método define una
     * relación de uno a muchos entre el modelo actual y el modelo `EgresoRolPago`. Especifica que el
     * modelo `Empleado` tiene muchos modelos `EgresoRolPago` asociados, usando la clave externa
     * `empleado_id` en el modelo `EgresoRolPago` y la clave local `
     */
    public function egresoRolPago()
    {
        return $this->hasMany(EgresoRolPago::class, 'empleado_id', 'id');
    }

    /**
     * La función "ordenesCompras" define una relación donde un usuario tiene muchas órdenes de compra.
     *
     * @return HasMany función devuelve una relación en la que un usuario tiene muchas instancias de
     * "OrdenCompra", con la clave externa 'solicitante_id' que vincula al usuario con los pedidos.
     */
    public function ordenesCompras()
    {
        return $this->hasMany(OrdenCompra::class, 'solicitante_id');
    }

    /**
     * La función "gastos" define una relación donde un usuario tiene muchos gastos.
     *
     * @return HasMany función `gastos()` devuelve una relación donde el modelo actual tiene muchas instancias
     * del modelo `Gasto`, con la clave externa `id_usuario` vinculando los dos modelos.
     */
    public function gastos()
    {
        return $this->hasMany(Gasto::class, 'id_usuario');
    }

    /**
     * La función "vendedor" establece una relación uno a uno con el modelo "Vendedor" en PHP.
     *
     * @return HasOne función `vendedor()` devuelve una definición de relación utilizando Eloquent ORM de
     * Laravel. Está definiendo una relación uno a uno donde el modelo actual tiene un modelo "Vendedor"
     * asociado.
     */
    public function vendedor()
    {
        return $this->hasOne(Vendedor::class);
    }

    public function conductor()
    {
        return $this->hasOne(Conductor::class);
    }

    /**
     * La función "respuestaCuestionarioEmpleado" define una relación de uno a muchos con el modelo
     * "RespuestaCuestionarioEmpleado" en PHP.
     *
     * @return HasMany método `respuestaCuestionarioEmpleado()` está devolviendo una relación definida por
     * `hasMany` con el modelo `RespuestaCuestionarioEmpleado`.
     */
    public function respuestaCuestionarioEmpleado()
    {
        return $this->hasMany(RespuestaCuestionarioEmpleado::class);
    }

    /**
     * Relacion polimorfica con Archivos uno a muchos.
     *
     */
    public function archivos()
    {
        return $this->morphMany(Archivo::class, 'archivable');
    }

    public static function empaquetarListado($empleados)
    {
        $results = [];
        $id = 0;
        $row = [];

        foreach ($empleados as $empleado) {

            $row['item'] = $id + 1;
            $row['id'] = $empleado->id;
            $row['apellidos'] = $empleado->apellidos;
            $row['nombres'] = $empleado->nombres;
            $row['identificacion'] = $empleado->identificacion;
            $row['departamento'] = $empleado->departamento != null ? $empleado->departamento->nombre : '';
            $row['area'] = $empleado->area != null ? $empleado->area->nombre : '';
            $row['cargo'] = $empleado->cargo != null ? $empleado->cargo->nombre : '';
            $row['salario'] = $empleado->salario;
            $results[$id] = $row;
            $id++;
        }
        return $results;
    }

    public function tiposDiscapacidades()
    {
        return $this->belongsToMany(TipoDiscapacidad::class, 'rrhh_empleado_tipo_discapacidad_porcentaje')->withPivot('porcentaje');
    }

    public function orientacionSexual()
    {
        return $this->hasOne(OrientacionSexual::class, 'id', 'orientacion_sexual_id');
    }

    public function identidadGenero()
    {
        return $this->hasOne(IdentidadGenero::class, 'id', 'identidad_genero_id');
    }

    public function religion()
    {
        return $this->hasOne(Religion::class, 'id', 'religion_id');
    }

    public function certificacionesEmpleado()
    {
        return $this->hasMany(CertificacionEmpleado::class, 'empleado_id');
    }

    public function zonas()
    {
        return $this->belongsToMany(Zona::class, 'seg_miembros_zonas', 'empleado_id', 'zona_id')
            ->withTimestamps();
    }


    /*********
     * Scopes
     *********/
    function scopeHabilitado($query)
    {
        return $query->where('id', '>=', 2)->where('estado', true); //->where('esta_en_rol_pago', true);
    }
}
