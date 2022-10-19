<?php

namespace App\Http\Controllers;

use App\Models\Contacto;
use Illuminate\Http\Request;
use App\Http\Resources\ContactoResource;

class ContactoController extends Controller
{
    private $entidad = 'Contacto';

    /**
     * Listar
     */
    public function index(Request $request)
    {
        $results = ContactoResource::collection(Contacto::all());
        return response()->json(compact('results'));
    }
}
