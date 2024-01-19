<?php

use App\Http\Controllers\Ventas\DashboardVentasController;
use App\Http\Controllers\Ventas\BonoController;
use App\Http\Controllers\Ventas\BonoMensualCumplimientoController;
use App\Http\Controllers\Ventas\BonoPorcentualController;
use App\Http\Controllers\Ventas\BonoTrimestralCumplimientoController;
use App\Http\Controllers\Ventas\ChargebackController;
use App\Http\Controllers\Ventas\ClienteClaroController;
use App\Http\Controllers\Ventas\ComisionController;
use App\Http\Controllers\Ventas\EscenarioVentaJPController;
use App\Http\Controllers\Ventas\EsquemaComisionController;
use App\Http\Controllers\Ventas\ModalidadController;
use App\Http\Controllers\Ventas\PagoComisionController;
use App\Http\Controllers\Ventas\PlanController;
use App\Http\Controllers\Ventas\ProductoVentaController;
use App\Http\Controllers\Ventas\TipoChargebackController;
use App\Http\Controllers\Ventas\UmbralVentaController;
use App\Http\Controllers\Ventas\VendedorController;
use App\Http\Controllers\Ventas\VentaController;
use Illuminate\Support\Facades\Route;

// Generar GET - POST - PUT - DELETE
Route::apiResources(
    [
        'bonos' => BonoController::class,
        'bono-porcentual' => BonoPorcentualController::class,
        'comisiones' => ComisionController::class,
        'modalidad' => ModalidadController::class,
        'planes' => PlanController::class,
        'productos-ventas' => ProductoVentaController::class,
        'vendedores' => VendedorController::class,
        'ventas' => VentaController::class,
        'tipo-chargeback' => TipoChargebackController::class,
        'chargebacks' => ChargebackController::class,
        'pago-comision' => PagoComisionController::class,
        'bono-mensual-cumplimiento' => BonoMensualCumplimientoController::class,
        'bono-trimestral-cumplimiento' => BonoTrimestralCumplimientoController::class,
        'umbral-ventas' => UmbralVentaController::class,
        'esquema-comision' => EsquemaComisionController::class,
        'clientes-claro' => ClienteClaroController::class,
        'escenario-venta-jp' => EscenarioVentaJPController::class,
    ],
    [
        'parameters' => [
            'planes' => 'plan',
            'clientes-claro' => 'cliente',
            'comisiones' => 'comision',
            'vendedores' => 'vendedor',
            'productos-ventas' => 'producto',
        ],
    ]
);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('obtener-comision/{idProducto}/{forma_pago}/{vendedor}', [ComisionController::class, 'obtener_comision']);
    Route::post('cobrojp', [VentaController::class, 'generar_reporteCobroJP']);
    Route::post('reporte-ventas', [VentaController::class, 'reporte_ventas']);
    Route::post('pago', [VentaController::class, 'reporte_pagos']);
    Route::get('dashboard', [DashboardVentasController::class, 'index']);
});

Route::post('productos-ventas/desactivar/{producto}', [ProductoVentaController::class, 'desactivar']);
Route::post('vendedores/desactivar/{vendedor}', [VendedorController::class, 'desactivar']);
Route::post('clientes-claro/desactivar/{cliente}', [ClienteClaroController::class, 'desactivar']);
