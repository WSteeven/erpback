<?php

namespace App\Models\SSO;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @method static insert(array $array)
 * @method static ignoreRequest(string[] $array)
 * @method static create($datos)
 * @method static whereIn(string $string, mixed $certificaciones_id)
 */
class Certificacion extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'sso_certificaciones';
    protected $fillable = [
        'descripcion',
        'activo',
    ];
    protected $casts = [
        'activo' => 'boolean',
    ];

    private static array $whiteListFilter = ['*'];
}
