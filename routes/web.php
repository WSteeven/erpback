<?php

use App\Exports\RegistroTendidoExport;
use App\Http\Controllers\Appenate\ProgresivaController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\LoginSocialNetworkController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\TransaccionBodegaIngresoController;
use App\Http\Resources\ProductoResource;
use App\Mail\Notificar;
use App\Models\Producto;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/search-producto', function ()  {
//    Log::channel('testing')->info('Log', ['search-product', request()->all()]);

    $results = Producto::search(request()->search)
        ->get();

    $results = ProductoResource::collection($results);
    return response()->json(compact('results'));
});

Route::get('/qrcode', [PedidoController::class, 'qrview']);
Route::get('/encabezado', [PedidoController::class, 'encabezado']);
Route::get('/ejemplo', [PedidoController::class, 'example']);

Route::get('/', function () {
    return view('welcome');
});

Route::name('imprimir')->get('/imprimir-pdf', [TransaccionBodegaIngresoController::class, 'imprimir']);
// Route::get('transacciones-ingresos/imprimir/{transaccion}', [TransaccionBodegaIngresoController::class, 'imprimir'])->name('imprimir');

//pedidos


Route::view('resumen-tendido', 'pdf-excel.resumen_tendido'); //resources\views\pdf-excel\resumen_tendido.php
Route::get('resumen-tendido', fn() => Excel::download(new RegistroTendidoExport, 'users.xlsx'));

Route::get('/notificar', function () {
    $response = Mail::to('wcordova@jpconstrucred.com')->cc(['full.stack.developer1997@gmail.com'])->send(new Notificar());

    dump($response);
});

Route::get('social-network/{driver}', [LoginSocialNetworkController::class, 'handleCallback']);
Route::get('login-social-network', [LoginSocialNetworkController::class, 'login']);
Route::get('social-network/{driver}', [LoginSocialNetworkController::class, 'handleCallback']);

// Route::get('verificar', function(){
//     $empleado = Empleado::find(24);

//     Log::channel('testing')->info('Log', ['Recibe fondos', $empleado->acumula_fondos_reserva==0]);
// });

Route::get('get-file/{file_path}', [FileController::class, 'getFile'])->where('file_path', '.*')->name('get-file');

//Route::get('get-file/{file_path}', function ($file_path) {
    // Decodifica la URL en caso de que tenga caracteres especiales.
//    $file_path = urldecode($file_path);

    //Verifica si el archivo existe
//    $full_path =

//});


Route::get('obtener-registros-progresivas', [ProgresivaController::class, 'leerRegistros']);
