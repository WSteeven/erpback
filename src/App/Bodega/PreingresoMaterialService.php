<?php

namespace Src\App\Bodega;

use App\Models\Condicion;
use App\Models\DetalleProducto;
use App\Models\Empleado;
use App\Models\Fibra;
use App\Models\Inventario;
use App\Models\ItemDetallePreingresoMaterial;
use App\Models\MaterialEmpleado;
use App\Models\MaterialEmpleadoTarea;
use App\Models\PreingresoMaterial;
use App\Models\Producto;
use App\Models\UnidadMedida;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\Autorizaciones;
use Src\Config\RutasStorage;
use Src\Shared\Utils;

class PreingresoMaterialService
{
    public function __construct() {}

    public static function filtrarPreingresos(Request $request)
    {
        if ($request->autorizacion_id) {
            // switch ($request->autorizacion_id) {
            //     case 1: //PENDIENTE
            if (auth()->user()->hasRole([User::ROL_JEFE_TECNICO, User::ROL_COORDINADOR_BODEGA, User::ROL_ADMINISTRADOR])) return PreingresoMaterial::where('autorizacion_id', $request->autorizacion_id)->orderBy('id', 'desc')->get();
            $results = PreingresoMaterial::where('autorizacion_id', $request->autorizacion_id)
                ->where(function ($query) {
                    $query->where('responsable_id', auth()->user()->empleado->id)
                        ->orWhere('autorizador_id', auth()->user()->empleado->id)
                        ->orWhere('coordinador_id', auth()->user()->empleado->id);
                })->orderBy('id', 'desc')->get();
            //         break;
            //     case 2: //APROBADO
            //         if(auth()->user()->hasRole([User::ROL_JEFE_TECNICO])) return PreingresoMaterial::where('autorizacion_id', $request->autorizacion_id)->orderBy('id', 'desc')->get();
            //         $results = PreingresoMaterial::where('autorizacion_id', $request->autorizacion_id)
            //             ->where(function ($query) {
            //                 $query->where('responsable_id', auth()->user()->empleado->id)
            //                     ->orWhere('autorizador_id', auth()->user()->empleado->id)
            //                     ->orWhere('coordinador_id', auth()->user()->empleado->id);
            //             })->orderBy('id', 'desc')->get();
            //         break;
            //     case 3: //CANCELADO
            //         $results = PreingresoMaterial::where('autorizacion_id', $request->autorizacion_id)
            //             ->where(function ($query) {
            //                 $query->where('responsable_id', auth()->user()->empleado->id)
            //                     ->orWhere('autorizador_id', auth()->user()->empleado->id)
            //                     ->orWhere('coordinador_id', auth()->user()->empleado->id);
            //             })->orderBy('id', 'desc')->get();
            //         break;
            //     default:
            //         $results = PreingresoMaterial::all();
            // }
        } else {
            $results = PreingresoMaterial::all();
        }
        return $results;
    }

    public static function empaquetarDatos($preingreso_id, $detalle_id, $item)
    {
        $fotografia = null;
        // se guarda la imagen en caso de haber
        if (array_key_exists('fotografia', $item) && Utils::esBase64($item['fotografia'])) $fotografia = (new GuardarImagenIndividual($item['fotografia'], RutasStorage::FOTOGRAFIAS_ITEMS_PREINGRESOS,null, $preingreso_id . '_' . $item['producto'] .'_'. time()))->execute();
        $unidad = UnidadMedida::where('nombre', $item['unidad_medida'])->first();
        $condicion = Condicion::where('nombre', $item['condicion'])->first();

        $datos = [
            'preingreso_id' => $preingreso_id,
            'detalle_id' => $detalle_id,
            'descripcion' => $item['descripcion'],
            'serial' => $item['serial'],
            'cantidad' => $item['cantidad'],
            'punta_inicial' => $item['punta_inicial'],
            'punta_final' => $item['punta_final'],
            'unidad_medida_id' => $unidad->id,
            'condicion_id' => $condicion->id,
            'fotografia' => $fotografia,
        ];
        return $datos;
    }
    public static function guardarDetalles($preingreso, $listado)
    {
        try {
            foreach ($listado as $item) {
                //buscamos el producto padre del detalle
                $producto = Producto::obtenerProductoPorNombre($item['producto']);
                //buscamos si el detalle ingresado coincide con uno ya almacenado
                $detalle = DetalleProducto::obtenerDetalle($item['descripcion'], $item['serial'], $producto->id);

                if ($detalle) {
                    //Si hay detalle con numero de serie y todo tal cual los argumentos dados se hace lo siguiente:
                    if (is_null($detalle->serial))
                        ItemDetallePreingresoMaterial::create(self::empaquetarDatos($preingreso->id, $detalle->id, $item));
                    else {
                        $sumInventario = Inventario::contarExistenciasDetalleSerial($detalle->id);
                        if ($sumInventario > 0) throw new Exception('ERROR: Hay ' . $sumInventario . ' items en inventario con serial ' . $detalle->serial . '. No se puede ingresar en stock del técnico sin antes quitar del inventario');
                        if ($sumInventario == 0) ItemDetallePreingresoMaterial::create(self::empaquetarDatos($preingreso->id, $detalle->id, $item));
                    }
                } else {
                    // no se encontró detalles coincidentes con numero de serie, buscamos sin numero de serie
                    $detalle = DetalleProducto::obtenerDetalle($item['descripcion'], null, $producto->id);

                    if ($detalle) { //se encontró detalle, pero se sabe que no tiene el mismo número de serie, entonces se debe crear uno nuevo
                        if ($item['serial']) {
                            $datos = $detalle->toArray();
                            $datos['serial'] = $item['serial'];
                            $fibra = Fibra::where('detalle_id', $detalle->id)->first();
                            if ($fibra) {
                                $datos['span'] = $fibra->span_id;
                                $datos['tipo_fibra'] = $fibra->tipo_fibra_id;
                                $datos['hilos'] = $fibra->hilo_id;
                                $datos['punta_inicial'] = $item['punta_inicial'];
                                $datos['punta_final'] = $item['punta_final'];
                                $datos['custodia'] = abs($item['punta_inicial'] - $item['punta_final']);
                            }

                            $categoria = new \stdClass();
                            $categoria->categoria = strtoupper($producto->categoria->nombre);
                            $categoria->es_fibra = !!$fibra;
                            $detalleNuevo = DetalleProducto::crearDetalle($categoria, $datos);
                            ItemDetallePreingresoMaterial::create(self::empaquetarDatos($preingreso->id, $detalleNuevo->id, $item));
                        }
                    } else  throw new Exception('No se encontró un detalle de producto con las similitudes dadas');
                }
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * La función "cargarMaterialesEmpleado" se utiliza para cargar materiales para un empleado, ya sea
     * a su stock personal o al stock de una tarea específica de la que es responsable.
     *
     * @param PreingresoMaterial $preingreso El parámetro `preingreso` es una instancia de la clase
     * `PreingresoMaterial`, que representa una preentrada de materiales. Contiene información como el
     * empleado responsable, el ID de la tarea y otros detalles.
     * @param mixed $listado Una matriz que contiene los detalles de los materiales que se cargarán.
     * @return void
     */
    public static function cargarMaterialesEmpleado(PreingresoMaterial $preingreso, $listado)
    {
        try {
            foreach ($listado as $item) {
                $producto = Producto::obtenerProductoPorNombre($item['producto']);
                $detalle = DetalleProducto::obtenerDetalle($item['descripcion'], $item['serial'], $producto->id);
                if ($detalle) {

                    if ($preingreso->tarea_id) // se carga el material al stock de tarea del tecnico responsable
                        MaterialEmpleadoTarea::cargarMaterialEmpleadoTarea($detalle->id, $preingreso->responsable_id, $preingreso->tarea_id, $item['cantidad'], $preingreso->cliente_id, $preingreso->proyecto_id, $preingreso->etapa_id);
                    else  // se carga el material al stock personal del tecnico responsable
                        MaterialEmpleado::cargarMaterialEmpleado($detalle->id, $preingreso->responsable_id, $item['cantidad'], $preingreso->cliente_id);
                } else {
                    throw new Exception('No se encontró un detalle ');
                }
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    /**
     * La función comprueba si un elemento con una ID determinada existe en una lista determinada.
     *
     * @param int $id El parámetro "id" es el ID del elemento que se va a verificar si existe en la lista
     * dada.
     * @param mixed $listado Una matriz que contiene elementos con las claves 'producto', 'descripcion' y
     * 'serial'.
     *
     * @return boolean Devuelve verdadero si el  dado se encuentra en la matriz;
     * de lo contrario, devuelve falso.
     */
    public static function verificarItemExisteListadoRecibido($id, $listado)
    {
        foreach ($listado as $elemento) {
            $producto = Producto::obtenerProductoPorNombre($elemento['producto']);
            $detalle = DetalleProducto::obtenerDetalle($elemento['descripcion'], $elemento['serial'], $producto->id);
            // Si el id del itemPreingreso es encontrado en el listado recibido entonces retorna true
            if ($id == $detalle->id) return true;
        }
        return false;
    }
    /**
     * La función "eliminarItemsPreingreso" toma una colección de elementos y una lista, verifica si
     * cada elemento de la lista existe en la colección y elimina los elementos que no existen.
     *
     * @param Collection $itemsPreingreso Una colección de elementos que se procesarán para su
     * eliminación.
     * @param mixed $listado El parámetro es una variable que representa una lista o matriz de
     * elementos.
     * @return void
     */
    public static function eliminarItemsPreingreso(Collection $itemsPreingreso, $listado)
    {
        $ids_encontrados = [];
        $encontrado = false;
        foreach ($itemsPreingreso as $item) {
            $encontrado = self::verificarItemExisteListadoRecibido($item['detalle_id'], $listado);
            if ($encontrado) array_push($ids_encontrados, $item['detalle_id']);
        }
        $diferencia = $itemsPreingreso->pluck('detalle_id')->diff($ids_encontrados);
        foreach ($diferencia as $d) {
            $itemAEliminar = $itemsPreingreso->where('detalle_id', $d)->first();
            //Eliminamos la imagen del servidor y luego el ítem
            Utils::eliminarArchivoServidor($itemAEliminar->fotografia);
            $itemAEliminar->delete();
        }
    }
    public static function modificarItemPreingreso(ItemDetallePreingresoMaterial $itemPreingreso, $datos)
    {
        $fotografia = null;
        if ($datos['fotografia'] && Utils::esBase64($datos['fotografia'])) {
            //Si la fotografia recibida es la actualización de algun item ya guardado, se borra la imagen del servidor y se guarda la nueva imagen
            Utils::eliminarArchivoServidor($itemPreingreso->fotografia);

            $fotografia = (new GuardarImagenIndividual($datos['fotografia'], RutasStorage::FOTOGRAFIAS_ITEMS_PREINGRESOS, null, $itemPreingreso->preingreso_id . '_' . $datos['producto'] .'_'. time()))->execute();
            $itemPreingreso->fotografia = $fotografia;
            $itemPreingreso->save();
        }
        $itemPreingreso->update([
            'descripcion' => $datos['descripcion'],
            'serial' => $datos['serial'] ? $datos['serial'] : null,
            'cantidad' => $datos['cantidad'] ? $datos['cantidad'] : null,
            'punta_inicial' => $datos['punta_inicial'] ? $datos['punta_inicial'] : null,
            'punta_final' => $datos['punta_final'] ? $datos['punta_final'] : null,
        ]);
    }


    public static function modificarItems($preingreso, $listado)
    {
        $itemsPreingreso = ItemDetallePreingresoMaterial::where('preingreso_id', $preingreso->id)->get();
        if (count($listado) < $itemsPreingreso->count()) { //significa que se recibieron menos items de los que existian previamente
            self::eliminarItemsPreingreso($itemsPreingreso, $listado);
            self::modificarItems($preingreso, $listado); //recursividad, una vez hecha la eliminación de repetidos ya no debería entrar a eliminar nuevamente
        } else { // ingresa aquí cuando recibe igual o mayor cantidad de items de los que existian previamente
            foreach ($listado as $item) {
                //primero buscamos si hay un detalle coincidente con los datos recibidos desde el front
                $producto = Producto::obtenerProductoPorNombre($item['producto']);
                $detalle = DetalleProducto::obtenerDetalle($item['descripcion'], $item['serial'], $producto->id);

                if ($detalle) {
                    //Si hay el detalle, comprobamos si hay algún ítem de Preingreso registrado previamente con el detalle encontrado
                    $itemPreingreso = ItemDetallePreingresoMaterial::where('preingreso_id', $preingreso->id)->where('detalle_id', $detalle->id)->first();
                    if ($itemPreingreso) self::modificarItemPreingreso($itemPreingreso, $item);
                    else self::guardarDetalles($preingreso, [$item]);
                } else {
                    $detalle = DetalleProducto::obtenerDetalle($item['descripcion'], null, $producto->id);
                    if ($detalle) {
                        $itemPreingreso = ItemDetallePreingresoMaterial::where('preingreso_id', $preingreso->id)->where('detalle_id', $detalle->id)->first();
                        Log::channel('testing')->info('Log', ['item 255:', $itemPreingreso]);
                        if ($itemPreingreso) self::modificarItemPreingreso($itemPreingreso, $item);
                        else self::guardarDetalles($preingreso, [$item]);
                    }
                    // throw new Exception('No se encontró un detalle coincidente');
                }
            }
        }
    }
    public static function actualizarDetalles($preingreso, $listado)
    {
        try {
            // Ingresa aquí cuando se aprueba el preingreso
            if ($preingreso->autorizacion_id == Autorizaciones::APROBADO) {
                self::modificarItems($preingreso, $listado);
                self::cargarMaterialesEmpleado($preingreso, $listado);
            }

            // Ingresa aquí cuando el preingreso sigue en pendiente
            if ($preingreso->autorizacion_id == Autorizaciones::PENDIENTE) {
                self::modificarItems($preingreso, $listado);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /************************************************
     * Reporte Excel - Formulario producto Empleados
     ************************************************/
    public function filtrarPreingresosReporteExcel($request)
    {
        $query = PreingresoMaterial::where('autorizacion_id', 2)->where('responsable_id', $request['responsable']);

        // Manejo de las fechas usando Carbon
        $fechaInicio = Carbon::parse($request->fecha_inicio)->startOfDay();
        $fechaFin = $request->fecha_fin
            ? Carbon::parse($request->fecha_fin)->endOfDay()
            : now();

        $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);

        return $query->orderByDesc('id')->get();
    }

    public function obtenerProductosPreingresos($preingresos)
    {
        $results = []; // Inicializa $results fuera del bucle principal

        foreach ($preingresos as $preingreso) {
            $detalles = $preingreso->detalles()->get();

            foreach ($detalles as $detalle) {
                $results[] = [
                    'id' => $preingreso->id,
                    'fecha_solicitud' => $preingreso->created_at,
                    'producto' => $detalle->producto->nombre,
                    'descripcion' => $detalle->pivot->descripcion,
                    'serial' => $detalle->pivot->serial,
                    'categoria' => $detalle->producto->categoria->nombre,
                    'cantidad' => $detalle->pivot->cantidad,
                    'cliente' => $preingreso->cliente?->empresa->razon_social,
                    'justificacion' => $preingreso->observacion,
                    'cliente_id' => $preingreso->cliente_id,
                    'detalle_producto_id' => $detalle->id,
                    'solicitante' => Empleado::extraerNombresApellidos($preingreso->solicitante),
                    'unidad_medida' => $detalle->producto->unidadMedida->nombre,
                    'condicion' => Condicion::find($detalle->pivot->condicion_id)?->nombre,
                ];
            }
        }

        return $results;
    }

    /* public static function obtenerSumaCantidadesProductos($productos_transferencias)
    {
        $results = [];
        foreach ($productos_transferencias as $item) {
            $detalleProductoId = $item['detalle_producto_id'];
            $propietario = $item['cliente_id'];

            // Llave única para identificar elementos
            $key = $detalleProductoId . '|' . $propietario;

            if (!isset($results[$key])) {
                // Si el elemento no existe en el array, agregarlo
                $results[$key] = [
                    'producto' => $item['producto'],
                    'descripcion' => $item['descripcion'],
                    'serial' => $item['serial'],
                    'propietario' => $item['cliente'],
                    'cantidad' => $item['cantidad'],
                ];
            } else {
                // Si el elemento ya existe, sumar la cantidad
                $results[$key]['cantidad'] += $item['cantidad'];
            }
        }

        // Convertir resultados a un array indexado
        return array_values($results);
    } */
}
