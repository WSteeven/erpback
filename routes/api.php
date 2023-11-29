<?php

use App\Http\Controllers\DetalleProductoTransaccionController;
use App\Http\Controllers\TransaccionBodegaIngresoController;
use App\Http\Controllers\TransaccionBodegaEgresoController;
use App\Http\Controllers\MovimientoProductoController;
use App\Http\Controllers\EstadoTransaccionController;
use App\Http\Controllers\TransaccionBodegaController;
use App\Http\Controllers\ProductoEnPerchaController;
use App\Http\Controllers\DetalleProductoController;
use App\Http\Controllers\TipoTransaccionController;
use App\Http\Controllers\ImagenProductoController;
use App\Http\Controllers\CodigoClienteController;
use App\Http\Controllers\UnidadMedidaController;
use App\Http\Controllers\ValidarCedulaController;
use App\Http\Controllers\TransferenciaController;
use App\Http\Controllers\AutorizacionController;
use App\Http\Controllers\ControlStockController;
use App\Http\Controllers\ProcesadorController;
use App\Http\Controllers\ActivoFijoController;
use App\Http\Controllers\ArchivoController;
use App\Http\Controllers\AuditoriaController;
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
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ComprobanteController;
use App\Http\Controllers\ConfiguracionGeneralController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\TableroController;
use App\Http\Controllers\ModeloController;
use App\Http\Controllers\MotivoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\PerchaController;
use App\Http\Controllers\DiscoController;
use App\Http\Controllers\FondosRotativos\Saldo\AcreditacionesController;
use App\Http\Controllers\FondosRotativos\Saldo\SaldoGrupoController;
use App\Http\Controllers\FondosRotativos\Saldo\TipoSaldoController;
use App\Http\Controllers\FondosRotativos\TipoFondoController;
use App\Http\Controllers\FondosRotativos\Gasto\DetalleViaticoController;
use App\Http\Controllers\FondosRotativos\Gasto\GastoController;
use App\Http\Controllers\FondosRotativos\Gasto\GastoCoordinadorController;
use App\Http\Controllers\FondosRotativos\Gasto\MotivoGastoController;
use App\Http\Controllers\FondosRotativos\Gasto\SubDetalleViaticoController;
use App\Http\Controllers\FondosRotativos\Saldo\TransferenciasController;
use App\Http\Controllers\FormaPagoController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\SpanController;
use App\Http\Controllers\HiloController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\ParroquiaController;
use App\Http\Resources\UserInfoResource;
use App\Http\Controllers\PisoController;
use App\Http\Controllers\PreingresoMaterialController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RamController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\EstadoPermisoEmpleadoController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\MotivoPermisoEmpleadoController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\PermisoEmpleadoController;
use App\Http\Controllers\RecursosHumanos\NominaPrestamos\RolPagosController;
use App\Http\Controllers\RecursosHumanos\TipoContratoController;
use App\Http\Controllers\RolController;
use App\Http\Resources\CantonResource;
use App\Http\Resources\ParroquiaResource;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Provincia;
use App\Models\Canton;
use App\Models\Empleado;
use App\Models\Pais;
use App\Models\Parroquia;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;

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
Route::get('auditorias', [AuditoriaController::class, 'index']);
Route::get('permisos_roles_usuario', [PermisoController::class, 'listarPermisosRoles']);
Route::get('permisos_administrar', [PermisoController::class, 'listarPermisos']);
Route::post('asignar-permisos', [PermisoRolController::class, 'asignarPermisos']);
Route::post('asignar-permisos-usuario', [PermisoRolController::class, 'asignarPermisosUsuario']);
Route::post('crear-permiso', [PermisoRolController::class, 'crearPermisoRol']);
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
// Configuracion general
Route::get('configuracion', [ConfiguracionGeneralController::class, 'index']);
Route::middleware('auth:sanctum')->post('configuracion', [ConfiguracionGeneralController::class, 'store']);

Route::post('validar_cedula', [ValidarCedulaController::class, 'validarCedula']);
Route::post('validar_ruc', [ValidarCedulaController::class, 'validarRUC']);

Route::apiResource('archivos', ArchivoController::class)->only('destroy');
Route::apiResources(
    [
        'activos-fijos' => ActivoFijoController::class,
        'autorizaciones' => AutorizacionController::class,
        'cargos' => CargoController::class,
        'categorias' => CategoriaController::class,
        'clientes' => ClienteController::class,
        'condiciones' => CondicionController::class,
        // 'configuracion' => ConfiguracionGeneralController::class,
        'control-stocks' => ControlStockController::class,
        'comprobantes' => ComprobanteController::class,
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
        'preingresos' => PreingresoMaterialController::class,
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
        'parroquias' => ParroquiaController::class,


        'forma_pago' => FormaPagoController::class
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
            'unidades-medidas' => 'unidad',
            'tipos-fibras' => 'tipo_fibra',
        ],
        'middleware' => ['auth:sanctum']
    ]
);

/**
 * Rutas para obtener empleados por cierto rol
 */
Route::get('empleados-roles',  [EmpleadoController::class, 'empleadosRoles'])->middleware('auth:sanctum'); //usuarios con uno o varios roles enviados desde el front
/**
 * Ruta para obtener empleados por cierto permiso
 */
Route::get('empleados-permisos', [EmpleadoController::class, 'empleadoPermisos'] )->middleware('auth:sanctum'); //usuarios con uno o varios permisos enviados desde el front


/**
 * Rutas para imprimir PDFs
 */
Route::get('activos-fijos/imprimir/{activo}', [ActivoFijoController::class, 'imprimir'])->middleware('auth:sanctum');
Route::get('pedidos/imprimir/{pedido}', [PedidoController::class, 'imprimir'])->middleware('auth:sanctum');
Route::get('devoluciones/imprimir/{devolucion}', [DevolucionController::class, 'imprimir'])->middleware('auth:sanctum');
Route::get('traspasos/imprimir/{traspaso}', [TraspasoController::class, 'imprimir'])->middleware('auth:sanctum');
Route::get('transacciones-ingresos/imprimir/{transaccion}', [TransaccionBodegaIngresoController::class, 'imprimir'])->middleware('auth:sanctum');
Route::get('transacciones-egresos/imprimir/{transaccion}', [TransaccionBodegaEgresoController::class, 'imprimir'])->middleware('auth:sanctum');

/*********************************************************
 * ANULACIONES
 ********************************************************/
Route::post('detalles/anular/{detalle}', [DetalleProductoController::class, 'desactivar']);
Route::get('transacciones-ingresos/anular/{transaccion}', [TransaccionBodegaIngresoController::class, 'anular'])->middleware('auth:sanctum');
Route::get('transacciones-egresos/anular/{transaccion}', [TransaccionBodegaEgresoController::class, 'anular'])->middleware('auth:sanctum');
Route::post('devoluciones/anular/{devolucion}', [DevolucionController::class, 'anular']);
Route::post('pedidos/anular/{pedido}', [PedidoController::class, 'anular']);
Route::post('pedidos/marcar-completado/{pedido}', [PedidoController::class, 'marcarCompletado']);
Route::post('proveedores/anular/{proveedor}', [ProveedorController::class, 'anular']);
Route::post('notificaciones/marcar-leida/{notificacion}', [NotificacionController::class, 'leida']);
/******************************************************
 * REPORTES DE BODEGA
 ******************************************************/
//Reportes de pedidos
Route::post('pedidos/reportes', [PedidoController::class, 'reportes']);
//Reportes de ingresos y egresos
Route::post('transacciones-ingresos/reportes', [TransaccionBodegaIngresoController::class, 'reportes']);
Route::post('transacciones-egresos/reportes', [TransaccionBodegaEgresoController::class, 'reportes']);
//Reportes inventario
Route::get('reporte-inventario/pdf/{id}', [InventarioController::class, 'reporteInventarioPdf']);
Route::get('reporte-inventario/excel/{id}', [InventarioController::class, 'reporteInventarioExcel']);
Route::post('reporte-inventario/kardex', [InventarioController::class, 'kardex']);

/******************************************************
 * REPORTES DE COMPRAS Y PROVEEDORES
 ******************************************************/
Route::post('proveedores/reportes', [ProveedorController::class, 'reportes']);
Route::get('proveedores/imprimir-calificacion/{proveedor}', [ProveedorController::class, 'reporteCalificacion']);


//gestionar egresos
Route::get('gestionar-egresos', [TransaccionBodegaEgresoController::class, 'showEgresos'])->middleware('auth:sanctum');

Route::get('comprobantes-filtrados', [TransaccionBodegaEgresoController::class, 'filtrarComprobante'])->middleware('auth:sanctum');
Route::get('egresos-filtrados', [TransaccionBodegaEgresoController::class, 'filtrarEgresos'])->middleware('auth:sanctum');


//show-preview
Route::get('devoluciones/show-preview/{devolucion}', [DevolucionController::class, 'showPreview']);
Route::get('pedidos/show-preview/{pedido}', [PedidoController::class, 'showPreview']);
Route::put('pedidos/corregir-pedido/{pedido}', [PedidoController::class, 'corregirPedido']);
Route::post('pedidos/eliminar-item', [PedidoController::class, 'eliminarDetallePedido']);
Route::get('traspasos/show-preview/{traspaso}', [TraspasoController::class, 'showPreview']);
Route::get('transacciones-ingresos/show-preview/{transaccion}', [TransaccionBodegaIngresoController::class, 'showPreview']);
Route::get('transacciones-egresos/show-preview/{transaccion}', [TransaccionBodegaEgresoController::class, 'showPreview']);
Route::get('proveedores/show-preview/{proveedor}', [ProveedorController::class, 'showPreview']);

Route::put('comprobantes/{transaccion}', [TransaccionBodegaEgresoController::class, 'updateComprobante'])->middleware('auth:sanctum');
Route::get('buscarDetalleInventario', [InventarioController::class, 'buscar']);
Route::post('buscarIdsEnInventario', [InventarioController::class, 'buscarProductosSegunId']);
Route::post('buscarDetallesEnInventario', [InventarioController::class, 'buscarProductosSegunDetalleId']);



Route::get('all-items', [InventarioController::class, 'vista']);

Route::get('empleados/obtenerTecnicos/{grupo_id}', [EmpleadoController::class, 'obtenerTecnicos'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    // Fecha y hora del sistema
    Route::get('obtener-fecha', fn () => Carbon::now()->format('d-m-Y'));
    Route::get('obtener-hora', fn () => Carbon::now()->format('H:i:s'));
    Route::get('paises', fn () => ['results' => Pais::filter()->get()]);
    Route::get('provincias', fn (Request $request) => ['results' => Provincia::filter()->get()]);
    Route::get('cantones', function () {
        $results = Canton::ignoreRequest(['campos'])->filter()->get();
        $results = CantonResource::collection($results);
        //  return 'results' => Canton::ignoreRequest(['campos'])->filter()->get());
        return response()->json(compact('results'));
    });
    Route::get('usuarios-autorizadores', [UserController::class, 'autorizationUser']);
    Route::get('lista-usuarios', [UserController::class, 'listaUsuarios']);

});


/**
 * Auditorias
 */
Route::get('w-auditoria', [PedidoController::class, 'auditoria'])->middleware('auth:sanctum');

/**
 * ______________________________________________________________________________________
 * RUTAS DE MANEJO POLIMORFICO DE ARCHIVOS
 * ______________________________________________________________________________________
 */

/**
 * Listar Archivos
 */
Route::get('empresas/files/{empresa}', [EmpresaController::class, 'indexFiles'])->middleware('auth:sanctum');
Route::get('proveedores/files/{proveedor}', [ProveedorController::class, 'indexFilesDepartamentosCalificadores'])->middleware('auth:sanctum');
Route::get('preingresos/files/{preingreso}', [PreingresoMaterialController::class, 'indexFiles'])->middleware('auth:sanctum');
Route::get('devoluciones/files/{devolucion}', [DevolucionController::class, 'indexFiles'])->middleware('auth:sanctum');

/**
 * Subidas de archivos
 */
Route::post('empresas/files/{empresa}', [EmpresaController::class, 'storeFiles'])->middleware('auth:sanctum');
Route::post('preingresos/files/{preingreso}', [PreingresoMaterialController::class, 'storeFiles'])->middleware('auth:sanctum');
Route::post('devoluciones/files/{devolucion}', [DevolucionController::class, 'storeFiles'])->middleware('auth:sanctum');
