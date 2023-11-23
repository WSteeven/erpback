<?php

use App\Exports\RegistroTendidoExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\PrestamoTemporalController;
use App\Http\Controllers\TransaccionBodegaIngresoController;
use App\Mail\Notificar;
use App\Models\PrestamoTemporal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use Src\App\RecursosHumanos\NominaPrestamos\NominaService;

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

Route::get('/qrcode', [PedidoController::class, 'qrview']);
Route::get('/encabezado', [PedidoController::class, 'encabezado']);
Route::get('/ejemplo', [PedidoController::class, 'example']);

Route::get('/', function () {
    return view('welcome');
});

// Route::name('imprimir')->get('/imprimir-pdf', [Controller::class, 'imprimir']);
// Route::get('transacciones-ingresos/imprimir/{transaccion}', [TransaccionBodegaIngresoController::class, 'imprimir'])->name('imprimir');
// Route::name('imprimir')->get('/imprimir-single/{prestamo}', [PrestamoTemporalController::class, 'print']);

//pedidos
Route::get('pedidos/imprimir/{pedido}', [PedidoController::class, 'imprimir'])->name('imprimir');


Route::view('resumen-tendido', 'pdf-excel.resumen_tendido'); //resources\views\pdf-excel\resumen_tendido.php
Route::get('resumen-tendido', fn() => Excel::download(new RegistroTendidoExport, 'users.xlsx'));

Route::get('/notificar', function(){
    $response = Mail::to('wilsonsteeven@outlook.com')->cc(['wilson972906@gmail.com', 'wcordova@jpconstrucred.com', 'full.stack.developer1997@gmail.com'])->send(new Notificar());

    dump($response);
});


Route::get('/calcular-dias', function(){
    $nominaService = new NominaService();
    $mes = Carbon::createFromFormat('m-Y', '10-2023')->format('Y-m');
    $nominaService->setMes($mes);
    $nominaService->setEmpleado(257);//257,286
    $dias = $nominaService->calcularDias(30, 30, $mes);
   dump($dias);
});
