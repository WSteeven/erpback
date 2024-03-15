<?php

namespace App\Http\Controllers\ComprasProveedores;

use App\Http\Controllers\Controller;
use App\Http\Resources\ComprasProveedores\PagoProveedoresResource;
use App\Imports\ComprasProveedores\PagoProveedoresImport;
use App\Models\ComprasProveedores\ItemPagoProveedores;
use App\Models\ComprasProveedores\PagoProveedores;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Src\App\ArchivoService;
use Src\Shared\Utils;

class PagoProveedoresController extends Controller
{
    //pagos_proveedores
    private $entidad = 'Pago a proveedores';
    private $archivoService;

    public function __construct()
    {
        // $this->servicio = new OrdenCompraService();
        $this->archivoService = new ArchivoService();
        $this->middleware('can:puede.ver.pagos_proveedores')->only('index', 'show');
        $this->middleware('can:puede.crear.pagos_proveedores')->only('store');
        $this->middleware('can:puede.editar.pagos_proveedores')->only('update');
        $this->middleware('can:puede.eliminar.pagos_proveedores')->only('destroy');
    }
    public function index()
    {
        $results = PagoProveedores::filter()->orderBy('id', 'desc')->get();
        $results = PagoProveedoresResource::collection($results);
        return response()->json(compact('results'));
    }
    public function store(Request $request)
    {
        Log::channel('testing')->info('Log', ['request pago-proveeodres:', $request->all()]);
        try {
            DB::beginTransaction();
            $this->validate($request, [
                'file' => 'required|mimes:xls,xlsx'
            ]);
            if (!$request->hasFile('file')) {
                throw ValidationException::withMessages([
                    'file' => ['Debe seleccionar al menos un archivo.'],
                ]);
            }
            $pago = PagoProveedores::create([
                'nombre' => Carbon::now()->format('Y-m-d') . ' - ' . $request->file->getClientOriginalName(),
                'realizador_id' => auth()->user()->empleado->id,
            ]);
            Excel::import(new PagoProveedoresImport($pago), $request->file);
            $mensaje = 'Subido exitosamente!';
            $modelo = new PagoProveedoresResource($pago);
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR al leer el archivo', $e->getMessage(), $e->getLine()]);
            throw ValidationException::withMessages([
                'file' => [$e->getMessage(), $e->getLine()],
            ]);
        }
    }

    public function show(PagoProveedores $pago)
    {
        $modelo = new PagoProveedoresResource($pago);
        return response()->json(compact('modelo'));
    }
    public function update(Request $request, PagoProveedores $pago)
    {
        Log::channel('testing')->info('Log', ['request pago-proveeodres:', $request->all()]);
        Log::channel('testing')->info('Log', ['pago-proveeodres:', $pago]);
        $ids_items = [];
        try {
            //code...
            DB::beginTransaction();
            //se debe actualizar los registros con un foreach
            foreach ($request->listado as $key => $item) {
                $ids_items[] = $item['id']; //se asigna los ids para saber que items se tiene que mantener y eliminar los que no esten
                $itemEncontrado = ItemPagoProveedores::find($item['id']);
                if ($itemEncontrado) {
                    Log::channel('testing')->info('Log', ['item_encontrado:', $itemEncontrado]);
                    $itemEncontrado->valor_pagar = $item['valor_pagar'];
                    $itemEncontrado->save();
                }
            }
            //se elimina los registros que no vienen desde el front
            ItemPagoProveedores::eliminarObsoletos($pago->id, $ids_items);

            DB::commit();
            $modelo = new PagoProveedoresResource($pago->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR al actualizar los registros', $e->getMessage(), $e->getLine()]);
            throw ValidationException::withMessages([
                'error' => [$e->getMessage(), $e->getLine()],
            ]);
        }
    }
}
