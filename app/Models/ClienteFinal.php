<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UppercaseValuesTrait;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\ClienteFinal
 *
 * @property int $id
 * @property string $id_cliente_final
 * @property string $nombres
 * @property string|null $apellidos
 * @property string|null $celular
 * @property string|null $parroquia
 * @property string|null $direccion
 * @property string|null $referencia
 * @property string|null $cedula
 * @property string|null $correo
 * @property string|null $coordenadas
 * @property bool $activo
 * @property int|null $provincia_id
 * @property int|null $canton_id
 * @property int $cliente_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Canton|null $canton
 * @property-read \App\Models\Cliente $cliente
 * @property-read \App\Models\Provincia|null $provincia
 * @method static \Illuminate\Database\Eloquent\Builder|ClienteFinal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClienteFinal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClienteFinal query()
 * @method static \Illuminate\Database\Eloquent\Builder|ClienteFinal whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClienteFinal whereApellidos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClienteFinal whereCantonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClienteFinal whereCedula($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClienteFinal whereCelular($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClienteFinal whereClienteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClienteFinal whereCoordenadas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClienteFinal whereCorreo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClienteFinal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClienteFinal whereDireccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClienteFinal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClienteFinal whereIdClienteFinal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClienteFinal whereNombres($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClienteFinal whereParroquia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClienteFinal whereProvinciaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClienteFinal whereReferencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClienteFinal whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ClienteFinal extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;

    protected $table = 'clientes_finales';
    protected $fillable = [
        'id_cliente_final',
        'nombres',
        'apellidos',
        'nombres',
        'apellidos',
        'celular',
        'parroquia',
        'direccion',
        'referencia',
        'cedula',
        'correo',
        'coordenadas',
        'activo',
        'provincia_id',
        'canton_id',
        'cliente_id',
    ];
    protected $casts = [
        'activo' => 'boolean',
    ];

    public function provincia()
    {
        return $this->belongsTo(Provincia::class);
    }

    public function canton()
    {
        return $this->belongsTo(Canton::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
