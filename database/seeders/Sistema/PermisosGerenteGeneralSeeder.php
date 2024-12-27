<?php /* @noinspection ALL */

namespace Database\Seeders\Sistema;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Src\Config\Permisos;

class PermisosGerenteGeneralSeeder extends Seeder
{
    // php artisan db:seed --class="Database\Seeders\Sistema\PermisosGerenteGeneralSeeder"
    public function run(){
        $admin= Role::firstOrCreate(['name' => User::ROL_ADMINISTRADOR]);
        $gerente = Role::firstOrCreate(['name' => User::ROL_GERENTE]);
        $asistente_gerencia = Role::firstOrCreate(['name' => User::ROL_ASISTENTE_GERENCIA]);

            Permission::firstOrCreate(['name' => Permisos::BOTON.'modo_no_disponible'])->syncRoles([$gerente, $asistente_gerencia]);

            Permission::firstOrCreate(['name' => Permisos::ACCEDER.'autorizadores_directos'])->syncRoles([$gerente, $asistente_gerencia, $admin]);
            Permission::firstOrCreate(['name' => Permisos::VER.'autorizadores_directos'])->syncRoles([$gerente, $asistente_gerencia, $admin]);
            Permission::firstOrCreate(['name' => Permisos::CREAR.'autorizadores_directos'])->syncRoles([$gerente, $asistente_gerencia, $admin]);
            Permission::firstOrCreate(['name' => Permisos::EDITAR.'autorizadores_directos'])->syncRoles([$gerente, $asistente_gerencia, $admin]);
            Permission::firstOrCreate(['name' => Permisos::ELIMINAR.'autorizadores_directos'])->syncRoles([$gerente, $asistente_gerencia, $admin]);


    }
}
