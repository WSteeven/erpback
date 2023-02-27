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
        'trabajo_id',
        'bobina_id',
    ];

    public function registrosTendidos()
    {
        return $this->hasMany(RegistroTendido::class);
    }

    public function trabajo()
    {
        return $this->belongsTo(Trabajo::class);
    }

    public function bobina()
    {
        return $this->hasOne(Fibra::class);
    }
}
