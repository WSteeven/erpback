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
Route::get('/notificar-android', function(){
    try {
        //code...
        $device_token = 'dZBr8Z-WTGSEWjm_n2cFu_:APA91bHPIod8UlDmtxGwYLELqsT8fQFlHvRprLCxpcnLtVdKvSyotjCrR_IWOz7arMIzeREIbaFMcP8NXnJh-lgSN18mI0Jfq1LcjGmwvHii9JlDPB11aYE';
        $title = '¡Nuevo like!';
        $body = 'Juan le dio like a tu publicación. Notificacion desde laravel jpconstrucred';
        $fmc_service = new FCMService();
        $fmc_service->sendTo($device_token, $title, $body);
        $message = 'Enviado exitoso';
        return response()->json(compact('message'));
    } catch (Throwable $th) {
        throw Utils::obtenerMensajeErrorLanzable($th);
    }
});
Route::get('/notificar-data-android', function(){
    try {
        //code...
        $device_token = 'dZBr8Z-WTGSEWjm_n2cFu_:APA91bHPIod8UlDmtxGwYLELqsT8fQFlHvRprLCxpcnLtVdKvSyotjCrR_IWOz7arMIzeREIbaFMcP8NXnJh-lgSN18mI0Jfq1LcjGmwvHii9JlDPB11aYE';
        $title = 'Nuevo like';
        $body = 'Juan le dio like a tu publicacion. Notificacion desde laravel jpconstrucred';
        $fmc_service = new FCMService();
        $fmc_service->sendDataTo($device_token, $title, $body);
        $message = 'Enviado exitoso';
        return response()->json(compact('message'));
    } catch (Throwable $th) {
        throw Utils::obtenerMensajeErrorLanzable($th);
    }
});

Route::get('/search-producto', function ()  {
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

Route::view('resumen-tendido', 'pdf-excel.resumen_tendido'); //resources\views\pdf-excel\resumen_tendido.php
Route::get('resumen-tendido', fn() => Excel::download(new RegistroTendidoExport, 'users.xlsx'));

Route::get('/notificar', function () {
    $response = Mail::to('wcordova@jpconstrucred.com')->cc(['full.stack.developer1997@gmail.com'])->send(new Notificar());

    dump($response);
});

Route::get('social-network/{driver}', [LoginSocialNetworkController::class, 'handleCallback']);
Route::get('login-social-network', [LoginSocialNetworkController::class, 'login']);
Route::get('social-network/{driver}', [LoginSocialNetworkController::class, 'handleCallback']);

Route::get('get-file/{file_path}', [FileController::class, 'getFile'])->where('file_path', '.*')->name('get-file');

Route::get('obtener-registros-progresivas', [ProgresivaController::class, 'leerRegistros']);
