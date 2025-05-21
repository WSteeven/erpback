<?php

use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\Ventas\DashboardVentasController;
use App\Http\Controllers\Ventas\BonoController;
use App\Http\Controllers\Ventas\BonoMensualCumplimientoController;
use App\Http\Controllers\Ventas\BonoPorcentualController;
use App\Http\Controllers\Ventas\BonoTrimestralCumplimientoController;
use App\Http\Controllers\Ventas\ChargebackController;
use App\Http\Controllers\Ventas\ClienteClaroController;
use App\Http\Controllers\Ventas\ComisionController;
use App\Http\Controllers\Ventas\CortePagoComisionController;
use App\Http\Controllers\Ventas\EscenarioVentaJPController;
use App\Http\Controllers\Ventas\EsquemaComisionController;
use App\Http\Controllers\Ventas\EstadoClaroController;
use App\Http\Controllers\Ventas\ModalidadController;
use App\Http\Controllers\Ventas\NovedadVentaController;
use App\Http\Controllers\Ventas\PagoComisionController;
use App\Http\Controllers\Ventas\PlanController;
use App\Http\Controllers\Ventas\ProductoVentaController;
use App\Http\Controllers\Ventas\RetencionChargebackController;
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
        'estados' => EstadoClaroController::class,
        'modalidad' => ModalidadController::class,
        'planes' => PlanController::class,
        'productos-ventas' => ProductoVentaController::class,
        'vendedores' => VendedorController::class,
        'ventas' => VentaController::class,
        'tipo-chargeback' => TipoChargebackController::class,
        'chargebacks' => ChargebackController::class,
        'pagos-comisiones' => PagoComisionController::class,
        'novedades-ventas' => NovedadVentaController::class,
        'bonos-mensuales-cumplimientos' => BonoMensualCumplimientoController::class,
        'bono-trimestral-cumplimiento' => BonoTrimestralCumplimientoController::class,
        'umbral-ventas' => UmbralVentaController::class,
        'esquema-comision' => EsquemaComisionController::class,
        'clientes-claro' => ClienteClaroController::class,
        'escenario-venta-jp' => EscenarioVentaJPController::class,
        'cortes-pagos-comisiones' => CortePagoComisionController::class,
        'retenciones-chargebacks' => RetencionChargebackController::class,
    ],
    [
        'parameters' => [
            'planes' => 'plan',
            'clientes-claro' => 'cliente',
            'comisiones' => 'comision',
            'vendedores' => 'vendedor',
            'productos-ventas' => 'producto',
            'novedades-ventas' => 'venta',
            'pagos-comisiones' => 'pago',
            'cortes-pagos-comisiones' => 'corte',
            'retenciones-chargebacks' => 'retencion',
            'bonos-mensuales-cumplimientos' => 'bono',
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
Route::post('vendedores/desactivar-masivo', [VendedorController::class, 'desactivarMasivo']);
Route::post('clientes-claro/desactivar/{cliente}', [ClienteClaroController::class, 'desactivar']);
Route::post('ventas/suspender/{venta}', [VentaController::class, 'desactivar']);
Route::post('ventas/marcar-pagado/{venta}', [VentaController::class, 'marcarPagado']);
Route::get('obtener-fechas-disponibles-cortes', [CortePagoComisionController::class, 'obtenerFechasDisponblesCortes']);
Route::get('cortes-pagos-comisiones/marcar-completada/{corte}', [CortePagoComisionController::class, 'marcarCompletado']);
Route::get('retenciones-chargebacks/marcar-pagada/{retencion}', [RetencionChargebackController::class, 'marcarPagada']);
Route::get('actualizar-comisiones-ventas', [VentaController::class, 'actualizarComisiones']);
Route::post('bonos-mensuales-cumplimientos/marcar-pagada/{bono}', [BonoMensualCumplimientoController::class, 'marcarPagada']);

Route::get('empleados-ventas', [EmpleadoController::class, 'empleadosConVentasClaro']);

//listar archivos
Route::get('ventas/files/{venta}', [VentaController::class, 'indexFiles'])->middleware('auth:sanctum');
//guardar archivos
Route::post('ventas/files/{venta}', [VentaController::class, 'storeFiles'])->middleware('auth:sanctum');

//anular
Route::post('cortes-pagos-comisiones/anular/{corte}', [CortePagoComisionController::class, 'anular']);


/**
 * Rutas para imprimir archivos (PDF y EXCEL)
 */
Route::get('cortes-pagos-comisiones/imprimir-excel/{corte}', [CortePagoComisionController::class, 'imprimirExcel']);
