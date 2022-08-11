<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Canton extends Model
{
    use HasFactory;
    protected $table = "cantones";

    /**
     * Get the parroquia associated with the canton.
     */
    public function parroquias(){
        return $this->hasMany(Parroquia::class);
    }

    /*
    * Get the provincia that owns the canton
    */
    public function provincia()
    {
        return $this->belongsTo(Provincia::class);
    }

    /**
     * Interact with the canton's  name.
     *
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    /* protected function canton(): Attribute
    {
        return Attribute::make(
            get: fn($value) => strtoupper($value),
            set: fn($value) => strtoupper($value),
        );
    } */
}
