<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoSubtarea extends Model
{
    use HasFactory;

    protected $table = 'grupo_subtarea';
    protected $fillable = ['principal', 'grupo_id', 'subtarea_id'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'responsable' => 'boolean',
    ];
}
