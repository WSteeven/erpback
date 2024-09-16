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
use App\Http\Controllers\FondosRotativos\Saldo\SaldoController;
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
        'saldo-grupo' => SaldoController::class,
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
    Route::post('reporte/fecha/{tipo}', [GastoController::class, 'generarReporte']);
    Route::post('reporte/saldo_actual/{tipo}', [SaldoController::class, 'saldoActual']);
    //Route::post('reporte/saldo_actual/{tipo}', [SaldoGrupoController::class, 'saldoActual']);
    Route::post('reporte/solicitud_fondo/{tipo}', [GastoCoordinadorController::class, 'reporte']);
    Route::get('cortar_saldo', [AcreditacionSemanaController::class, 'cortarSaldo']);
    Route::get('ultimo_saldo/{id}', [SaldoController::class, 'saldoActualUsuario']);
    Route::get('monto_acreditar_usuario/{id}', [ValorAcreditarController::class, 'montoAcreditarUsuario']);
    Route::post('autorizaciones_fecha/{tipo}', [GastoController::class, 'reporteAutorizaciones']);
    Route::post('consolidado/{tipo}', [SaldoController::class, 'consolidado']);
    Route::post('consolidado_filtrado/{tipo}', [SaldoController::class, 'consolidadoFiltrado']);
    Route::get('gastocontabilidad', [SaldoController::class, 'gastoContabilidad']);
    Route::get('autorizaciones_gastos', [GastoController::class, 'autorizacionesGastos']);
    Route::get('autorizaciones_transferencia', [TransferenciasController::class, 'autorizacionesTransferencia']);
    Route::post('aprobar-gasto', [GastoController::class, 'aprobarGasto']);
    Route::post('rechazar-gasto', [GastoController::class, 'rechazarGasto']);
    Route::post('anular-gasto', [GastoController::class, 'anularGasto']);
    Route::post('aprobar-transferencia', [TransferenciasController::class, 'aprobarTransferencia']);
    Route::post('rechazar-transferencia', [TransferenciasController::class, 'rechazarTransferencia']);
    Route::post('anular-transferencia', [TransferenciasController::class, 'anularTransferencia']);
    Route::post('anular-acreditacion', [AcreditacionesController::class, 'anularAcreditacion']);
    Route::get('crear-cash-acreditacion-saldo/{id}', [AcreditacionSemanaController::class, 'crearCashAcreditacionSaldo']);
    Route::get('acreditacion-saldo-semana/{id}', [AcreditacionSemanaController::class, 'acreditacionSaldoSemana']);
    Route::get('actualizar-valores-saldo-semana/{id}', [AcreditacionSemanaController::class, 'acreditacionSaldoSemana']);
    Route::get('reporte-acreditacion-semanal/{id}', [AcreditacionSemanaController::class, 'reporteAcreditacionSemanal']);
    Route::get('reporte-acreditacion-semanal/{id}', [AcreditacionSemanaController::class, 'reporteAcreditacionSemanal']);
    Route::post('reporte-valores-fondos', [GastoController::class, 'reporteValoresFondos']);
});


Route::get('empleados-saldos-fr', [EmpleadoController::class, 'empleadosConSaldoFondosRotativos']);
