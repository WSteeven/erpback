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
        "nombres", "apellidos",
        "id_cliente",
        "nombres",
        "apellidos",
        "celular",
        "provincia",
        "ciudad",
        "parroquia",
        "direccion",
        "referencias",
        "coordenadas",
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
