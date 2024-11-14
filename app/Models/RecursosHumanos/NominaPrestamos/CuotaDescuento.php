<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use App\Traits\UppercaseValuesTrait;
use DB;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use Throwable;


class CuotaDescuento extends Model implements Auditable
{
    use HasFactory;
    use UppercaseValuesTrait, Filterable;
    use AuditableModel;

    protected $table = 'rrhh_nomina_cuotas_descuentos';
    protected $fillable = [
        'descuento_id',
        'num_cuota',
        'mes_vencimiento',
        'valor_cuota',
        'pagada',
        'comentario',
    ];

    protected $casts = [
        'pagada' => 'boolean',
    ];

    private static array $whiteListFilter = ['*'];

    public function descuento()
    {
        return $this->belongsTo(Descuento::class);
    }

    public function egreso_rol_pago()
    {
        return $this->morphMany(EgresoRolPago::class, 'descuento');
    }

    /**
     * @throws Throwable
     */
    public static function actualizarCuotasDescuento(Descuento $descuento, array $listado)
    {
        $ids_elementos = [];
        try {
            DB::beginTransaction();
            foreach ($listado as $fila) {
                $registro = $descuento->cuotas()->find($fila['id']);
                if (!$registro)
                    $registro = $descuento->cuotas()->create($fila);
                else
                    $registro->update($fila);
                $ids_elementos[] = $registro->id;
            }
            $descuento->cuotas()->whereNotIn('id', $ids_elementos)->delete();
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
