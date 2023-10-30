<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\Autorizaciones;
use Src\Config\RutasStorage;

class PreingresoMaterial extends Model implements Auditable
{
    use HasFactory;
    use Filterable;
    use UppercaseValuesTrait;
    use AuditableModel;
    protected $table = 'preingresos_materiales';
    protected $fillable =  [
        'observacion',
        'cuadrilla',
        'num_guia',
        'courier',
        'fecha',
        'tarea_id',
        'cliente_id',
        'autorizador_id',
        'responsable_id',
        'coordinador_id',
        'autorizacion_id',
        'observacion_aut',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    private static $whiteListFilter = [
        '*',
    ];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    /**
     * Relación uno a uno (inversa).
     * Un preingreso pertenece a una o ninguna tarea
     */
    public function tarea()
    {
        return $this->belongsTo(Tarea::class);
    }
    /**
     * Relacion uno a muchos (inversa).
     * Uno o varios preingresos pertenecen a un cliente
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id');
    }
    /**
     * Relacion uno a muchos (inversa).
     * Uno o varios preingresos son autorizados por una persona
     */
    public function autorizador()
    {
        return $this->belongsTo(Empleado::class, 'autorizador_id', 'id');
    }
    /**
     * Relacion uno a muchos (inversa).
     * Uno o varios preingresos tienen un responsable
     */
    public function responsable()
    {
        return $this->belongsTo(Empleado::class, 'responsable_id', 'id');
    }
    /**
     * Relacion uno a muchos (inversa).
     * Uno o varios preingresos tienen un coordinador
     */
    public function coordinador()
    {
        return $this->belongsTo(Empleado::class, 'coordinador_id', 'id');
    }

    /**
     * Relación uno a uno(inversa).
     * Uno o varios preingresos solo pueden tener una autorización.
     */
    public function autorizacion()
    {
        return $this->belongsTo(Autorizacion::class);
    }

    public function detalles()
    {
        return $this->belongsToMany(DetalleProducto::class, 'item_detalle_preingreso_material', 'preingreso_id', 'detalle_id')
            ->withPivot('descripcion', 'cantidad', 'serial', 'punta_inicial', 'punta_final', 'unidad_medida_id', 'fotografia')->withTimestamps();
    }

    /**
     * Relacion polimorfica a una notificacion.
     * Una orden de compra puede tener una o varias notificaciones.
     */
    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }
    /**
     * Relación para obtener la ultima notificacion de un modelo dado.
     */
    public function latestNotificacion()
    {
        return $this->morphOne(Notificacion::class, 'notificable')->latestOfMany();
    }


    /**
     * ______________________________________________________________________________________
     * FUNCIONES
     * ______________________________________________________________________________________
     */

    public static function listadoProductos($id)
    {
        $items =  PreingresoMaterial::find($id)->detalles()->get();
        $results = [];
        $id = 0;
        $row = [];
        foreach ($items as $item) {
            Log::channel('testing')->info('Log', ['Item en el listado:', $item]);
            $row['id'] = $item->id;
            $row['producto'] = $item->producto->nombre;
            $row['detalle_id'] = $item->id;
            $row['descripcion'] = $item->pivot->descripcion;
            $row['categoria'] = $item->producto->categoria->nombre;
            $row['unidad_medida'] = $item->producto->unidadMedida->nombre;
            $row['serial'] = $item->pivot->serial;
            $row['cantidad'] = $item->pivot->cantidad;
            $row['punta_inicial'] = $item->pivot->punta_inicial;
            $row['punta_final'] = $item->pivot->punta_final;
            $row['fotografia'] = $item->pivot->fotografia ? url($item->pivot->fotografia) : null;
            $results[$id] = $row;
            $id++;
        }

        return $results;
    }


    public static function guardarDetalles($preingreso, $listado)
    {
        try {
            foreach ($listado as $item) {
                // Log::channel('testing')->info('Log', ['Item a guardar:', $item]);
                //buscamos el producto padre del detalle
                $producto = Producto::where('nombre', $item['producto'])->first();
                //buscamos si el detalle ingresado coincide con uno ya almacenado
                $item['serial'] ? $detalle = DetalleProducto::where('producto_id', $producto->id)->where('serial', $item['serial'])->first() : $detalle = DetalleProducto::where('producto_id', $producto->id)->where(function ($query) use ($item) {
                    $query->where('descripcion', $item['descripcion'])->orWhere('descripcion', 'LIKE', '%' . $item['descripcion']   . '%'); // busca coincidencia exacta o similitud en el texto
                })->first();
                if ($detalle) {
                    if (is_null($detalle->serial)) $itemPreingreso = ItemDetallePreingresoMaterial::create(self::empaquetarDatos($preingreso->id, $detalle->id, $item));
                    else {
                        $sumInventario = Inventario::where('detalle_id', $detalle->id)->get();
                        if ($sumInventario->sum('cantidad') > 0)
                            throw new Exception('ERROR: Hay ' . $sumInventario->sum('cantidad') . ' items en inventario con serial ' . $detalle->serial . '. No se puede ingresar en stock del técnico sin quitar del inventario');
                        if ($sumInventario->sum('cantidad') == 0)
                            $itemPreingreso = ItemDetallePreingresoMaterial::create(self::empaquetarDatos($preingreso->id, $detalle->id, $item));
                        else {
                            // como el detalle encontrado tiene serial, el nuevo registro debe agregarse a los detalles
                            // Log::channel('testing')->info('Log', ['Else:', $detalle, $item]);
                            $datos = $detalle->toArray();
                            $fibra = Fibra::where('detalle_id', $detalle->id)->first();
                            if ($fibra) {
                                $datos['span'] = $fibra->span_id;
                                $datos['tipo_fibra'] = $fibra->tipo_fibra_id;
                                $datos['hilos'] = $fibra->hilo_id;
                                $datos['punta_inicial'] = $item['punta_inicial'];
                                $datos['punta_final'] = $item['punta_final'];
                                $datos['custodia'] = abs($item['punta_inicial'] - $item['punta_final']);
                            }
                            // los clientes (Nedetel y otros) no entregan computadoras ni telefonos a los tecnicos
                            // $computadora = ComputadoraTelefono::where('detalle_id', $detalle->id)->first();
                            // if($computadora){
                            //     $datos['ram'] = $fibra->span_id;
                            //     $datos['disco'] = $fibra->tipo_fibra_id;
                            //     $datos['procesador'] = $fibra->hilo_id;
                            //     $datos['imei'] = $item['punta_inicial'];
                            // }
                            $datos['serial'] = $item['serial'];
                            $categoria = new \stdClass();
                            $categoria->categoria = strtoupper($producto->categoria->nombre);
                            $categoria->es_fibra = !!Fibra::where('detalle_id', $detalle->id)->first();
                            $detalleNew = DetalleProducto::crearDetalle($categoria, $datos);
                            $itemPreingreso = ItemDetallePreingresoMaterial::create(self::empaquetarDatos($preingreso->id, $detalleNew->id, $item));
                            // throw new Exception('Se produjo un error');
                        }
                    }
                    Log::channel('testing')->info('Log', ['Item creado:', $itemPreingreso]);
                } else {
                    // no se encontró detalles coincidentes con numero de serie,buscamos sin numero de serie 
                    $detalle = DetalleProducto::where('producto_id', $producto->id)->where(function ($query) use ($item) {
                        $query->where('descripcion', $item['descripcion'])->orWhere('descripcion', 'LIKE', '%' . $item['descripcion']   . '%'); // busca coincidencia exacta o similitud en el texto
                    })->first();
                    if ($detalle) { //se encontró detalle, pero se sabe que no tiene el mismo número de serie, entonces se debe crear uno nuevo
                        if($item['serial'] && !is_null($detalle->serial)){
                        // como el detalle encontrado tiene serial, el nuevo registro debe agregarse a los detalles
                            $datos = $detalle->toArray();
                            $fibra = Fibra::where('detalle_id', $detalle->id)->first();
                            if ($fibra) {
                                $datos['span'] = $fibra->span_id;
                                $datos['tipo_fibra'] = $fibra->tipo_fibra_id;
                                $datos['hilos'] = $fibra->hilo_id;
                                $datos['punta_inicial'] = $item['punta_inicial'];
                                $datos['punta_final'] = $item['punta_final'];
                                $datos['custodia'] = abs($item['punta_inicial'] - $item['punta_final']);
                            }
                            
                            $datos['serial'] = $item['serial'];
                            $categoria = new \stdClass();
                            $categoria->categoria = strtoupper($producto->categoria->nombre);
                            $categoria->es_fibra = !!Fibra::where('detalle_id', $detalle->id)->first();
                            $detalleNew = DetalleProducto::crearDetalle($categoria, $datos);
                            $itemPreingreso = ItemDetallePreingresoMaterial::create(self::empaquetarDatos($preingreso->id, $detalleNew->id, $item));
                            // throw new Exception('Se produjo un error');
                        }
                        Log::channel('testing')->info('Log', ['Item creado:', $itemPreingreso]);
                    }
                }
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public static function actualizarDetalles($preingreso, $listado)
    {
        //se actualiza los detalles para cargar al stock del tecnico nada más
        try {
            Log::channel('testing')->info('Log', ['Actualizar detalles:', $preingreso, $listado]);
            if ($preingreso->autorizacion_id == Autorizaciones::APROBADO) {
                foreach ($listado as $item) {
                    $producto = Producto::where('nombre', $item['producto'])->first();
                    if (is_null($item['serial'])) {
                        $detalle = DetalleProducto::where('producto_id', $producto->id)->where(function ($query) use ($item) {
                            $query->where('descripcion', $item['descripcion'])->orWhere('descripcion', 'LIKE', '%' . $item['descripcion']   . '%'); // busca coincidencia exacta o similitud en el texto
                        })->first();
                    } else {
                        $detalle = DetalleProducto::where('producto_id', $producto->id)->where('serial', $item['serial'])->where(function ($query) use ($item) {
                            $query->where('descripcion', $item['descripcion'])->orWhere('descripcion', 'LIKE', '%' . $item['descripcion']   . '%'); // busca coincidencia exacta o similitud en el texto
                        })->first();
                    }

                    if ($preingreso->tarea_id) { // se carga el material al stock de tarea del tecnico responsable
                        $material = MaterialEmpleadoTarea::where('detalle_producto_id', $detalle->id)
                            ->where('tarea_id', $preingreso->tarea_id)
                            ->where('empleado_id', $preingreso->responsable_id)->first();

                        if ($material) {
                            $material->cantidad_stock += $item['cantidad'];
                            $material->despachado += $item['cantidad'];
                            $material->save();
                        } else {
                            $esFibra = !!Fibra::where('detalle_id', $detalle->id)->first();
                            MaterialEmpleadoTarea::create([
                                'cantidad_stock' => $item['cantidad'],
                                'despachado' => $item['cantidad'],
                                'tarea_id' => $preingreso->tarea_id,
                                'empleado_id' => $preingreso->responsable_id,
                                'detalle_producto_id' => $detalle->id,
                                'es_fibra' => $esFibra, // Pendiente de obtener
                            ]);
                        }
                    } else { // se carga el material al stock personal del tecnico responsable
                        $material = MaterialEmpleado::where('detalle_producto_id', $detalle->id)
                            ->where('empleado_id', $preingreso->responsable_id)->first();
                        if ($material) {
                            $material->cantidad_stock += $item['cantidad'];
                            $material->despachado += $item['cantidad'];
                            $material->save();
                        } else { //se crea el material 
                            $esFibra = !!Fibra::where('detalle_id', $detalle->id)->first();
                            MaterialEmpleado::create([
                                'cantidad_stock' => $item['cantidad'],
                                'despachado' => $item['cantidad'],
                                'empleado_id' => $preingreso->responsable_id,
                                'detalle_producto_id' => $detalle->id,
                                'es_fibra' => $esFibra,
                            ]);
                        }
                    }
                }
            }

            // throw new Exception('Se produjo un error al actualizar');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function empaquetarDatos($preingreso_id, $detalle_id, $item)
    {
        $fotografia = null;
        // se guarda la imagen en caso de haber
        if (array_key_exists('fotografia', $item)) $fotografia = (new GuardarImagenIndividual($item['fotografia'], RutasStorage::FOTOGRAFIAS_ITEMS_PREINGRESOS, $preingreso_id . '_' . $item['producto'] . time()))->execute();
        $unidad = UnidadMedida::where('nombre', $item['unidad_medida'])->first();

        $datos = [
            'preingreso_id' => $preingreso_id,
            'detalle_id' => $detalle_id,
            'descripcion' => $item['descripcion'],
            'serial' => $item['serial'],
            'cantidad' => $item['cantidad'],
            'punta_inicial' => $item['punta_inicial'],
            'punta_final' => $item['punta_final'],
            'unidad_medida_id' => $unidad->id,
            'fotografia' => $fotografia,
        ];
        return $datos;
    }
}
