<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Provincia extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    
    protected $table = "provincias";
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];



    /**
     * Get the cantones of the provincia.
     */
    public function cantones(){
        return $this->hasMany(Canton::class);
    }
    
}
