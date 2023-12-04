<?php

namespace App\Http\Controllers\Tarea;

use App\Http\Controllers\Controller;
use App\Models\Tareas\SolicitudAts;
use Illuminate\Http\Request;

class SolicitudAtsController extends Controller
{
    public function index() {
        SolicitudAts::where('subtarea_id');
    }
}
