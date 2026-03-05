<?php

use App\Exports\RegistroTendidoExport;
use App\Http\Controllers\FileController;
use App\Http\Resources\ProductoResource;
use App\Models\Producto;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

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

Route::middleware('artisan.key')->prefix('system')->group(function () {

    Route::get('/cache-clear', function () {
        Artisan::call('cache:clear');
        return response()->json(['status' => 'cache cleared']);
    });

    Route::get('/config-clear', function () {
        Artisan::call('config:clear');
        return response()->json(['status' => 'config cleared']);
    });

    Route::get('/route-clear', function () {
        Artisan::call('route:clear');
        return response()->json(['status' => 'routes cleared']);
    });

    Route::get('/view-clear', function () {
        Artisan::call('view:clear');
        return response()->json(['status' => 'views cleared']);
    });

    Route::get('/migrate', function () {
        Artisan::call('migrate', ['--force' => true]);
        return response()->json(['status' => 'migrations executed']);
    });
});
