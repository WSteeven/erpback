<?php

namespace App\Http\Controllers\FondosRotativos\Viatico;

use App\Http\Controllers\Controller;
use App\Http\Resources\FondosRotativos\Viaticos\DetalleViaticoResource;
use App\Models\FondosRotativos\Viatico\DetalleViatico;
use Illuminate\Http\Request;

class DetalleViaticoController extends Controller
{
    public function index(Request $request)
    {
        $page = $request['page'];
        $results = [];

        $results = DetalleViatico::ignoreRequest(['campos'])->filter()->get();
        $results = DetalleViaticoResource::collection($results);
        return response()->json(compact('results'));
    }
}
