<?php

use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\FondosRotativos\AjusteSaldoFondoRotativoController;
use App\Http\Controllers\FondosRotativos\Gasto\DetalleViaticoController;
use App\Http\Controllers\FondosRotativos\Gasto\GastoController;
use App\Http\Controllers\FondosRotativos\Gasto\GastoCoordinadorController;
use App\Http\Controllers\FondosRotativos\Gasto\MotivoGastoController;
use App\Http\Controllers\FondosRotativos\Gasto\SubDetalleViaticoController;
use App\Http\Controllers\FondosRotativos\Saldo\AcreditacionesController;
use App\Http\Controllers\FondosRotativos\Saldo\AcreditacionSemanaController;
use App\Http\Controllers\FondosRotativos\Saldo\SaldoGrupoController;
use App\Http\Controllers\FondosRotativos\Saldo\TipoSaldoController;
use App\Http\Controllers\FondosRotativos\Saldo\TransferenciasController;
use App\Http\Controllers\FondosRotativos\Saldo\ValorAcreditarController;
use App\Http\Controllers\FondosRotativos\TipoFondoController;
use App\Http\Controllers\FondosRotativos\UmbralFondosRotativosController;
use Illuminate\Support\Facades\Route;

// Generar GET - POST - PUT - DELETE
Route::apiResources(
    [
        'ajustes-saldos' => AjusteSaldoFondoRotativoController::class,
        'detalles-viaticos' => DetalleViaticoController::class,
        'sub-detalles-viaticos' => SubDetalleViaticoController::class,
        'gastos' => GastoController::class,
        'tipo-saldo' => TipoSaldoController::class,
        'tipo-fondo' => TipoFondoController::class,
        'saldo-grupo' => SaldoGrupoController::class,
        'acreditacion' => AcreditacionesController::class,
        'transferencia' => TransferenciasController::class,
        'gasto-coordinador' => GastoCoordinadorController::class,
        'motivo-gasto' => MotivoGastoController::class,
        'umbral' => UmbralFondosRotativosController::class,
        'acreditacion-semana' => AcreditacionSemanaController::class,
        'valor-acreditar' => ValorAcreditarController::class,
    ],
    [
        'parameters' => [
            'ajustes-saldos' => 'ajuste'
        ],
    ]
);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('reporte/fecha/{tipo}', [GastoController::class, 'generar_reporte']);
    Route::post('reporte/saldo_actual/{tipo}', [SaldoGrupoController::class, 'saldo_actual']);
    Route::post('reporte/solicitud_fondo/{tipo}', [GastoCoordinadorController::class, 'reporte']);
    Route::get('cortar_saldo', [AcreditacionSemanaController::class, 'cortar_saldo']);
    Route::get('ultimo_saldo/{id}', [SaldoGrupoController::class, 'saldo_actual_usuario']);
    Route::get('monto_acreditar_usuario/{id}', [ValorAcreditarController::class, 'monto_acreditar_usuario']);
    Route::post('autorizaciones_fecha/{tipo}', [GastoController::class, 'reporte_autorizaciones']);
    Route::post('consolidado/{tipo}', [SaldoGrupoController::class, 'consolidado']);
    Route::post('consolidado_filtrado/{tipo}', [SaldoGrupoController::class, 'consolidado_filtrado']);
    Route::get('gastocontabilidad', [SaldoGrupoController::class, 'gastocontabilidad']);
    Route::get('autorizaciones_gastos', [GastoController::class, 'autorizaciones_gastos']);
    Route::get('autorizaciones_transferencia', [TransferenciasController::class, 'autorizaciones_transferencia']);
    Route::post('aprobar-gasto', [GastoController::class, 'aprobar_gasto']);
    Route::post('rechazar-gasto', [GastoController::class, 'rechazar_gasto']);
    Route::post('anular-gasto', [GastoController::class, 'anular_gasto']);
    Route::post('aprobar-transferencia', [TransferenciasController::class, 'aprobar_transferencia']);
    Route::post('rechazar-transferencia', [TransferenciasController::class, 'rechazar_transferencia']);
    Route::post('anular-transferencia', [TransferenciasController::class, 'anular_transferencia']);
    Route::post('anular-acreditacion', [AcreditacionesController::class, 'anular_acreditacion']);
    Route::get('crear-cash-acreditacion-saldo/{id}', [AcreditacionSemanaController::class, 'crear_cash_acreditacion_saldo']);
    Route::get('acreditacion-saldo-semana/{id}', [AcreditacionSemanaController::class, 'acreditacion_saldo_semana']);
    Route::get('actualizar-valores-saldo-semana/{id}', [AcreditacionSemanaController::class, 'acreditacion_saldo_semana']);
    Route::get('reporte-acreditacion-semanal/{id}', [AcreditacionSemanaController::class, 'reporte_acreditacion_semanal']);
    Route::get('reporte-acreditacion-semanal/{id}', [AcreditacionSemanaController::class, 'reporte_acreditacion_semanal']);
    Route::post('reporte-valores-fondos', [GastoController::class, 'reporte_valores_fondos']);
});


Route::get('empleados-saldos-fr', [EmpleadoController::class, 'empleadosConSaldoFondosRotativos']);
