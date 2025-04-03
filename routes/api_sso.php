<?php

use App\Http\Controllers\ActividadRealizadaSeguimientoSubtareaController;
use App\Http\Controllers\ArchivoSeguimientoController;
use App\Http\Controllers\SSO\AccidenteController;
use App\Http\Controllers\SSO\CertificacionController;
use App\Http\Controllers\SSO\CertificacionEmpleadoController;
use App\Http\Controllers\SSO\IncidenteController;
use App\Http\Controllers\SSO\InspeccionController;
use App\Http\Controllers\SSO\SeguimientoAccidenteController;
use App\Http\Controllers\SSO\SeguimientoIncidenteController;
use App\Http\Controllers\SSO\SolicitudDescuentoController;
use App\Http\Controllers\TransaccionBodegaEgresoController;
use App\Http\Controllers\ControlAsistenciaController;
use App\Http\Controllers\RegistroTendidoController;
use App\Http\Controllers\TrabajoAsignadoController;
use App\Http\Controllers\ArchivoSubtareaController;
use App\Http\Controllers\CausaIntervencionController;
use App\Http\Controllers\ControlCambioController;
use App\Http\Controllers\TipoElementoController;
use App\Http\Controllers\ClienteFinalController;
use App\Http\Controllers\DashboardTareaController;
use App\Http\Controllers\TipoTrabajoController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\MotivoPausaController;
use App\Http\Controllers\MotivoSuspendidoController;
use App\Http\Controllers\MovilizacionSubtareaController;

// 9018-2
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\ReporteModuloTareaController;
use App\Http\Controllers\RutaTareaController;
use App\Http\Controllers\SeguimientoSubtareaController;
use App\Http\Controllers\SubtareaController;
use App\Http\Controllers\TendidoController;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\Tareas\AlimentacionGrupoController;
use App\Http\Controllers\Tareas\EtapaController;
use App\Http\Controllers\Tareas\TransferenciaProductoEmpleadoController;
use App\Http\Controllers\Tareas\CentroCostoController;
use App\Http\Controllers\Tareas\MaterialUtilizadoController;
use App\Http\Controllers\Tareas\SubCentroCostoController;
use Illuminate\Support\Facades\Route;

// Generar GET - POST - PUT - DELETE
Route::apiResources(
    [
        'accidentes' => AccidenteController::class,
        'incidentes' => IncidenteController::class,
        'inspecciones' => InspeccionController::class,
        'seguimientos-incidentes' => SeguimientoIncidenteController::class,
        'seguimientos-accidentes' => SeguimientoAccidenteController::class,
        'solicitudes-descuentos' => SolicitudDescuentoController::class,
        'certificaciones' => CertificacionController::class,
        'certificaciones-empleados' => CertificacionEmpleadoController::class,
    ],
    [
        'parameters' => [
            'accidentes' => 'accidente',
            'incidentes' => 'incidente',
            'inspecciones' => 'inspeccion',
            'seguimientos-incidentes' => 'seguimiento_incidente',
            'seguimientos-accidentes' => 'seguimiento_accidente',
            'solicitudes-descuentos' => 'solicitud_descuento',
            'certificaciones' => 'certificacion',
            'certificaciones-empleados' => 'certificacion_empleado',
        ],
    ]
);

/*************************
 * Archivos polimorficos
 *************************/
Route::get('incidentes/files/{incidente}', [IncidenteController::class, 'indexFiles']);

Route::get('accidentes/files/{accidente}', [AccidenteController::class, 'indexFiles']);
Route::post('accidentes/files/{accidente}', [AccidenteController::class, 'storeFiles']);

Route::get('solicitudes-descuentos/files/{solicitud_descuento}', [SolicitudDescuentoController::class, 'indexFiles']);
Route::post('solicitudes-descuentos/files/{solicitud_descuento}', [SolicitudDescuentoController::class, 'storeFiles']);
Route::post('incidentes/files/{incidente}', [IncidenteController::class, 'storeFiles']);

Route::get('inspecciones/files/{inspeccion}', [InspeccionController::class, 'indexFiles']);
Route::post('inspecciones/files/{inspeccion}', [InspeccionController::class, 'storeFiles']);

Route::get('seguimientos-accidentes/files/{seguimiento_accidente}', [SeguimientoAccidenteController::class, 'indexFiles']);
Route::post('seguimientos-accidentes/files/{seguimiento_accidente}', [SeguimientoAccidenteController::class, 'storeFiles']);

/***************
 * Imprimir pdf
 ***************/
Route::get('seguimientos-accidentes/imprimir/{seguimiento_accidente}', [SeguimientoAccidenteController::class, 'informeAccidente']);

