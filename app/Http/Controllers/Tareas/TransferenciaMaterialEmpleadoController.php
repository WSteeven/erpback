<?php

namespace App\Http\Controllers\Tareas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransferenciaMaterialEmpleadoController extends Controller
{
    public function index() {
        $results = [];
        return response()->json(compact('results'));
    }
}
