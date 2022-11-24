<?php

use App\Http\Controllers\ActivoFijoController;
use App\Http\Controllers\AutorizacionController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ClienteFinalController;
use App\Http\Controllers\CodigoClienteController;
use App\Http\Controllers\CondicionController;
use App\Http\Controllers\ControlAsistenciaController;
use App\Http\Controllers\ControlCambioController;
use App\Http\Controllers\ControlStockController;
use App\Http\Controllers\DetalleProductoController;
use App\Http\Controllers\DetalleProductoTransaccionController;
use App\Http\Controllers\DiscoController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\EstadoTransaccionController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\HiloController;
use App\Http\Controllers\ImagenProductoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\ModeloController;
use App\Http\Controllers\MotivoController;
use App\Http\Controllers\MovimientoProductoController;
use App\Http\Controllers\PerchaController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\PermisoRolController;
use App\Http\Controllers\PisoController;
use App\Http\Controllers\PrestamoTemporalController;
use App\Http\Controllers\ProcesadorController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProductoEnPerchaController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\RamController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\SpanController;
use App\Http\Controllers\SubtareaAsignadaController;
use App\Http\Controllers\SubtareaController;
use App\Http\Controllers\SubtipoTransaccionController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\TipoElementoController;
use App\Http\Controllers\TipoFibraController;
use App\Http\Controllers\TipoTareaController;
use App\Http\Controllers\TipoTransaccionController;
use App\Http\Controllers\TransaccionBodegaController;
use App\Http\Controllers\UbicacionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ValidarCedulaController;
use App\Http\Controllers\TableroController;
use App\Http\Controllers\TransaccionBodegaEgresoController;
use App\Http\Controllers\TransaccionBodegaIngresoController;
use App\Http\Resources\UserResource;
use App\Models\Canton;
use App\Models\ClienteFinal;
use App\Models\Contacto;
use App\Models\Empleado;
use App\Models\Inventario;
use App\Models\PrestamoTemporal;
use App\Models\ProductoEnPercha;
use App\Models\Provincia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

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
// Rutas de user (para pruebas) 
Route::prefix('usuarios')->group(function () {
    Route::get('/', [UserController::class, 'index'])->middleware('auth:sanctum');
    Route::post('registrar', [UserController::class, 'store'])->middleware('auth:sanctum');
    Route::post('login', [UserController::class, 'login']);
    Route::post('logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('ver/{empleado}', [UserController::class, 'show'])->middleware('auth:sanctum');
    Route::put('actualizar/{empleado}', [UserController::class, 'update'])->middleware('auth:sanctum');
});

Route::group(['prefix' => '/permisos'], function () {
    Route::get('verpermisos/{rol}', [PermisoRolController::class, 'listarPermisos']);
    Route::post('actualizarpermisos/{rol}', [PermisoRolController::class, 'asignarPermisos']);
});

// El frontend usa esta ruta para verificar si está autenticado
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return new UserResource($request->user());
});

// El frontend usa esta ruta para obtener los roles y permisos del usuario autenticado
Route::middleware('auth:sanctum')->get('/user/roles', function (Request $request) {
    return $request->user()->getRoleNames();
});
Route::middleware('auth:sanctum')->get('/user/permisos', function (Request $request) {
    return $request->user()->allPermissions;
});


Route::post('validar_cedula', [ValidarCedulaController::class, 'validarCedula']);
Route::post('validar_ruc', [ValidarCedulaController::class, 'validarRUC']);

Route::get('provincias', fn () => ['results' => Provincia::all()])->middleware('auth:sanctum');
Route::get('cantones', fn () => ['results' => Canton::all()])->middleware('auth:sanctum');

Route::apiResources(
    [
        'activos-fijos' => ActivoFijoController::class,
        'autorizaciones' => AutorizacionController::class,
        'categorias' => CategoriaController::class,
        'clientes' => ClienteController::class,
        'condiciones' => CondicionController::class,
        'control-stocks' => ControlStockController::class,
        'codigos-clientes' => CodigoClienteController::class,
        'discos' => DiscoController::class,
        'detalles-productos-transacciones' => DetalleProductoTransaccionController::class,
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
        'procesadores' => ProcesadorController::class,
        'prestamos' => PrestamoTemporalController::class,
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
        'subtipos-transacciones' => SubtipoTransaccionController::class,
        'transacciones' => TransaccionBodegaController::class,
        'transacciones-ingresos' => TransaccionBodegaIngresoController::class,
        'transacciones-egresos' => TransaccionBodegaEgresoController::class,
        'ubicaciones' => UbicacionController::class,
        'tareas' => TareaController::class,
        'subtareas' => SubtareaController::class,
        'tipos-tareas' => TipoTareaController::class,
        'control-asistencias' => ControlAsistenciaController::class,
        'control-cambios' => ControlCambioController::class,
        'tipos-elementos' => TipoElementoController::class,
        'clientes-finales' => ClienteFinalController::class,
    ],
    [
        'parameters' => [
            'activos_fijos' => 'activo',
            'autorizaciones' => 'autorizacion',
            'condiciones' => 'condicion',
            'codigos-clientes' => 'codigo_cliente',
            'detalles-productos-transacciones' => 'detalle',
            'imagenesproductos' => 'imagenproducto',
            'movimientos-productos' => 'movimiento',
            'procesadores' => 'procesador',
            'proveedores' => 'proveedor',
            'productos-perchas' => 'producto_en_percha',
            'sucursales' => 'sucursal',
            'tipos-transacciones' => 'tipo_transaccion',
            'subtipos-transacciones' => 'subtipo_transaccion',
            'transacciones' => 'transaccion',
            'transacciones-ingresos' => 'transaccion',
            'transacciones-egresos' => 'transaccion',
            'ubicaciones' => 'ubicacion',
            'tipos-tareas' => 'tipo_tarea',
            'tipos-elementos' => 'tipo_elemento',
            'tipos-fibras' => 'tipo_fibra',
            'clientes-finales' => 'cliente_final'
        ],
        'middleware' => ['auth:sanctum']
    ]
);

Route::get('prestamos/imprimir/{prestamo}', [PrestamoTemporalController::class, 'print']);
Route::get('buscarDetalleInventario', [InventarioController::class, 'buscar']);

Route::get('all-items', [InventarioController::class, 'vista']);

Route::get('empleados/obtenerTecnicos/{grupo_id}', [EmpleadoController::class, 'obtenerTecnicos'])->middleware('auth:sanctum');

// Estados de las subtareas
Route::post('subtareas/asignar/{subtarea}', [SubtareaController::class, 'asignar'])->middleware('auth:sanctum');
Route::post('subtareas/ejecutar/{subtarea}', [SubtareaController::class, 'ejecutar'])->middleware('auth:sanctum');
Route::post('subtareas/realizar/{subtarea}', [SubtareaController::class, 'realizar'])->middleware('auth:sanctum');
Route::post('subtareas/pausar/{subtarea}', [SubtareaController::class, 'pausar'])->middleware('auth:sanctum');
Route::post('subtareas/reanudar/{subtarea}', [SubtareaController::class, 'reanudar'])->middleware('auth:sanctum');
Route::post('subtareas/suspender/{subtarea}', [SubtareaController::class, 'suspender'])->middleware('auth:sanctum');
Route::get('subtareas/pausas/{subtarea}', [SubtareaController::class, 'pausas'])->middleware('auth:sanctum');
Route::get('subtareas-asignadas', [SubtareaAsignadaController::class, 'index'])->middleware('auth:sanctum');
