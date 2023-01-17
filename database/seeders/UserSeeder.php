<?php

namespace Database\Seeders;

use App\Models\Sucursal;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // SuperAdministrador
        $admin = User::create([
            'name' => 'ADMINISTRADOR',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
        ])->assignRole(User::ROL_ADMINISTRADOR);
        // Gerente
        $gerente = User::create([
            'name' => 'PPAZMINO',
            'email' => 'gerente@jp.com',
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_GERENTE, User::ROL_EMPLEADO);
        // Coordinador
        $coordinador = User::create([
            'name' => 'MJARAMILLO',
            'email' => 'mjaramillo@jp.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_COORDINADOR, User::ROL_EMPLEADO);
        // RR HH
        $recursos_humanos = User::create([
            'name' => 'LPESANTES',
            'email' => 'manuel@jp.com',
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_EMPLEADO, User::ROL_RECURSOS_HUMANOS);
        // Coordinador
        $coordinador_sto_domingo = User::create([
            'name' => 'BCHAMBA',
            'email' => 'bchamba@jp.com',
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_EMPLEADO, User::ROL_COORDINADOR, User::ROL_BODEGA);
        // Coordinador
        $coordinador_telconet = User::create([
            'name' => 'DLOJA',
            'email' => 'dloja@jp.com',
            'password' => bcrypt('password'),
        ])->assignRole(User::ROL_COORDINADOR, User::ROL_EMPLEADO);
        // Asistente de bodega 1
        $bodeguero1 = User::create([
            'name' => 'CALBARRACIN',
            'email' => 'asistentebodega1@jp.com',
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_BODEGA, User::ROL_EMPLEADO);
        // Asistente de bodega 2
        $bodeguero2 = User::create([
            'name' => 'JTORRES',
            'email' => 'asistentebodega2@jp.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_EMPLEADO, User::ROL_BODEGA);

        

        //Localidad
        $localidad_machala = Sucursal::create(['lugar' => 'MACHALA', 'telefono' => '0965421', 'correo' => 'oficina_matriz@jp.com', 'administrador_id' => 7]);
        $localidad_sto_domingo = Sucursal::create(['lugar' => 'SANTO DOMINGO', 'telefono' => '0965421', 'correo' => 'oficina_santo_domingo@jp.com', 'administrador_id' => 5]);
        $localidad_cuenca = Sucursal::create(['lugar' => 'CUENCA', 'telefono' => '0965421', 'correo' => 'oficina_cuenca@jp.com', 'administrador_id' => 8]);
        $localidad_guayaquil = Sucursal::create(['lugar' => 'GUAYAQUIL', 'telefono' => '0965421', 'correo' => 'oficina_guayaquil@jp.com', 'administrador_id' => 1]);



        $admin->empleado()->create([
            'nombres' => 'Superusuario',
            'apellidos' => 'Administrador',
            'sucursal_id' => $localidad_machala->id
        ]);


        $gerente->empleado()->create([
            'nombres' => 'PATRICIO',
            'apellidos' => 'PAZMIÑO',
            'identificacion' => '0702875618001',
            'telefono' => '0987456748',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '2',
            'sucursal_id' => $localidad_machala->id
        ]);


        $coordinador->empleado()->create([
            'nombres' => 'MARILÚ',
            'apellidos' => 'JARAMILLO',
            'identificacion' => '0745125487',
            'telefono' => '0987456',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '2',
            'sucursal_id' => $localidad_machala->id
        ]);

        $recursos_humanos->empleado()->create([
            'nombres' => 'LUIS MANUEL',
            'apellidos' => 'PESANTEZ MORA',
            'identificacion' => '0784956230',
            'telefono' => '0987456747',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '2',
            'sucursal_id' => $localidad_machala->id
        ]);

        $coordinador_sto_domingo->empleado()->create([
            'nombres' => 'BRYAN',
            'apellidos' => 'CHAMBA',
            'identificacion' => '0745185287',
            'telefono' => '0987456741',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '2',
            'sucursal_id' => $localidad_sto_domingo->id
        ]);

        $coordinador_telconet->empleado()->create([
            'nombres' => 'DARIO',
            'apellidos' => 'LOJA',
            'identificacion' => '0745124163',
            'telefono' => '0987456785',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '2',
            'sucursal_id' => $localidad_machala->id
        ]);

        $bodeguero1->empleado()->create([
            'nombres' => 'CRISTIAN',
            'apellidos' => 'ALBARRACIN',
            'identificacion' => '0708549625',
            'telefono' => '0987456112',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '2',
            'sucursal_id' => $localidad_machala->id
        ]);

        $bodeguero2->empleado()->create([
            'nombres' => 'JUAN JOSE',
            'apellidos' => 'TORRES',
            'identificacion' => '0704126352',
            'telefono' => '0987456965',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '2',
            'sucursal_id' => $localidad_machala->id
        ]);

        // Departamento de compras
        $compras = User::create([
            'name' => 'ILIMA',
            'email' => 'asistentecompras@jp.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_EMPLEADO, User::ROL_COMPRAS);
        $compras->empleado()->create([
            'nombres' => 'INGRID',
            'apellidos' => 'LIMA',
            'identificacion' => '0708541325',
            'telefono' => '0984568596',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '2',
            'sucursal_id' => $localidad_machala->id
        ]);

        // Personal administrativo
        $administrativo = User::create([
            'name' => 'SSARMIENTO',
            'email' => 'santiago@jp.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_EMPLEADO, User::ROL_ADMINISTRATIVO);
        $administrativo->empleado()->create([
            'nombres' => 'SANTIAGO',
            'apellidos' => 'SARMIENTO',
            'identificacion' => '0745963251',
            'telefono' => '0987456235',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '5',
            'sucursal_id' => $localidad_guayaquil->id
        ]);


        // Activos fijos
        $activos_fijos = User::create([
            'name' => 'PAGUILAR',
            'email' => 'pedro@jp.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->syncRoles([User::ROL_ADMINISTRATIVO, User::ROL_ACTIVOS_FIJOS, User::ROL_BODEGA]);
        $activos_fijos->empleado()->create([
            'nombres' => 'PEDRO',
            'apellidos' => 'AGUILAR',
            'identificacion' => '0702041526',
            'telefono' => '0989857463',
            'fecha_nacimiento' => '1993-05-12',
            'jefe_id' => '2',
            'sucursal_id' => $localidad_machala->id
        ]);

        // Contabilidad
        $contabilidad = User::create([
            'name' => 'IVALAREZO',
            'email' => 'isa@jp.com',
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_EMPLEADO, User::ROL_CONTABILIDAD);
        $contabilidad->empleado()->create([
            'nombres' => 'ISABEL',
            'apellidos' => 'VALAREZO',
            'identificacion' => '0780401526',
            'telefono' => '0987457845',
            'fecha_nacimiento' => '2000-05-12',
            'jefe_id' => '2',
            'sucursal_id' => $localidad_machala->id
        ]);

        /** TECNICOS */
        // Tecnico lider
        $tecnico = User::create([
            'name' => 'JPILAY',
            'email' => 'jpilay@jpconstrucred.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_EMPLEADO, User::ROL_TECNICO_JEFE_CUADRILLA, User::ROL_TECNICO_CABLISTA);
        $tecnico->empleado()->create([
            'nombres' => 'JAIME LEONEL',
            'apellidos' => 'PILAY PEÑAFIEL',
            'identificacion' => '0707415236',
            'telefono' => '0987456332',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '3',
            'sucursal_id' => $localidad_cuenca->id,
            'grupo_id' => 1,
        ]);

        $tecnico = User::create([
            'name' => 'ATIGUA',
            'email' => 'atigua@jpconstrucred.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_EMPLEADO, User::ROL_TECNICO_SECRETARIO);
        $tecnico->empleado()->create([
            'nombres' => 'ALEXANDER LORENZO',
            'apellidos' => 'TIGUA PILLASAGUA',
            'identificacion' => '0707474236',
            'telefono' => '0987456332',
            'fecha_nacimiento' => '2010-05-12',
            'jefe_id' => '6',
            'sucursal_id' => $localidad_machala->id,
            'grupo_id' => 1,
        ]);

        $tecnico = User::create([
            'name' => 'CFERNANDEZ',
            'email' => 'cfernandez@jp.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_EMPLEADO, User::ROL_TECNICO_JEFE_CUADRILLA);
        $tecnico->empleado()->create([
            'nombres' => 'CARLOS',
            'apellidos' => 'FERNANDEZ',
            'identificacion' => '0707415231',
            'telefono' => '0987456331',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '3',
            'sucursal_id' => $localidad_cuenca->id,
            'grupo_id' => 3,
        ]);

        $tecnico = User::create([
            'name' => 'OSANCHEZ',
            'email' => 'osanchez@jpconstrucred.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_EMPLEADO, User::ROL_TECNICO_JEFE_CUADRILLA);
        $tecnico->empleado()->create([
            'nombres' => 'OSCAR OMAR',
            'apellidos' => 'SANCHEZ QUIROZ',
            'identificacion' => '0707415232',
            'telefono' => '0987456332',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '3',
            'sucursal_id' => $localidad_cuenca->id,
            'grupo_id' => 2,
        ]);

        $tecnico = User::create([
            'name' => 'JUAREZ',
            'email' => 'bjuarez@jp.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_EMPLEADO, User::ROL_TECNICO_AYUDANTE);
        $tecnico->empleado()->create([
            'nombres' => 'BENITO',
            'apellidos' => 'JUAREZ',
            'identificacion' => '0707415235',
            'telefono' => '0987456333',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '3',
            'sucursal_id' => $localidad_cuenca->id,
            'grupo_id' => 1,
        ]);

        $tecnico = User::create([
            'name' => 'RSANCHEZ',
            'email' => 'rsanchez@jp.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_EMPLEADO, User::ROL_CHOFER);
        $tecnico->empleado()->create([
            'nombres' => 'RICK',
            'apellidos' => 'SANCHEZ',
            'identificacion' => '0707415237',
            'telefono' => '0987456334',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '3',
            'sucursal_id' => $localidad_cuenca->id,
            'grupo_id' => 1,
        ]);

        $tecnico = User::create([
            'name' => 'LARMIJOS',
            'email' => 'larmijos@jp.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_EMPLEADO, User::ROL_TECNICO_SECRETARIO);
        $tecnico->empleado()->create([
            'nombres' => 'LEONARLO',
            'apellidos' => 'ARMIJOS',
            'identificacion' => '0707415233',
            'telefono' => '0987456333',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '3',
            'sucursal_id' => $localidad_cuenca->id,
            'grupo_id' => 2,
        ]);

        $tecnico = User::create([
            'name' => 'PCARRION',
            'email' => 'pcarrion@jp.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_EMPLEADO, User::ROL_TECNICO_SECRETARIO);
        $tecnico->empleado()->create([
            'nombres' => 'POLO',
            'apellidos' => 'CARRIÓN',
            'identificacion' => '0707415234',
            'telefono' => '0987456334',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '3',
            'sucursal_id' => $localidad_cuenca->id,
            'grupo_id' => 3,
        ]);

        /**
         * -----------
         * Informatica
         * -----------
         * Coordinador
         */
        $coordinador = User::create([
            'name' => 'YLOVERA',
            'email' => 'ylovera@jp.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_EMPLEADO, User::ROL_COORDINADOR);
        $coordinador->empleado()->create([
            'nombres' => 'YEFRAINA',
            'apellidos' => 'LOVERA',
            'identificacion' => '0707417879',
            'telefono' => '0987498564',
            'fecha_nacimiento' => '2000-05-12',
            'jefe_id' => '2',
            'sucursal_id' => $localidad_machala->id,
        ]);

        $tecnico = User::create([
            'name' => 'WCORDOVA',
            'email' => 'wcordova@jp.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_EMPLEADO, User::ROL_ADMINISTRATIVO);
        $tecnico->empleado()->create([
            'nombres' => 'WILSON',
            'apellidos' => 'CORDOVA',
            'identificacion' => '0750360919',
            'telefono' => '0992200572',
            'fecha_nacimiento' => '1997-06-29',
            'jefe_id' => '21',
            'sucursal_id' => $localidad_machala->id,
        ]);
        $tecnico = User::create([
            'name' => 'JCUESTA',
            'email' => 'jcuesta@jp.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_EMPLEADO, User::ROL_ADMINISTRATIVO);
        $tecnico->empleado()->create([
            'nombres' => 'JUAN',
            'apellidos' => 'CUESTA',
            'identificacion' => '0705570679',
            'telefono' => '0998474965',
            'fecha_nacimiento' => '1996-05-12',
            'jefe_id' => '21',
            'sucursal_id' => $localidad_machala->id,
        ]);

        $tecnico = User::create([
            'name' => 'JLEIVER',
            'email' => 'leiver@jp.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_EMPLEADO, User::ROL_FISCALIZADOR);
        $tecnico->empleado()->create([
            'nombres' => 'LEIVER JOAO',
            'apellidos' => 'VILLEGAS CANAVICHE',
            'identificacion' => '0701234568',
            'telefono' => '0998474966',
            'fecha_nacimiento' => '1995-05-12',
            'jefe_id' => '2',
            'sucursal_id' => $localidad_machala->id,
        ]);
    }
}
