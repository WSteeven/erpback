<?php

namespace Database\Seeders\Intranet;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Src\Config\Permisos;

class PermisosModuloIntranetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=Database\Seeders\Intranet\PermisosModuloIntranetSeeder
     * @return void
     */
    public function run()
    {
        //Modulo de intranet
        Permission::firstOrCreate(['name'=> Permisos::VER.'modulo_intranet'])->syncRoles([User::ROL_ADMINISTRADOR, User::ROL_RECURSOS_HUMANOS]);
        Permission::firstOrCreate(['name'=> Permisos::ACCEDER.'modulo_intranet'])->syncRoles([User::ROL_ADMINISTRADOR, User::ROL_RECURSOS_HUMANOS]);

        Permission::firstOrCreate(['name'=> Permisos::ACCEDER.'intra_categorias'])->syncRoles([User::ROL_ADMINISTRADOR, User::ROL_RECURSOS_HUMANOS]);
        Permission::firstOrCreate(['name'=> Permisos::VER.'intra_categorias'])->syncRoles([User::ROL_ADMINISTRADOR, User::ROL_RECURSOS_HUMANOS]);
        Permission::firstOrCreate(['name'=> Permisos::CREAR.'intra_categorias'])->syncRoles([User::ROL_ADMINISTRADOR, User::ROL_RECURSOS_HUMANOS]);
        Permission::firstOrCreate(['name'=> Permisos::EDITAR.'intra_categorias'])->syncRoles([User::ROL_ADMINISTRADOR, User::ROL_RECURSOS_HUMANOS]);

        Permission::firstOrCreate(['name'=> Permisos::ACCEDER.'intra_etiquetas'])->syncRoles([User::ROL_ADMINISTRADOR, User::ROL_RECURSOS_HUMANOS]);
        Permission::firstOrCreate(['name'=> Permisos::VER.'intra_etiquetas'])->syncRoles([User::ROL_ADMINISTRADOR, User::ROL_RECURSOS_HUMANOS]);
        Permission::firstOrCreate(['name'=> Permisos::CREAR.'intra_etiquetas'])->syncRoles([User::ROL_ADMINISTRADOR, User::ROL_RECURSOS_HUMANOS]);
        Permission::firstOrCreate(['name'=> Permisos::EDITAR.'intra_etiquetas'])->syncRoles([User::ROL_ADMINISTRADOR, User::ROL_RECURSOS_HUMANOS]);

        Permission::firstOrCreate(['name'=> Permisos::ACCEDER.'intra_tipos_eventos'])->syncRoles([User::ROL_ADMINISTRADOR, User::ROL_RECURSOS_HUMANOS]);
        Permission::firstOrCreate(['name'=> Permisos::VER.'intra_tipos_eventos'])->syncRoles([User::ROL_ADMINISTRADOR, User::ROL_RECURSOS_HUMANOS]);
        Permission::firstOrCreate(['name'=> Permisos::CREAR.'intra_tipos_eventos'])->syncRoles([User::ROL_ADMINISTRADOR, User::ROL_RECURSOS_HUMANOS]);
        Permission::firstOrCreate(['name'=> Permisos::EDITAR.'intra_tipos_eventos'])->syncRoles([User::ROL_ADMINISTRADOR, User::ROL_RECURSOS_HUMANOS]);

        Permission::firstOrCreate(['name'=> Permisos::ACCEDER.'intra_noticias'])->syncRoles([User::ROL_ADMINISTRADOR, User::ROL_RECURSOS_HUMANOS]);
        Permission::firstOrCreate(['name'=> Permisos::VER.'intra_noticias'])->syncRoles([User::ROL_ADMINISTRADOR, User::ROL_RECURSOS_HUMANOS, User::ROL_EMPLEADO]);
        Permission::firstOrCreate(['name'=> Permisos::CREAR.'intra_noticias'])->syncRoles([User::ROL_ADMINISTRADOR, User::ROL_RECURSOS_HUMANOS]);
        Permission::firstOrCreate(['name'=> Permisos::EDITAR.'intra_noticias'])->syncRoles([User::ROL_ADMINISTRADOR, User::ROL_RECURSOS_HUMANOS]);

        Permission::firstOrCreate(['name'=> Permisos::ACCEDER.'intra_eventos'])->syncRoles([User::ROL_ADMINISTRADOR, User::ROL_RECURSOS_HUMANOS]);
        Permission::firstOrCreate(['name'=> Permisos::VER.'intra_eventos'])->syncRoles([User::ROL_ADMINISTRADOR, User::ROL_RECURSOS_HUMANOS]);
        Permission::firstOrCreate(['name'=> Permisos::CREAR.'intra_eventos'])->syncRoles([User::ROL_ADMINISTRADOR, User::ROL_RECURSOS_HUMANOS]);
        Permission::firstOrCreate(['name'=> Permisos::EDITAR.'intra_eventos'])->syncRoles([User::ROL_ADMINISTRADOR, User::ROL_RECURSOS_HUMANOS]);


    }
}
