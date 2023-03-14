<?php

use App\Http\Controllers\DetalleProductoTransaccionController;
use App\Http\Controllers\TransaccionBodegaIngresoController;
use App\Http\Controllers\TransaccionBodegaEgresoController;
use App\Http\Controllers\ReporteControlMaterialController;
use App\Http\Controllers\MovimientoProductoController;
use App\Http\Controllers\EstadoTransaccionController;
use App\Http\Controllers\ControlAsistenciaController;
use App\Http\Controllers\TransaccionBodegaController;
use App\Http\Controllers\ProductoEnPerchaController;
use App\Http\Controllers\DetalleProductoController;
use App\Http\Controllers\RegistroTendidoController;
use App\Http\Controllers\TipoTransaccionController;
use App\Http\Controllers\TrabajoAsignadoController;
use App\Http\Controllers\ImagenProductoController;
use App\Http\Controllers\CodigoClienteController;
use App\Http\Controllers\UnidadMedidaController;
use App\Http\Controllers\ControlCambioController;
use App\Http\Controllers\ValidarCedulaController;
use App\Http\Controllers\TransferenciaController;
use App\Http\Controllers\AutorizacionController;
use App\Http\Controllers\TipoElementoController;
use App\Http\Controllers\ClienteFinalController;
use App\Http\Controllers\ControlStockController;
use App\Http\Controllers\TipoTrabajoController;
use App\Http\Controllers\ProcesadorController;
use App\Http\Controllers\ActivoFijoController;
use App\Http\Controllers\ArchivoSubtareaController;
use App\Http\Controllers\CargoController;
use App\Http\Controllers\DevolucionController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\PermisoRolController;
use App\Http\Controllers\CondicionController;
use App\Http\Controllers\UbicacionController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\TipoFibraController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\TraspasoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\SubtareaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\TableroController;
use App\Http\Controllers\TendidoController;
use App\Http\Controllers\ModeloController;
use App\Http\Controllers\MotivoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\PerchaController;
use App\Http\Controllers\DiscoController;
use App\Http\Controllers\EmergenciaController;
use App\Http\Controllers\FondosRotativos\Saldo\AcreditacionesController;
use App\Http\Controllers\FondosRotativos\Saldo\SaldoGrupoController;
use App\Http\Controllers\FondosRotativos\Saldo\TipoSaldoController;
use App\Http\Controllers\FondosRotativos\TipoFondoController;
use App\Http\Controllers\FondosRotativos\Gasto\DetalleViaticoController;
use App\Http\Controllers\FondosRotativos\Gasto\GastoController;
use App\Http\Controllers\FondosRotativos\Gasto\SubDetalleViaticoController;
use App\Http\Controllers\FondosRotativos\Saldo\TransferenciasController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\SpanController;
use App\Http\Controllers\HiloController;
use App\Http\Controllers\NotificacionController;
use App\Http\Resources\UserInfoResource;
use App\Http\Controllers\PisoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RamController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\TrabajoController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Provincia;
use App\Models\Canton;
use App\Models\Notificacion;
use App\Models\Parroquia;
use App\Models\User;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('tablero', [TableroController::class, 'index']);
Route::get('permisos_administrar', [PermisoController::class, 'listarPermisos']);
Route::post('asignar-permisos', [PermisoRolController::class, 'asignarPermisos']);
Route::post('usuarios/login', [LoginController::class, 'login']);
Route::post('usuarios/recuperar-password', [UserController::class, 'recuperarPassword']);
Route::post('usuarios/reset-password', [UserController::class, 'resetearPassword']);
Route::post('usuarios/validar-token', [UserController::class, 'updateContrasenaRecovery']);
Route::middleware('auth:sanctum')->prefix('usuarios')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::post('registrar', [UserController::class, 'store']);
    Route::post('logout', [LoginController::class, 'logout']);
    Route::get('ver/{empleado}', [UserController::class, 'show']);
    Route::put('actualizar/{empleado}', [UserController::class, 'update']);
    Route::post('cambiar-contrasena', [UserController::class, 'updatePassword']);
});

Route::group(['prefix' => '/permisos'], function () {
    Route::get('verpermisos/{rol}', [PermisoRolController::class, 'listarPermisos']);
    Route::post('actualizarpermisos/{rol}', [PermisoRolController::class, 'asignarPermisos']);
});

// El frontend usa esta ruta para verificar si está autenticado
Route::middleware('auth:sanctum')->get('/user', fn (Request $request) => new UserInfoResource($request->user()));

// El frontend usa esta ruta para obtener los roles y permisos del usuario autenticado
// Route::middleware('auth:sanctum')->get('/user/roles', fn (Request $request) => $request->user()->getRoleNames());
/* Route::middleware('auth:sanctum')->get('/user/permisos', function (Request $request) {
    return $request->user()->allPermissions;
}); */

Route::post('validar_cedula', [ValidarCedulaController::class, 'validarCedula']);
Route::post('validar_ruc', [ValidarCedulaController::class, 'validarRUC']);

Route::apiResources(
    [
        'activos-fijos' => ActivoFijoController::class,
        'autorizaciones' => AutorizacionController::class,
        'cargos' => CargoController::class,
        'categorias' => CategoriaController::class,
        'clientes' => ClienteController::class,
        'condiciones' => CondicionController::class,
        'control-stocks' => ControlStockController::class,
        'codigos-clientes' => CodigoClienteController::class,
        'devoluciones' => DevolucionController::class,
        'detalles-productos-transacciones' => DetalleProductoTransaccionController::class,
        'discos' => DiscoController::class,
        'empleados' => EmpleadoController::class,
        'empresas' => EmpresaController::class,
        'estados' => EstadoTransaccionController::class,
        'grupos' => GrupoController::class,
        'hilos' => HiloController::class,
        'inventarios' => InventarioController::class,
        'imagenes-productos' => ImagenProductoController::class,
        'marcas' => MarcaController::class,
        'modelos' => ModeloController::class,
        'movimientos-productos' => MovimientoProductoController::class,
        'motivos' => MotivoController::class,
        'notificaciones' => NotificacionController::class,
        'pedidos' => PedidoController::class,
        'procesadores' => ProcesadorController::class,
        'productos' => ProductoController::class,
        'productos-perchas' => ProductoEnPerchaController::class,
        'perchas' => PerchaController::class,
        'permisos' => PermisoController::class,
        'pisos' => PisoController::class,
        'detalles' => DetalleProductoController::class,
        'proveedores' => ProveedorController::class,
        'rams' => RamController::class,
        'roles' => RolController::class,
        'sucursales' => SucursalController::class,
        'spans' => SpanController::class,
        'tipos-fibras' => TipoFibraController::class,
        'tipos-transacciones' => TipoTransaccionController::class,
        'transacciones' => TransaccionBodegaController::class,
        'transacciones-ingresos' => TransaccionBodegaIngresoController::class,
        'transacciones-egresos' => TransaccionBodegaEgresoController::class,
        'transferencias' => TransferenciaController::class,
        'traspasos' => TraspasoController::class,
        'ubicaciones' => UbicacionController::class,
        'unidades-medidas' => UnidadMedidaController::class,
        'tareas' => TareaController::class,
        'subtareas' => SubtareaController::class,
        'tipos-trabajos' => TipoTrabajoController::class,
        'control-asistencias' => ControlAsistenciaController::class,
        'control-cambios' => ControlCambioController::class,
        'tipos-elementos' => TipoElementoController::class,
        'clientes-finales' => ClienteFinalController::class,
        'proyectos' => ProyectoController::class,
        'archivos-subtareas' => ArchivoSubtareaController::class,
        'registros-tendidos' => RegistroTendidoController::class,
        'fondos-rotativos/detalles-viaticos' => DetalleViaticoController::class,
        'fondos-rotativos/sub-detalles-viaticos' => SubDetalleViaticoController::class,
        'fondos-rotativos/gastos' => GastoController::class,
        'fondos-rotativos/tipo-saldo' => TipoSaldoController::class,
        'fondos-rotativos/tipo-fondo' => TipoFondoController::class,
        'fondos-rotativos/saldo-grupo' => SaldoGrupoController::class,
        'fondos-rotativos/acreditacion' => AcreditacionesController::class,
        'fondos-rotativos/transferencia' => TransferenciasController::class,
    ],
    [
        'parameters' => [
            'activos-fijos' => 'activo',
            'autorizaciones' => 'autorizacion',
            'condiciones' => 'condicion',
            'codigos-clientes' => 'codigo_cliente',
            'devoluciones' => 'devolucion',
            'detalles-productos-transacciones' => 'detalle',
            'imagenesproductos' => 'imagenproducto',
            'movimientos-productos' => 'movimiento',
            'notificaciones' => 'notificacion',
            'procesadores' => 'procesador',
            'proveedores' => 'proveedor',
            'productos-perchas' => 'producto_en_percha',
            'sucursales' => 'sucursal',
            'tipos-transacciones' => 'tipo_transaccion',
            'transacciones' => 'transaccion',
            'transacciones-ingresos' => 'transaccion',
            'transacciones-egresos' => 'transaccion',
            'ubicaciones' => 'ubicacion',
            'tipos-trabajos' => 'tipo_trabajo',
            'tipos-elementos' => 'tipo_elemento',
            'tipos-fibras' => 'tipo_fibra',
            'clientes-finales' => 'cliente_final',
            'archivos-subtareas' => 'archivo-subtarea',
            'unidades-medidas' => 'unidad',
            'registros-tendidos' => 'registro-tendido'
        ],
        'middleware' => ['auth:sanctum']
    ]
);

Route::get('bobinas-empleado-tarea', [TransaccionBodegaEgresoController::class, 'obtenerBobinas'])->middleware('auth:sanctum');
Route::get('materiales-empleado-tarea', [TransaccionBodegaEgresoController::class, 'obtenerMateriales'])->middleware('auth:sanctum');

Route::controller(TransaccionBodegaEgresoController::class)->prefix('transacciones-egresos')->group(function () {
    // Route::get('materiales-grupo-tarea', 'obtenerMateriales');
    Route::get('prueba/{tarea}', 'prueba');
    Route::get('materiales-despachados/{tarea}', 'materialesDespachados');
    Route::get('show-preview/{transaccion}', 'showPreview'); //->name('imprimir-transaccion');
    Route::get('materiales/{tarea}', 'obtenerTransaccionPorTarea');
});
/**
 * Rutas para imprimir PDFs
 */
Route::get('activos-fijos/imprimir/{activo}', [ActivoFijoController::class, 'imprimir'])->middleware('auth:sanctum');
Route::get('pedidos/imprimir/{pedido}', [PedidoController::class, 'imprimir'])->middleware('auth:sanctum');
Route::get('devoluciones/imprimir/{devolucion}', [DevolucionController::class, 'imprimir'])->middleware('auth:sanctum');
Route::get('traspasos/imprimir/{traspaso}', [TraspasoController::class, 'imprimir'])->middleware('auth:sanctum');
Route::get('transacciones-ingresos/imprimir/{transaccion}', [TransaccionBodegaIngresoController::class, 'imprimir'])->middleware('auth:sanctum');
Route::get('transacciones-egresos/imprimir/{transaccion}', [TransaccionBodegaEgresoController::class, 'imprimir'])->middleware('auth:sanctum');

Route::post('devoluciones/anular/{devolucion}', [DevolucionController::class, 'anular']);
Route::post('notificaciones/marcar-leida/{notificacion}', [NotificacionController::class, 'leida']);
//show-preview
Route::get('devoluciones/show-preview/{devolucion}', [DevolucionController::class, 'showPreview']);
Route::get('pedidos/show-preview/{pedido}', [PedidoController::class, 'showPreview']);
Route::get('traspasos/show-preview/{traspaso}', [TraspasoController::class, 'showPreview']);
Route::get('transacciones-ingresos/show-preview/{transaccion}', [TransaccionBodegaIngresoController::class, 'showPreview']); //->name('imprimir-transaccion');

Route::get('buscarDetalleInventario', [InventarioController::class, 'buscar']);
Route::post('buscarIdsEnInventario', [InventarioController::class, 'buscarProductosSegunId']);
Route::post('buscarDetallesEnInventario', [InventarioController::class, 'buscarProductosSegunDetalleId']);

Route::get('all-items', [InventarioController::class, 'vista']);

Route::get('empleados/obtenerTecnicos/{grupo_id}', [EmpleadoController::class, 'obtenerTecnicos'])->middleware('auth:sanctum');

// Estados de las subtareas
Route::middleware('auth:sanctum')->prefix('subtareas')->group(function () {
    Route::post('agendar/{subtarea}', [SubtareaController::class, 'agendar']);
    Route::post('asignar/{subtarea}', [SubtareaController::class, 'asignar']);
    Route::post('ejecutar/{subtarea}', [SubtareaController::class, 'ejecutar']);
    Route::post('realizar/{subtarea}', [SubtareaController::class, 'realizar']);
    Route::post('finalizar/{subtarea}', [SubtareaController::class, 'finalizar']);
    Route::post('pausar/{subtarea}', [SubtareaController::class, 'pausar']);
    Route::post('reanudar/{subtarea}', [SubtareaController::class, 'reanudar']);
    Route::post('pendiente/{subtarea}', [SubtareaController::class, 'marcarComoPendiente']);
    Route::post('suspender/{subtarea}', [SubtareaController::class, 'suspender']);
    Route::post('cancelar/{subtarea}', [SubtareaController::class, 'cancelar']);
    Route::get('pausas/{subtarea}', [SubtareaController::class, 'obtenerPausas']);
});

Route::put('subtareas/actualizar-fechas-reagendar/{subtarea}', [SubtareaController::class, 'actualizarFechasReagendar'])->middleware('auth:sanctum');

/* Route::middleware('auth:sanctum')->prefix('subtareas')->group(function () {
    Route::post('asignar/{trabajo}', [TrabajoController::class, 'asignar']);
    Route::post('ejecutar/{trabajo}', [TrabajoController::class, 'ejecutar']);
    Route::post('realizar/{trabajo}', [TrabajoController::class, 'realizar']);
    Route::post('finalizar/{trabajo}', [TrabajoController::class, 'finalizar']);
    Route::post('pausar/{trabajo}', [TrabajoController::class, 'pausar']);
    Route::post('reanudar/{trabajo}', [TrabajoController::class, 'reanudar']);
    Route::post('suspender/{trabajo}', [TrabajoController::class, 'suspender']);
    Route::post('cancelar/{trabajo}', [TrabajoController::class, 'cancelar']);
    Route::get('pausas/{trabajo}', [TrabajoController::class, 'obtenerPausas']);
}); */

Route::middleware('auth:sanctum')->group(function () {
    // Subtareas
    Route::get('trabajo-asignado', [TrabajoAsignadoController::class, 'index']);
    Route::post('designar-lider-grupo', [EmpleadoController::class, 'designarLiderGrupo']);
    Route::post('designar-secretario-grupo', [EmpleadoController::class, 'designarSecretarioGrupo']);
    
    // Fecha y hora del sistema
    Route::get('obtener-fecha', fn () => Carbon::now()->format('d-m-Y'));
    Route::get('obtener-hora', fn () => Carbon::now()->format('H:i:s'));
    Route::get('provincias', fn () => ['results' => Provincia::all()]);
    Route::get('cantones', fn () => ['results' => Canton::all()]);
    Route::get('parroquias', fn () => ['results' => Parroquia::all()]);
    Route::get('usuarios-autorizadores', [UserController::class, 'autorizationUser']);
    Route::get('lista-usuarios', [UserController::class, 'listaUsuarios']);
    Route::post('fondos-rotativos/reporte/fecha/{tipo}', [GastoController::class, 'generar_reporte']);
    Route::post('fondos-rotativos/reporte/saldo_actual/{tipo}', [SaldoGrupoController::class, 'saldo_actual']);
    Route::get('fondos-rotativos/ultimo_saldo/{id}', [SaldoGrupoController::class, 'saldo_actual_usuario']);
    Route::post('fondos-rotativos/autorizaciones_fecha/{tipo}', [GastoController::class, 'reporte_autorizaciones']);
    Route::post('fondos-rotativos/consolidado/{tipo}', [SaldoGrupoController::class, 'consolidado']);
    Route::post('fondos-rotativos/consolidado_filtrado/{tipo}', [SaldoGrupoController::class, 'consolidado_filtrado']);
    Route::get('fondos-rotativos/gastocontabilidad', [SaldoGrupoController::class, 'gastocontabilidad']);
    Route::get('fondos-rotativos/autorizaciones_gastos', [GastoController::class, 'autorizaciones_gastos']);
    Route::post('fondos-rotativos/aprobar-gasto', [GastoController::class, 'aprobar_gasto']);
    Route::post('fondos-rotativos/rechazar-gasto', [GastoController::class, 'rechazar_gasto']);
});

// Tendidos
Route::apiResource('tendidos', TendidoController::class)->except('show');
Route::get('tendidos/{subtarea}', [TendidoController::class, 'show']);

// Emergencias
Route::apiResource('emergencias', EmergenciaController::class);

// Reportes de material
Route::get('reportes-control-materiales', [ReporteControlMaterialController::class, 'index'])->middleware('auth:sanctum');
