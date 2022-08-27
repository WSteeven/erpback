<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parroquia extends Model
{
    use HasFactory, UppercaseValuesTrait;
    protected $table = "parroquias";
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];


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
