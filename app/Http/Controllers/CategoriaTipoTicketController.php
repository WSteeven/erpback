<?php

namespace App\Http\Controllers;

use App\Models\CategoriaTipoTicket;
use Illuminate\Http\Request;

class CategoriaTipoTicketController extends Controller
{
    public function index()
    {
        $results = CategoriaTipoTicket::all();
        return response()->json(compact('results'));
    }
}
