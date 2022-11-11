<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransaccionBodegaResource;
use App\Models\TransaccionBodega;
use Illuminate\Http\Request;

class SolicitudMaterialTareaController extends Controller
{
    public function index(Request $request) {
        $page = $request['page'];

        if ($page) {
            $results = TransaccionBodega::simplePaginate($request['offset']);
            TransaccionBodegaResource::collection($results);
        } else {
            $results = TransaccionBodegaResource::collection(TransaccionBodega::filter()->get());
        }

        return response()->json(compact('results'));
    }

    public function store() {

    }
}
