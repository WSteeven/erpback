<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tendido extends Model
{
    use HasFactory;
    protected $table = 'tendidos';
    protected $fillable = [
        'marca_inicial',
        'marca_final',
        'subtarea_id',
        'bobina_id',
    ];

    public function registrosTendidos()
    {
        return $this->hasMany(RegistroTendido::class);
    }

    public function subtarea()
    {
        return $this->belongsTo(Subtarea::class);
    }

    public function bobina()
    {
        return $this->hasOne(Fibra::class);
    }
}
