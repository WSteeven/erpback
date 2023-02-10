<?php

namespace App\Http\Controllers\FondosRotativos\Viatico;

use App\Http\Controllers\Controller;
use App\Http\Resources\FondosRotativos\Viaticos\SubDetalleViaticoResource;
use App\Models\FondosRotativos\Viatico\SubDetalleViatico;
use Illuminate\Http\Request;

class SubDetalleViaticoController extends Controller
{
    public function index(Request $request)
    {
        $results = [];

        $results = SubDetalleViatico::ignoreRequest(['campos'])->filter()->get();
        $results = SubDetalleViaticoResource::collection($results);
        return response()->json(compact('results'));
    }
}
