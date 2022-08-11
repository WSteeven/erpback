<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    use HasFactory;
    protected $table = "provincias";

    /**
     * Get the cantones of the provincia.
     */
    public function cantones(){
        return $this->hasMany(Canton::class);
    }
    public function pais(){
        return $this->belongsTo(Pais::class, 'id','pais_id');
    }


    /**
     * Interaccion con el nombre de provincia.
     *
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    /* protected function nombre(): Attribute
    {
        return Attribute::make(
            get: fn($value) => strtoupper($value),
            set: fn($value) => strtoupper($value),
        );
    } */
}
