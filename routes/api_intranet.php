<?php

use App\Http\Controllers\Intranet\CategoriaNoticiaController;
use App\Http\Controllers\Intranet\EtiquetaController;
use App\Http\Controllers\Intranet\EventoController;
use App\Http\Controllers\Intranet\NoticiaController;
use App\Http\Controllers\Intranet\OrganigramaController;
use App\Http\Controllers\Intranet\TipoEventoController;
use Illuminate\Support\Facades\Route;

Route::apiResources([
    'noticias' => NoticiaController::class,
    'eventos' => EventoController::class,
    'organigrama' => OrganigramaController::class,
    'categorias' => CategoriaNoticiaController::class,
    'tipos-eventos'=> TipoEventoController::class,
    'etiquetas' => EtiquetaController::class,
],[
    'parameters'=>[
        'tipos-eventos'=> 'tipo'
    ]
]);

