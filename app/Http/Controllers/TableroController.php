<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TableroController extends Controller
{
    public function index() {
        $results = [];
        return response()->json(compact('results'));
    }
}
