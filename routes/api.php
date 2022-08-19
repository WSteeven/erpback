<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AutorizacionController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CondicionController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\EstadosTransaccionController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\HiloController;
use App\Http\Controllers\ImagenesProductoController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\ModeloController;
use App\Http\Controllers\MovimientosProductosController;
use App\Http\Controllers\NombresProductosController;
use App\Http\Controllers\PerchaController;
use App\Http\Controllers\PisoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\TipoFibraController;
use App\Http\Controllers\TipoTransaccionController;
use App\Http\Controllers\TransaccionesBodegaController;
use App\Http\Controllers\UbicacionController;
use App\Http\Resources\UserResource;
use App\Models\Cliente;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return new UserResource($request->user());
});

//rutas de user
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/verpermisos', [AuthController::class, 'verpermisos'])->middleware('auth:sanctum');


Route::apiResources(
    [
        'autorizaciones' => AutorizacionController::class,
        'categorias' => CategoriaController::class,
        'clientes' => ClienteController::class,
        'condiciones' => CondicionController::class,
        'empleados' => EmpleadoController::class,
        'empresas' => EmpresaController::class,
        'estados' => EstadosTransaccionController::class,
        'grupos' => GrupoController::class,
        'hilos' => HiloController::class,
        'imagenesproductos' => ImagenesProductoController::class,
        'marcas' => MarcaController::class,
        'modelos' => ModeloController::class,
        'movimientosproductos' => MovimientosProductosController::class,
        'nombre_productos' => NombresProductosController::class,
        'perchas' => PerchaController::class,
        'pisos' => PisoController::class,
        'productos' => ProductoController::class,
        'proveedores' => ProveedorController::class,
        'sucursales' => SucursalController::class,
        'tiposfibras' => TipoFibraController::class,
        'tipostransacciones' => TipoTransaccionController::class,
        'transacciones' => TransaccionesBodegaController::class,
        'ubicaciones' => UbicacionController::class,
    ],
    [
        'parameters' => [
            'autorizaciones' => 'autorizacion',
            'condiciones' => 'condicion',
            'imagenesproductos' => 'imagenproducto',
            'movimientosproductos' => 'movimiento',
            'proveedores' => 'proveedor',
            'sucursales' => 'sucursal',
            'tipostransacciones' => 'tipo',
            'transacciones' => 'transaccion',
            'ubicaciones' => 'ubicacion',
        ],
        //'middleware' => ['auth:sanctum']
    ]
);
