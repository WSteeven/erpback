<?php

namespace App\Models\ComprasProveedores;

use App\Models\Autorizacion;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\EstadoTransaccion;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Proforma extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;

    public $table = 'cmp_proformas';
    public $fillable = [
        'solicitante_id',
        'cliente_id',
        'autorizador_id',
        'autorizacion_id',
        'observacion_aut',
        'estado_id',
        'causa_anulacion',
        'descripcion',
        'forma',
        'tiempo',
    ];


    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
      ];

    private static $whiteListFilter = ['*'];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */

    /**
     * Relación uno a muchos.
     * Una proforma tiene varios detalles
     */
    public function detalles(){
        return $this->hasMany(ItemDetalleProforma::class);
    }
    /**
   * Relación uno a muchos (inversa).
   * Uno o varias ordenes de compra pertencen a un solicitante.
   */
  public function solicitante()
  {
    return $this->belongsTo(Empleado::class, 'solicitante_id', 'id');
  }

  /**
   * Relación uno a muchos (inversa).
   * Uno o varias ordenes de compra son autorizados por un empleado Jefe Inmediato.
   */
  public function autorizador()
  {
    return $this->belongsTo(Empleado::class, 'autorizador_id', 'id');
  }

  /**
   * Relación uno a uno(inversa).
   * Uno o varios ordenes de compra solo pueden tener una autorización.
   */
  public function autorizacion()
  {
    return $this->belongsTo(Autorizacion::class);
  }

  /**
   * Relación uno a uno(inversa).
   * Uno o varios ordenes de compra solo pueden tener un estado.
   */
  public function estado()
  {
    return $this->belongsTo(EstadoTransaccion::class);
  }

  /**
   * Relación uno a uno
   */
  public function cliente(){
    return $this->belongsTo(Cliente::class);
  }

    /**
     * ______________________________________________________________________________________
     * FUNCIONES
     * ______________________________________________________________________________________
     */

     public static function listadoProductos(int $id)
    {
        $detalles = Proforma::find($id)->detalles()->get();
        $results = [];
        $row = [];
        foreach ($detalles as $index => $detalle) {
        $row['id'] = $detalle->id;
        $row['cantidad'] = $detalle->cantidad;
        $row['descripcion'] = $detalle->descripcion;
        $row['unidad_medida'] = $detalle->unidad_medida_id;
        $row['iva'] = $detalle->iva;
        $row['porcentaje_descuento'] = $detalle->porcentaje_descuento;
        $row['precio_unitario'] = $detalle->precio_unitario;
        $row['descuento'] = $detalle->descuento;
        $row['iva'] = $detalle->iva;
        $row['subtotal'] = $detalle->subtotal;
        $row['total'] = $detalle->total;
        $row['facturable'] = (bool)$detalle->facturable;
        $row['grava_iva'] = (bool)$detalle->grava_iva;
        $results[$index] = $row;
        }

        return $results;
    }
    public static function obtenerSumaListado($id)
    {
      $detalles = ItemDetalleProforma::where('proforma_id', $id)->get();
      $total = $detalles->sum('total');
      $subtotal = $detalles->sum('subtotal');
      $iva = $detalles->sum('iva');
      $descuento = $detalles->sum('descuento');

      return [$subtotal, $iva, $descuento, $total];
    }

    public static function guardarDetalles($proforma, $items){
        Log::channel('testing')->info('Log', ['Datos recibidos :', $items]);
        try {
           DB::beginTransaction();
            $datos = array_map(function ($detalle){
                return [
                    'cantidad'=> $detalle['cantidad'],
                    'descripcion'=> $detalle['descripcion'],
                    'facturable'=> $detalle['facturable'],
                    'grava_iva'=> $detalle['grava_iva'],
                    'iva'=> $detalle['iva'],
                    'porcentaje_descuento'=> $detalle['porcentaje_descuento']||0,
                    'descuento'=> $detalle['descuento'],
                    'precio_unitario'=> $detalle['precio_unitario'],
                    'subtotal'=> $detalle['subtotal'],
                    'total'=> $detalle['total'],
                    'unidad_medida_id'=> $detalle['unidad_medida'],
                ];
            }, $items);
            $proforma->detalles()->createMany($datos);
            // foreach($datos as $item){
            // }

           DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
