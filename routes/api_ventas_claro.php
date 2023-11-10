<?php

use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\Ventas\BonoMensualCumplimientoController;
use App\Http\Controllers\Ventas\BonosController;
use App\Http\Controllers\Ventas\BonoTrimestralCumplimientoController;
use App\Http\Controllers\Ventas\ChargebacksController;
use App\Http\Controllers\Ventas\ComisionesController;
use App\Http\Controllers\Ventas\ModalidadController;
use App\Http\Controllers\Ventas\PagoComisionController;
use App\Http\Controllers\Ventas\PlanesController;
use App\Http\Controllers\Ventas\ProductoVentasController;
use App\Http\Controllers\Ventas\TipoChargebackController;
use App\Http\Controllers\Ventas\VendedorController;
use App\Http\Controllers\Ventas\VentasController;
use App\Models\Ventas\TipoChargeback;
use Illuminate\Support\Facades\Route;

// Generar GET - POST - PUT - DELETE
Route::apiResources(
    [
        'bonos' => BonosController::class,
        'comisiones' => ComisionesController::class,
        'modalidad' => ModalidadController::class,
        'planes' => PlanesController::class,
        'producto-ventas' => ProductoVentasController::class,
        'vendedor' => VendedorController::class,
        'ventas' => VentasController::class,
        'tipo-chargeback' => TipoChargebackController::class,
        'chargebacks' => ChargebacksController::class,
        'pago-comision' => PagoComisionController::class,
        'bono-mensual-cumplimiento' => BonoMensualCumplimientoController::class,
        'bono-trimestral-cumplimiento' => BonoTrimestralCumplimientoController::class,
        ],
    [
        'parameters' => [],
    ]
);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('obtener-comision/{idProducto}/{forma_pago}', [ComisionesController::class, 'obtener_comision']);
    Route::post('cobrojp', [VentasController::class, 'generar_reporteCobroJP']);
    Route::post('reporte-ventas',[VentasController::class, 'reporte_ventas']);

});
