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
            'email' => 'gerente@jpconstrucred.com',
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_GERENTE, User::ROL_EMPLEADO);
        // Coordinador
        $coordinador = User::create([
            'name' => 'MJARAMILLO',
            'email' => 'mjaramillo@jpconstrucred.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_COORDINADOR, User::ROL_EMPLEADO);
        // RR HH
        $recursos_humanos = User::create([
            'name' => 'LPESANTES',
            'email' => 'manuel@jpconstrucred.com',
            'password' => bcrypt('0706297280'),
        ])->syncRoles(User::ROL_EMPLEADO, User::ROL_RECURSOS_HUMANOS);
        // Coordinador
        $coordinador_sto_domingo = User::create([
            'name' => 'BCHAMBA',
            'email' => 'bchamba@jpconstrucred.com',
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_EMPLEADO, User::ROL_COORDINADOR, User::ROL_BODEGA);
        // Coordinador
        $coordinador_telconet = User::create([
            'name' => 'DLOJA',
            'email' => 'dloja@jpconstrucred.com',
            'password' => bcrypt('password'),
        ])->assignRole(User::ROL_COORDINADOR, User::ROL_EMPLEADO);
        // Asistente de bodega 1
        $bodeguero1 = User::create([
            'name' => 'CALBARRACIN',
            'email' => 'asistentebodega1@jpconstrucred.com',
            'password' => bcrypt('0703324319'),
        ])->syncRoles(User::ROL_BODEGA, User::ROL_EMPLEADO);
        // Asistente de bodega 2
        $bodeguero2 = User::create([
            'name' => 'JTORRES',
            'email' => 'asistentebodega2@jpconstrucred.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('0705191054'),
        ])->syncRoles(User::ROL_EMPLEADO, User::ROL_BODEGA);
        // Jefe tecnico
        $jveintimilla = User::create([
            'name' => 'JVEINTIMILLA',
            'email' => 'jveintimilla@jpconstrucred.com',
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_JEFE_TECNICO, User::ROL_EMPLEADO);


        //Localidad
        $localidad_machala = Sucursal::create(['lugar' => 'MACHALA', 'telefono' => '0965421', 'correo' => 'oficina_matriz@jpconstrucred.com', 'administrador_id' => 7]);
        $localidad_sto_domingo = Sucursal::create(['lugar' => 'SANTO DOMINGO', 'telefono' => '0965421', 'correo' => 'oficina_santo_domingo@jpconstrucred.com', 'administrador_id' => 5]);
        $localidad_cuenca = Sucursal::create(['lugar' => 'CUENCA', 'telefono' => '0965421', 'correo' => 'oficina_cuenca@jpconstrucred.com', 'administrador_id' => 8]);
        $localidad_guayaquil = Sucursal::create(['lugar' => 'GUAYAQUIL', 'telefono' => '0965421', 'correo' => 'oficina_guayaquil@jpconstrucred.com', 'administrador_id' => 1]);



        $admin->empleado()->create([
            'nombres' => 'Superusuario',
            'apellidos' => 'Administrador',
        ]);


        $gerente->empleado()->create([
            'nombres' => 'PATRICIO',
            'apellidos' => 'PAZMIÑO',
            'identificacion' => '0702875618001',
            'telefono' => '0987456748',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '2',
        ]);

        $jveintimilla->empleado()->create([
            'nombres' => 'JONATHAN',
            'apellidos' => 'VEINTIMILLA',
            'identificacion' => '0745812451001',
            'telefono' => '0987459218',
            'fecha_nacimiento' => '2011-05-12',
            'jefe_id' => '2',
        ]);

        $coordinador->empleado()->create([
            'nombres' => 'MARILÚ',
            'apellidos' => 'JARAMILLO',
            'identificacion' => '0745125487',
            'telefono' => '0987456',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '2',
        ]);

        $recursos_humanos->empleado()->create([
            'nombres' => 'LUIS MANUEL',
            'apellidos' => 'PESANTEZ MORA',
            'identificacion' => '0784956230',
            'telefono' => '0987456747',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '2',
        ]);

        $coordinador_sto_domingo->empleado()->create([
            'nombres' => 'BRYAN',
            'apellidos' => 'CHAMBA',
            'identificacion' => '0745185287',
            'telefono' => '0987456741',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '2',
        ]);

        $coordinador_telconet->empleado()->create([
            'nombres' => 'DARIO',
            'apellidos' => 'LOJA',
            'identificacion' => '0745124163',
            'telefono' => '0987456785',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '2',
        ]);

        $bodeguero1->empleado()->create([
            'nombres' => 'CRISTIAN',
            'apellidos' => 'ALBARRACIN',
            'identificacion' => '0708549625',
            'telefono' => '0987456112',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '2',
        ]);

        $bodeguero2->empleado()->create([
            'nombres' => 'JUAN JOSE',
            'apellidos' => 'TORRES',
            'identificacion' => '0704126352',
            'telefono' => '0987456965',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '2',
        ]);

        // Departamento de compras
        $compras = User::create([
            'name' => 'ILIMA',
            'email' => 'asistentecompras@jpconstrucred.com',
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
        ]);

        // Personal administrativo
        $administrativo = User::create([
            'name' => 'SSARMIENTO',
            'email' => 'santiago@jpconstrucred.com',
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
        ]);


        // Activos fijos
        $activos_fijos = User::create([
            'name' => 'PAGUILAR',
            'email' => 'pedro@jpconstrucred.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('0706751393'),
        ])->syncRoles([User::ROL_ADMINISTRATIVO, User::ROL_ACTIVOS_FIJOS, User::ROL_BODEGA, User::ROL_EMPLEADO]);
        $activos_fijos->empleado()->create([
            'nombres' => 'PEDRO',
            'apellidos' => 'AGUILAR',
            'identificacion' => '0702041526',
            'telefono' => '0989857463',
            'fecha_nacimiento' => '1993-05-12',
            'jefe_id' => '2',
        ]);

        // Contabilidad
        $contabilidad = User::create([
            'name' => 'IVALAREZO',
            'email' => 'isa@jpconstrucred.com',
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_EMPLEADO, User::ROL_CONTABILIDAD);
        $contabilidad->empleado()->create([
            'nombres' => 'ISABEL',
            'apellidos' => 'VALAREZO',
            'identificacion' => '0780401526',
            'telefono' => '0987457845',
            'fecha_nacimiento' => '2000-05-12',
            'jefe_id' => '2',
        ]);

        $sso = User::create([
            'name'=>'ASERRANO',
            'email'=>'aserrano@jpconstrucred.com',
            'password'=>bcrypt('password'),
        ])->syncRoles([User::ROL_EMPLEADO, User::ROL_SSO]);
        $sso->empleado()->create([
            'nombres' => 'ARNALDO JOSE',
            'apellidos' => 'SERRANO COELLO',
            'identificacion' => '0703517029',
            'telefono' => '0982984348',
            'fecha_nacimiento' => '1978-08-06',
            'jefe_id' => '2',
        ]);

        /** TECNICOS */
        // Tecnico lider
        $tecnico = User::create([
            'name' => 'JPILAY',
            'email' => 'jpilay@jpconstrucred.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_EMPLEADO, User::ROL_TECNICO, User::ROL_TECNICO_LIDER_DE_GRUPO);
        $tecnico->empleado()->create([
            'nombres' => 'JAIME LEONEL',
            'apellidos' => 'PILAY PEÑAFIEL',
            'identificacion' => '0707415236',
            'telefono' => '0987456332',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '3',
            'grupo_id' => 1,
            'cargo_id' => 5,
        ]);

        $tecnico = User::create([
            'name' => 'ATIGUA',
            'email' => 'atigua@jpconstrucred.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_EMPLEADO, User::ROL_TECNICO);
        $tecnico->empleado()->create([
            'nombres' => 'ALEXANDER LORENZO',
            'apellidos' => 'TIGUA PILLASAGUA',
            'identificacion' => '1315288645',
            'telefono' => '0987456332',
            'fecha_nacimiento' => '2010-05-12',
            'jefe_id' => '6',
            'grupo_id' => 1,
            'cargo_id' => 6,
        ]);

        $tecnico = User::create([
            'name' => 'CFERNANDEZ',
            'email' => 'cfernandez@jpconstrucred.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_EMPLEADO, User::ROL_TECNICO);
        $tecnico->empleado()->create([
            'nombres' => 'CARLOS',
            'apellidos' => 'FERNANDEZ',
            'identificacion' => '0707415231',
            'telefono' => '0987456331',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '3',
            'grupo_id' => 3,
            'cargo_id' => 5,
        ]);

        $tecnico = User::create([
            'name' => 'OSANCHEZ',
            'email' => 'osanchez@jpconstrucred.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_EMPLEADO, User::ROL_TECNICO);
        $tecnico->empleado()->create([
            'nombres' => 'OSCAR OMAR',
            'apellidos' => 'SANCHEZ QUIROZ',
            'identificacion' => '0707415232',
            'telefono' => '0987456332',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '3',
            'grupo_id' => 2,
            'cargo_id' => 5,
        ]);

        $tecnico = User::create([
            'name' => 'JUAREZ',
            'email' => 'bjuarez@jpconstrucred.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_EMPLEADO, User::ROL_TECNICO);
        $tecnico->empleado()->create([
            'nombres' => 'BENITO',
            'apellidos' => 'JUAREZ',
            'identificacion' => '0707415235',
            'telefono' => '0987456333',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '3',
            'grupo_id' => 1,
            'cargo_id' => 8,
        ]);

        $tecnico = User::create([
            'name' => 'RSANCHEZ',
            'email' => 'rsanchez@jpconstrucred.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_EMPLEADO);
        $tecnico->empleado()->create([
            'nombres' => 'RICK',
            'apellidos' => 'SANCHEZ',
            'identificacion' => '0707415237',
            'telefono' => '0987456334',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '3',
            'grupo_id' => 1,
            'cargo_id' => 10,
        ]);

        $tecnico = User::create([
            'name' => 'LARMIJOS',
            'email' => 'larmijos@jpconstrucred.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_EMPLEADO, User::ROL_TECNICO);
        $tecnico->empleado()->create([
            'nombres' => 'LEONARLO',
            'apellidos' => 'ARMIJOS',
            'identificacion' => '0707415233',
            'telefono' => '0987456333',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '3',
            'grupo_id' => 2,
            'cargo_id' => 6,
        ]);

        $tecnico = User::create([
            'name' => 'PCARRION',
            'email' => 'pcarrion@jpconstrucred.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_EMPLEADO, User::ROL_TECNICO);
        $tecnico->empleado()->create([
            'nombres' => 'POLO',
            'apellidos' => 'CARRIÓN',
            'identificacion' => '0707415234',
            'telefono' => '0987456334',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '3',
            'grupo_id' => 3,
            'cargo_id' => 6,
        ]);

        /**
         * -----------
         * Informatica
         * -----------
         * Coordinador
         */
        $coordinador = User::create([
            'name' => 'YLOVERA',
            'email' => 'ylovera@jpconstrucred.com',
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
        ]);

        $tecnico = User::create([
            'name' => 'WCORDOVA',
            'email' => 'wcordova@jpconstrucred.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_EMPLEADO, User::ROL_ADMINISTRATIVO, User::ROL_COORDINADOR);
        $tecnico->empleado()->create([
            'nombres' => 'WILSON',
            'apellidos' => 'CORDOVA',
            'identificacion' => '0750360919',
            'telefono' => '0992200572',
            'fecha_nacimiento' => '1997-06-29',
            'jefe_id' => '2',
        ]);

        $tecnico = User::create([
            'name' => 'JCUESTA',
            'email' => 'jcuesta@jpconstrucred.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_EMPLEADO, User::ROL_ADMINISTRATIVO);
        $tecnico->empleado()->create([
            'nombres' => 'JUAN',
            'apellidos' => 'CUESTA',
            'identificacion' => '0705570679',
            'telefono' => '0998474965',
            'fecha_nacimiento' => '1996-05-12',
            'jefe_id' => '24',
        ]);

        $tecnico = User::create([
            'name' => 'HSIMBAÑA',
            'email' => 'hsimbana@jpconstrucred.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_EMPLEADO, User::ROL_ADMINISTRATIVO);
        $tecnico->empleado()->create([
            'nombres' => 'HENRY',
            'apellidos' => 'SIMBAÑA',
            'identificacion' => '0705570678',
            'telefono' => '0998474969',
            'fecha_nacimiento' => '1996-05-12',
            'jefe_id' => '24',
        ]);

        $tecnico = User::create([
            'name' => 'JLEIVER',
            'email' => 'leiver@jpconstrucred.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_EMPLEADO, User::ROL_FISCALIZADOR);
        $tecnico->empleado()->create([
            'nombres' => 'LEIVER JOAO',
            'apellidos' => 'CELI GONZALEZ',
            'identificacion' => '0701234568',
            'telefono' => '0998474966',
            'fecha_nacimiento' => '1995-05-12',
            'jefe_id' => '2',
        ]);
        $autorizador = User::create([
            'name' => 'AARMIJOS',
            'email' => 'AARMIJOS@JEANPAZMINO.COM',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_EMPLEADO, User::ROL_AUTORIZADOR);
        $autorizador->empleado()->create([
            'nombres' => 'ANGEL FERNANDO',
            'apellidos' => 'ARMIJOS RIOS',
            'identificacion' => '0706714649',
            'telefono' => '0994887502',
            'fecha_nacimiento' => '0000-00-00',
            'jefe_id' => '2',
           // 'sucursal_id' => $localidad_machala->id,
        ]);
        $autorizador = User::create([
            'name' => 'YLOJA',
            'email' => 'ASISTENTE_ADMINISTRATIVO@JEANPAZMINO.COM',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_EMPLEADO, User::ROL_AUTORIZADOR);
        $autorizador->empleado()->create([
            'nombres' => 'YANINA VANESSA',
            'apellidos' => 'LOJA TORRES',
            'identificacion' => '0703324863',
            'telefono' => '999910198',
            'fecha_nacimiento' => '1980-05-29',
            'jefe_id' => '2',
           // 'sucursal_id' => $localidad_machala->id,
        ]);
        $autorizador = User::create([
            'name' => 'JPAZMINO',
            'email' => 'jpazmino@jeanpazmino.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_EMPLEADO, User::ROL_AUTORIZADOR, User::ROL_CONTABILIDAD);
        $autorizador->empleado()->create([
            'nombres' => 'JEAN PATRICIO',
            'apellidos' => 'PAZMIÑO BARROS',
            'identificacion' => '0702875618',
            'telefono' => '995936695',
            'fecha_nacimiento' => '1974-07-07',
            'jefe_id' => '2',
           // 'sucursal_id' => $localidad_machala->id,
        ]);

    }
}
