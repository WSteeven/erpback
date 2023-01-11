<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UppercaseValuesTrait;

class ClienteFinal extends Model
{
    use HasFactory, UppercaseValuesTrait;

    protected $table = "clientes_finales";
    protected $fillable = [
        "id_cliente_final",
        "nombres",
        "apellidos",
        "nombres",
        "apellidos",
        "celular",
        "parroquia",
        "direccion",
        "referencia",
        "coordenada_latitud",
        'coordenada_longitud',
        "provincia_id",
        "canton_id",
        'cliente_id',
    ];

    public function provincia() {
        return $this->belongsTo(Provincia::class);
    }

    public function canton() {
        return $this->belongsTo(Canton::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
