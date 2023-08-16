<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormaPagoRequest;
use App\Http\Resources\FormaPagoResource;
use App\Models\FormaPago;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class FormaPagoController extends Controller
{
    private $entidad = 'FormaPago';
    public function __construct()
    {
        $this->middleware('can:puede.ver.forma_pago')->only('index', 'show');
        $this->middleware('can:puede.crear.forma_pago')->only('store');
        $this->middleware('can:puede.editar.forma_pago')->only('update');
        $this->middleware('can:puede.eliminar.forma_pago')->only('destroy');
    }
    public function listar()
    {
        $campos = explode(',', request('campos'));

        if (request('campos')) {
            return FormaPago::ignoreRequest(['campos'])->filter()->get($campos);
        } else {
            return FormaPagoResource::collection(FormaPago::filter()->get());
        }
    }

    /**
     * Listar
     */
    public function index()
    {
        $results = $this->listar();
        return response()->json(compact('results'));
    }


    /**
     * Guardar
     */
    public function store(FormaPagoRequest $request)
    {
        //Respuesta
        $modelo = FormaPago::create($request->validated());
        $modelo = new FormaPagoResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Consultar
     */
    public function show(FormaPago $FormaPago)
    {
        $modelo = new FormaPagoResource($FormaPago);
        return response()->json(compact('modelo'));
    }


    /**
     * Actualizar
     */
    public function update(FormaPagoRequest $request, FormaPago  $FormaPago)
    {
        // Respuesta
        $FormaPago->update($request->validated());
        $modelo = new FormaPagoResource($FormaPago->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Eliminar
     */
    public function destroy(FormaPago $FormaPago)
    {
        $FormaPago->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
