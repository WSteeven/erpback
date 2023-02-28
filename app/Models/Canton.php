<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Canton extends Model implements Auditable
{
    use HasFactory, AuditableModel;
    use UppercaseValuesTrait;

    protected $table = "cantones";
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

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
