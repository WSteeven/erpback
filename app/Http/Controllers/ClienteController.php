<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClienteRequest;
use App\Http\Resources\ClienteResource;
use App\Models\Cliente;
use App\Models\Empresa;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;
use Src\Shared\Utils;
use Throwable;

class ClienteController extends Controller
{
    private string $entidad = 'Cliente';
    public function __construct()
    {
        $this->middleware('can:puede.ver.clientes')->only('index', 'show');
        $this->middleware('can:puede.crear.clientes')->only('store');
        $this->middleware('can:puede.editar.clientes')->only('update');
        $this->middleware('can:puede.eliminar.clientes')->only('destroy');
    }
    /**
     * Listar
     */
    public function index(Request $request)
    {
        $search = $request['search'];

        $results = [];
        if ($request['campos']) {
            $results = Cliente::ignoreRequest(['campos'])->filter()->get();
            // return response()->json(compact('results'));
        } else if ($search) {
            $empresa = Empresa::select('id')->where('razon_social', 'LIKE', '%' . $search . '%')->first();
            // Log::channel('testing')->info('Log', ['empresa', $empresa->id]);

            if ($empresa) $results = ClienteResource::collection(Cliente::where('empresa_id', $empresa->id)->get());
        } else {
            $results = Cliente::filter()->get();
            // Log::channel('testing')->info('Log', ['entro en el else grande', $results, $request->all()]);
        }

        $results = ClienteResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     * @throws ValidationException
     * @throws Throwable
     */
    public function store(ClienteRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            //Adaptacion de claves foraneas
            $datos['empresa_id'] = $request->safe()->only(['empresa'])['empresa'];
            $datos['parroquia_id'] = $request->safe()->only(['parroquia'])['parroquia'];

            if ($datos['logo_url']) $datos['logo_url'] = (new GuardarImagenIndividual($datos['logo_url'], RutasStorage::CLIENTES))->execute();

            //Respuesta
            $modelo = Cliente::create($datos);
            $modelo = new ClienteResource($modelo);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->error('Log', ['ERROR en el insert de clientes', $e->getMessage(), $e->getLine()]);
            throw Utils::obtenerMensajeErrorLanzable($e);

        }
    }


    /**
     * Consultar
     */
    public function show(Cliente $cliente)
    {
        $modelo = new ClienteResource($cliente);
        return response()->json(compact('modelo'));
    }


    /**
     * Actualizar
     * @throws ValidationException
     * @throws Throwable
     */
    public function update(ClienteRequest $request, Cliente  $cliente)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            //Adaptacion de claves foraneas
            $datos['empresa_id'] = $request->safe()->only(['empresa'])['empresa'];
            $datos['parroquia_id'] = $request->safe()->only(['parroquia'])['parroquia'];

            if ($datos['logo_url'] && Utils::esBase64($datos['logo_url'])) $datos['logo_url'] = (new GuardarImagenIndividual($datos['logo_url'], RutasStorage::CLIENTES, $cliente->logo_url))->execute();
            else unset($datos['logo_url']);

            // Respuesta
            $cliente->update($datos);
            $modelo = new ClienteResource($cliente->refresh());
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->error('Log', ['ERROR al actualizar el cliente', $e->getMessage(), $e->getLine()]);
            throw Utils::obtenerMensajeErrorLanzable($e);
        }
    }

    /**
     * Eliminar
     */
    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }


    public function clientesConPrefacturas(Request $request)
    {
        $campos = request('campos') ? explode(',', request('campos')) : '*';
        $clientes = Cliente::whereHas('prefacturas', function ($query) use ($request) {
            $query->when($request->solicitante_id, function ($q) use ($request) {
                $q->where('solicitante_id', $request->solicitante_id);
            });
        })->get($campos);

        $results = ClienteResource::collection($clientes);
        return response()->json(compact('results'));
    }
}
