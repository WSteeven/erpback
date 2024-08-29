<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * @method static find(mixed $canton_id)
 */
class Canton extends Model implements Auditable
{
    use HasFactory, AuditableModel;
    use UppercaseValuesTrait, Filterable;

    protected $table = "cantones";
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    private static array  $whiteListFilter = ['*'];

    /**
     * Get the parroquia associated with the canton.
     */
    public function parroquias()
    {
        return $this->hasMany(Parroquia::class);
    }

    /*
    * Get the provincia that owns the canton
    */
    public function provincia()
    {
        return $this->belongsTo(Provincia::class);
    }
}
