<?php

namespace App\Models\ActivosFijos;

use App\Models\DetalleProducto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Exception;
use Laravel\Scout\Searchable;

class ActivoFijo extends Model implements Auditable
{
    use HasFactory, Filterable, AuditableModel, Searchable;

    protected $table = 'af_activos_fijos';
    protected $fillable = [
        'detalle_producto_id',
        'cliente_id',
    ];

    private static $whiteListFilter = ['*'];

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
        ];
    }


    /**************
     * Relaciones
     **************/
    public function detalleProducto()
    {
        return $this->belongsTo(DetalleProducto::class, 'detalle_producto_id', 'id');
    }

    /*************
     * Funciones
     *************/
    public static function cargar(int $detalle_producto_id, int|null $cliente_id)
    {
        try {
            $existe = ActivoFijo::where('detalle_producto_id', $detalle_producto_id)->where('cliente_id', $cliente_id)->exists();

            if (!$existe) {
                ActivoFijo::create([
                    'detalle_producto_id' => $detalle_producto_id,
                    'cliente_id' => $cliente_id,
                ]);
            }
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage() . '. ' . $th->getLine());
        }
    }
}
