<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parroquia extends Model
{
    use HasFactory;
    protected $table = "parroquias";
    /*
    * Get the provincia that owns the canton
    */
    public function canton()
    {
        return $this->belongsTo(Canton::class);
    }

    public function cliente()
    {
        return $this->hasOne(Cliente::class);
    }
}
