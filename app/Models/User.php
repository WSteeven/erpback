<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;

    const ROL_ADMINISTRADOR = 'ADMINISTRADOR';
    const ROL_BODEGA = 'BODEGA';
    const ROL_COORDINADOR = 'COORDINADOR';
    const ROL_COMPRAS = 'COMPRAS';
    const ROL_EMPLEADO = 'EMPLEADO';
    const ROL_TECNICO = 'TECNICO';
    const ROL_GERENTE = 'GERENTE';
    const ROL_JEFE_TECNICO = 'JEFE TECNICO';
    const ROL_ACTIVOS_FIJOS = 'ACTIVOS FIJOS';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relacion uno a uno
    public function empleados()
    {
        return $this->hasOne(Empleado::class, 'usuario_id');
    }
}
