<?php

use App\Exports\RegistroTendidoExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FondosRotativos\Viatico\ViaticoController;
use App\Http\Controllers\PrestamoTemporalController;
use App\Http\Controllers\TransaccionBodegaIngresoController;
use App\Models\PrestamoTemporal;
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

Route::get('/', function () {
    return view('welcome');
});

// Route::name('imprimir')->get('/imprimir-pdf', [Controller::class, 'imprimir']);
Route::get('transacciones-ingresos/imprimir/{transaccion}', [TransaccionBodegaIngresoController::class, 'imprimir'])->name('imprimir');
// Route::name('imprimir')->get('/imprimir-single/{prestamo}', [PrestamoTemporalController::class, 'print']);

Route::view('resumen-tendido', 'pdf-excel.resumen_tendido'); //resources\views\pdf-excel\resumen_tendido.php
Route::get('resumen-tendido', fn() => Excel::download(new RegistroTendidoExport, 'users.xlsx'));
Route::get('fondos-rotativos/prueba/reporte/fecha/{tipo}', [ViaticoController::class, 'generar_reporte_prueba']);
