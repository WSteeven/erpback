<?php

use App\Exports\RegistroTendidoExport;
use App\Http\Controllers\Appenate\ProgresivaController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\LoginSocialNetworkController;
use App\Http\Controllers\PedidoController;
use App\Http\Resources\ProductoResource;
use App\Mail\Notificar;
use App\Models\Producto;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use Src\App\FCMService;
use Src\Shared\Utils;

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
    $results = Producto::search(request()->search)
        ->get();

    $results = ProductoResource::collection($results);
    return response()->json(compact('results'));
});


Route::get('/', function () {
    return view('welcome');
});

Route::view('resumen-tendido', 'pdf-excel.resumen_tendido'); //resources\views\pdf-excel\resumen_tendido.php
Route::get('resumen-tendido', fn() => Excel::download(new RegistroTendidoExport, 'users.xlsx'));

Route::get('get-file/{file_path}', [FileController::class, 'getFile'])->where('file_path', '.*')->name('get-file');

