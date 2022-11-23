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
        //Localidad
        $localidad_machala = Sucursal::create(['lugar' => 'MACHALA', 'telefono' => '0965421', 'correo' => 'oficina_matriz@jp.com']);
        $localidad_sto_domingo = Sucursal::create(['lugar' => 'SANTO DOMINGO', 'telefono' => '0965421', 'correo' => 'oficina_santo_domingo@jp.com']);
        $localidad_cuenca = Sucursal::create(['lugar' => 'CUENCA', 'telefono' => '0965421', 'correo' => 'oficina_cuenca@jp.com']);
        $localidad_guayaquil = Sucursal::create(['lugar' => 'GUAYAQUIL', 'telefono' => '0965421', 'correo' => 'oficina_guayaquil@jp.com']);

        // SuperAdministrador
        $admin = User::create([
            'name' => 'ADMINISTRADOR',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
        ])->assignRole(User::ROL_ADMINISTRADOR);
        $admin->empleado()->create([
            'nombres' => 'Superusuario',
            'apellidos' => 'Administrador',
            'sucursal_id' => $localidad_machala->id
        ]);


        // Gerente
        $gerente = User::create([
            'name' => 'PPAZMINO',
            'email' => 'gerente@jp.com',
            'password' => bcrypt('password'),
        ])->assignRole(User::ROL_GERENTE);
        $gerente->empleado()->create([
            'nombres' => 'PATRICIO',
            'apellidos' => 'PAZMIÑO',
            'identificacion' => '0702875618001',
            'telefono' => '0987456748',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '2',
            'sucursal_id' => $localidad_machala->id
        ]);


        // Coordinador
        $coordinador = User::create([
            'name' => 'MJARAMILLO',
            'email' => 'mjaramillo@jp.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_COORDINADOR, User::ROL_EMPLEADO);
        $coordinador->empleado()->create([
            'nombres' => 'MARILÚ',
            'apellidos' => 'JARAMILLO',
            'identificacion' => '0745125487',
            'telefono' => '0987456',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '2',
            'sucursal_id' => $localidad_machala->id
        ]);

        // RR HH
        $recursos_humanos = User::create([
            'name' => 'LPESANTES',
            'email' => 'manuel@jp.com',
            'password' => bcrypt('password'),
        ])->assignRole(User::ROL_RECURSOS_HUMANOS);
        $recursos_humanos->empleado()->create([
            'nombres' => 'LUIS MANUEL',
            'apellidos' => 'PESANTEZ MORA',
            'identificacion' => '0784956230',
            'telefono' => '0987456747',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '2',
            'sucursal_id' => $localidad_machala->id
        ]);

        // Coordinador
        $coordinador_sto_domingo = User::create([
            'name' => 'BCHAMBA',
            'email' => 'bchamba@jp.com',
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_COORDINADOR, User::ROL_BODEGA);
        $coordinador_sto_domingo->empleado()->create([
            'nombres' => 'BRYAN',
            'apellidos' => 'CHAMBA',
            'identificacion' => '0745185287',
            'telefono' => '0987456741',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '2',
            'sucursal_id' => $localidad_sto_domingo->id
        ]);

        // Coordinador
        $coordinador_telconet = User::create([
            'name' => 'DLOJA',
            'email' => 'dloja@jp.com',
            'password' => bcrypt('password'),
        ])->assignRole(User::ROL_COORDINADOR);
        $coordinador_telconet->empleado()->create([
            'nombres' => 'DARIO',
            'apellidos' => 'LOJA',
            'identificacion' => '0745124163',
            'telefono' => '0987456785',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '2',
            'sucursal_id' => $localidad_machala->id
        ]);

        // Asistente de bodega 1
        $bodeguero1 = User::create([
            'name' => 'CALBARRACIN',
            'email' => 'asistentebodega1@jp.com',
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_BODEGA, User::ROL_EMPLEADO);
        $bodeguero1->empleado()->create([
            'nombres' => 'CRISTIAN',
            'apellidos' => 'ALBARRACIN',
            'identificacion' => '0708549625',
            'telefono' => '0987456112',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '2',
            'sucursal_id' => $localidad_machala->id
        ]);

        // Asistente de bodega 2
        $bodeguero2 = User::create([
            'name' => 'JTORRES',
            'email' => 'asistentebodega2@jp.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->assignRole(User::ROL_BODEGA);
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
        ])->assignRole(User::ROL_COMPRAS);
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
        ])->assignRole(User::ROL_ADMINISTRATIVO);
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
        ])->assignRole(User::ROL_CONTABILIDAD);
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
            'name' => 'PRAMIREZ',
            'email' => 'pramirez@jp.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->assignRole(User::ROL_TECNICO_LIDER);
        $tecnico->empleado()->create([
            'nombres' => 'PEDRO',
            'apellidos' => 'RAMIREZ',
            'identificacion' => '0707415236',
            'telefono' => '0987456332',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '3',
            'sucursal_id' => $localidad_cuenca->id,
            'grupo_id' => 1,
        ]);

        $tecnico = User::create([
            'name' => 'AROGEL',
            'email' => 'andres@jp.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->assignRole(User::ROL_TECNICO_LIDER);
        $tecnico->empleado()->create([
            'nombres' => 'ANDRES',
            'apellidos' => 'ROGEL',
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
        ])->assignRole(User::ROL_TECNICO);
        $tecnico->empleado()->create([
            'nombres' => 'CARLOS',
            'apellidos' => 'FERNANDEZ',
            'identificacion' => '0707415231',
            'telefono' => '0987456331',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '3',
            'sucursal_id' => $localidad_cuenca->id,
            'grupo_id' => 1,
        ]);

        $tecnico = User::create([
            'name' => 'OGUTIERREZ',
            'email' => 'ogutierrez@jp.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->assignRole(User::ROL_TECNICO);
        $tecnico->empleado()->create([
            'nombres' => 'OMAR',
            'apellidos' => 'GUTIERREZ',
            'identificacion' => '0707415232',
            'telefono' => '0987456332',
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
        ])->assignRole(User::ROL_TECNICO);
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
        ])->assignRole(User::ROL_TECNICO);
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
        ])->assignRole(User::ROL_COORDINADOR);
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
            'jefe_id' => '19',
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
            'identificacion' => '0701234567',
            'telefono' => '0998474965',
            'fecha_nacimiento' => '1996-05-12',
            'jefe_id' => '19',
            'sucursal_id' => $localidad_machala->id,
        ]);

        $tecnico = User::create([
            'name' => 'JLEIVER',
            'email' => 'leiver@jp.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_EMPLEADO, User::ROL_FISCALIZADOR);
        $tecnico->empleado()->create([
            'nombres' => 'JOAO',
            'apellidos' => 'LEIVER',
            'identificacion' => '0701234568',
            'telefono' => '0998474966',
            'fecha_nacimiento' => '1995-05-12',
            'jefe_id' => '19',
            'sucursal_id' => $localidad_machala->id,
        ]);

        //

        // $tecnico = User::create([, 'password' => bcrypt('password')])->syncRoles(User::ROL_EMPLEADO, User::ROL_TECNICO);
        //INSERT INTO usuario (nombre,apellido,correo_electronico) VALUES

        /* $usuarios = [
            ['ADMINISTRADOR NEW ADMINISTRADOR', 'ylovera@jeanpazmino.com', 1, date("Y-m-d"), bcrypt('password')],
            ['ANTHONY DANIEL BERSOZA SANCHEZ', 'abersoza@jeanpazmino.com', 0, date("Y-m-d"), bcrypt('password')],
            ['ALBERTO ALFREDO MORENO GARINO', 'amoreno@jeanpazmino.com', 0, date("Y-m-d"), bcrypt('password')],
            ['BRYAN LEONARDO CHAMBA MONTES', 'bchamba@jeanpazmino.com', 1, date("Y-m-d"), bcrypt('password')],
            ['DIEGO HERNAN INAMAGUA LALA', 'dinamagua@jeanpazmino.com', 1, date("Y-m-d"), bcrypt('password')],
            ['RUBEN DARIO LOJA TORRES', 'dloja@jeanpazmino.com', 1, date("Y-m-d"), bcrypt('password')],
            ['ELVIS AGUSTIN BRICEÑO ARMIJOS', 'ebriceno@jeanpazmino.com', 0, date("Y-m-d"), bcrypt('password')],
            ['EYDER ESTEBAN PEREIRA NAVAS', 'epereira@jeanpazmino.com', 1, date("Y-m-d"), bcrypt('password')],
            ['FREDY GEOVANNY QUITUISACA SANCHEZ', 'fquituisaca@jeanpazmino.com', 1, date("Y-m-d"), bcrypt('password')],
            ['FRANCISCO WLADIMIR SALAZAR SOLORZANO', 'fsalazar@jeanpazmino.com', 0, date("Y-m-d"), bcrypt('password')],
            ['ILIANA ESTEFANIA PALADINEZ HEREDIA', 'ipaladinez@jeanpazmino.com', 0, date("Y-m-d"), bcrypt('password')],
            ['JONATHAN FRANCISCO AGUILAR ALCIVAR', 'jaguilar@jeanpazmino.com', 1, date("Y-m-d"), bcrypt('password')],
            ['JOSEPH CARLOS CHONILLO SARMIENTO', 'jchonillo@jeanpazmino.com', 0, date("Y-m-d"), bcrypt('password')],
            ['JORGE GUILLERMO LEITON RIZZO', 'jleiton@jeanpazmino.com', 0, date("Y-m-d"), bcrypt('password')],
            ['JIMMY JAVIER MACAS CHUCHUCA', 'jmacas@jeanpazmino.com', 1, date("Y-m-d"), bcrypt('password')],
            ['JAIME RENAN MENDOZA ALCIVAR', 'jmendoza@jeanpazmino.com', 0, date("Y-m-d"), bcrypt('password')],
            ['JONNATHAN ISMAEL PACHECO VACA', 'jpacheco@jeanpazmino.com', 0, date("Y-m-d"), bcrypt('password')],
            ['JONATHAN ADRIAN PANCHI DIAZ', 'jpanchi@jeanpazmino.com', 1, date("Y-m-d"), bcrypt('password')],
            ['JEAN PATRICIO PAZMIÑO BARROS', 'jpazmino@jeanpazmino.com', 1, date("Y-m-d"), bcrypt('password')],
            ['JONATHAN ANTONIO TENESACA AZOGUE', 'jtenesaca@jeanpazmino.com', 1, date("Y-m-d"), bcrypt('password')],
            ['JACKSON JAVIER VALENCIA ARTURO', 'jvalencia@jeanpazmino.com', 0, date("Y-m-d"), bcrypt('password')],
            ['KATHERINE ESTEFANIA SEGARRA ORTIZ', 'ksegarra@jeanpazmino.com', 0, date("Y-m-d"), bcrypt('password')],
            ['ANGEL GEOVANNY CHAMBA ENRIQUEZ', 'ACHAMBA@JEANPAZMINO.COM', 1, date("Y-m-d"), bcrypt('password')],
            ['LUIS  ALBERTO GONZABAY PEÑFIEL', 'lgonzabay@jeanpazmino.com', 1, date("Y-m-d"), bcrypt('password')],
            ['AIDE MARILU JARAMILLO TORO', 'mjaramillo@jeanpazmino.com', 1, date("Y-m-d"), bcrypt('password')],
            ['NICOLAS EDUARDO PAZMINO BARROS', 'npazmino@jeanpazmino.com', 1, date("Y-m-d"), bcrypt('password')],
            ['DANIEL PATRICK APOLO RAMIREZ', 'papolo@jeanpazmino.com', 1, date("Y-m-d"), bcrypt('password')],
            ['PETER ANTONIO FIGUEROA CASTRO', 'pfigueroa@jeanpazmino.com', 0, date("Y-m-d"), bcrypt('password')],
            ['RAUL MAURICIO MUÑOZ', 'rmunoz@jeanpazmino.com', 0, date("Y-m-d"), bcrypt('password')],
            ['STIVEN JEFFERSON BONE MOTTA', 'sbone@jeanpazmino.com', 1, date("Y-m-d"), bcrypt('password')],
            ['SIMON GABRIEL CARBO PACHECO', 'scarbo@jeanpazmino.com', 0, date("Y-m-d"), bcrypt('password')],
            ['SANTIAGO ALEJANDRO GRANDA GRANDA', 'sgranda@jeanpazmino.com', 0, date("Y-m-d"), bcrypt('password')],
            ['YANINA VANESSA LOJA TORRES', 'ASISTENTE_ADMINISTRATIVO@JEANPAZMINO.COM', 1, date("Y-m-d"), bcrypt('password')],
            ['VICTOR HUGO RIERA GAVILANEZ', 'vriera@jeanpazmino.com', 1, date("Y-m-d"), bcrypt('password')],
            ['WILSON RODRIGO GRANDA FAJARDO', 'wgranda@jeanpazmino.com', 0, date("Y-m-d"), bcrypt('password')],
            ['WILMER FABIAN LEMA', 'wlema@jeanpazmino.com', 1, date("Y-m-d"), bcrypt('password')],
            ['XAVIER FERNANDO SIRANAULA JIMENEZ', 'xsiranaula@jeanpazmino.com', 0, date("Y-m-d"), bcrypt('password')],
            ['JEFFERSON RAFAEL ABAD ZUMBA', 'JABAD@JEANPAZMINO.COM', 1, date("Y-m-d"), bcrypt('password')],
            ['CHRISTIAN PAUL TAPIA IDROVO', 'CTAPIA@JEANPAZMINO.COM', 0, date("Y-m-d"), bcrypt('password')],
            ['JONNATHAN ADRIAN VEINTIMILLA SEGARRA', 'jveintimilla@jeanpazmino.com', 1, date("Y-m-d"), bcrypt('password')],
            ['JORDAN DAVID CEDEÑO VERGARA', 'JCEDENO@JEANPAZMINO.COM', 1, date("Y-m-d"), bcrypt('password')],
            ['CESAR ANTONIO VILLACIS ROMERO', 'CVILLACIS@JEANPAZMINO.COM', 0, date("Y-m-d"), bcrypt('password')],
            ['PATRICIO RODRIGO MENDEZ', 'PMENDEZ@JEANPAZMINO.COM', 1, date("Y-m-d"), bcrypt('password')],
            ['PATRICIA MOROCHO', 'PMROCHO@JEANPAZMINO.COM', 0, date("Y-m-d"), bcrypt('password')],
            ['INGRID GEANELLA LIMA AGUIRRE', 'ILIMA@JEANPAZMINO.COM', 0, date("Y-m-d"), bcrypt('password')],
            ['ISIDRO FRANCISCO VILLEGAS CANAVICHE', 'IVILLEGAS@JEANPAZMINO.COM', 0, date("Y-m-d"), bcrypt('password')],
            ['JEISON ENRIQUE SATAMA GARZON', 'JSATAMA@JEANPAZMINO.COM', 0, date("Y-m-d"), bcrypt('password')],
            ['SALLY ANDREA CAICEDO PARRA', 'SCAICEDO@JEANPAZMINO.COM', 1, date("Y-m-d"), bcrypt('password')],
            ['DARWIN JOSE MERO CASTILLO', 'DMERO@JEANPAZMINO.COM', 1, date("Y-m-d"), bcrypt('password')],
            ['PABLO SEBASTIAN QUIZHPI MOLINA', 'PQUIZHPI@JEANPAZMINO.COM', 1, date("Y-m-d"), bcrypt('password')],
            ['DOLORES DEL ROSARIO VIZUETE SALAZAR', 'DVIZUETE@JEANPAZMINO.COM', 0, date("Y-m-d"), bcrypt('password')],
            ['BRYAN STEVEN LOPEZ ORMAZA', 'BLOPEZ@JEANPAZMINO.COM', 0, date("Y-m-d"), bcrypt('password')],
            // ['JOSE NIBALDO MOREIRA GONZALES', '', 0, date("Y-m-d"), bcrypt('password')],
            ['JONATHAN AURELIO JORDAN HIDALGO', 'JJORDAN@JEANPAZMINO.COM', 1, date("Y-m-d"), bcrypt('password')],
            // ['LEONARDO ENRIQUE BAZURTO BRAVO', '', 1, date("Y-m-d"), bcrypt('password')],
            ['MARIA FERNANDA TORRES ABAD', 'MA.FERNANDATORRESA@GMAIL.COM', 0, date("Y-m-d"), bcrypt('password')],
            ['PEDRO JOEL BUSTOS MOREIRA', 'PBUSTOS@JEANPAZMINO.COM', 1, date("Y-m-d"), bcrypt('password')],
            ['LUIS MANUEL PESANTEZ MORA', 'LPESANTEZ@JENAPAZMINO.COM', 1, date("Y-m-d"), bcrypt('password')],
            ['LUIS FERNANDO BAJAÑA CHONILLO', 'LBAJANA@JEANPAZMINO.COM', 0, date("Y-m-d"), bcrypt('password')],
            ['MIGUEL FERNANDO LINO QUIMIS', 'MLINO@JEANPAZMINO.COM', 0, date("Y-m-d"), bcrypt('password')],
            ['MIGUEL ANGEL ENGRACIA GARCIA', 'MENGRACIA@JENAPAZMINO.COM', 0, date("Y-m-d"), bcrypt('password')],
            ['JUAN JOSE TORRES QUEZADA', 'JTORRES@JEANPAZMINO.COM', 1, date("Y-m-d"), bcrypt('password')],
            ['RONALD DAVID VIVANCO CARPIO', 'RVIVANCO@JEANPAZMINO.COM', 0, date("Y-m-d"), bcrypt('password')],
            ['PEDRO MORONI AGUILAR FERNANDEZ', 'PAGUILAR@JEANPAZMINO.COM', 1, date("Y-m-d"), bcrypt('password')],
            ['DAVID IVAN CHAMBA MORA', 'DCHAMBA@JEANPAZMINO.COM', 0, date("Y-m-d"), bcrypt('password')],
            ['EDISON MARTIN PALMA', 'EPALMA@JEANPAZMINO.COM', 1, date("Y-m-d"), bcrypt('password')],
            ['JOSE LUIS ANTEPARA CARDENAS', 'JANTEPARA@JEANPAZMINO.COM', 0, date("Y-m-d"), bcrypt('password')],
            ['OSWALDO MOLINA CEDEÑO', 'OMOLINA@JEANPAZMINO.COM', 0, date("Y-m-d"), bcrypt('password')],
            ['CRISTHIAN ANDRES ALBARRACIN VILAGRAN', 'CALBARRACIN@JEANPAZMINO.COM', 1, date("Y-m-d"), bcrypt('password')],
            ['CONSULTA CONSULTA', 'CONSULTA@JEANPAZMINO.COM', 1, date("Y-m-d"), bcrypt('password')],
            ['MARIUXI ISABEL VALAREZO ARMIJOS', 'MVALAREZO@JEANPAZMINO.COM', 1, date("Y-m-d"), bcrypt('password')],
            ['CRISTIAN RAUL LAAZ HIDROVO', 'CLAAZ@JEANPAZMINO.COM', 1, date("Y-m-d"), bcrypt('password')],
            ['ANGEL FERNANDO ARMIJOS RIOS', 'FARMIJOS@JEANPAZMINO.COM', 1, date("Y-m-d"), bcrypt('password')],
            ['JORGE LEONARDO MEDINA SOLEDISPA', 'JMEDINA@JEANPAZMINO.COM', 0, date("Y-m-d"), bcrypt('password')],
            ['DIEGO HERNAN CRUZ ESPINOZA', 'DCRUZ@JEANPAZMINO.COM', 1, date("Y-m-d"), bcrypt('password')],
            ['JHON EDUARDO SAETAMA SANMARTIN', 'JSAETAMA@JEANPAZMINO.COM', 1, date("Y-m-d"), bcrypt('password')],
            ['WILSON STEEVEN CORDOVA ERAS', 'WCORDOVA@JEANPAZMINO.COM', 1, date("Y-m-d"), bcrypt('password')],
            ['ANGEL DARIO CEDEÑO BRAVO', 'ACEDENO@JEANPAZMINO.COM', 1, date("Y-m-d"), bcrypt('password')],
            ['ALBERTO DANIEL SALAS REINA', 'ASALAS@JEANPAZMINO.COM', 1, date("Y-m-d"), bcrypt('password')],
            ['ALEXANDER LEONEL TOAPANTA ARROYO', 'ATOAPANTA@JEANPAZMINO.COM', 1, date("Y-m-d"), bcrypt('password')],
            ['KRISTEL PAULETTE ASTUDILLO YEPEZ', 'KASTUDILLO@JEAMPAZMINO.COM', 1, date("Y-m-d"), bcrypt('password')],
            ['JUAN BRYAN CUESTA VERA', 'JCUESTA@JEANPAZMINO.COM', 1, date("Y-m-d"), bcrypt('password')],
            ['FIDEL ANTONIO CORTEZ CASTRO', 'FCASTRO@JEANPAZMINO.COM', 1, date("Y-m-d"), bcrypt('password')],
            ['JOSE JACINTO PALMA CHOEZ', 'JPALMA@JEANPAZMINO.COM', 1, date("Y-m-d"), bcrypt('password')],
            ['JIMMY MARCOS BAQUE PIN', 'JBAQUE@JEANPAZMINO.COM', 1, date("Y-m-d"), bcrypt('password')],
            ['KLEBER ENRIQUE GUERRA CHAPIRO', 'KGUERRA@JEANPAZMINO.COM', 0, date("Y-m-d"), bcrypt('password')],
            // ['WILLIAM SANTIAGO ALDAS OCHOA', '', 0, date("Y-m-d"), bcrypt('password')],
            ['ARNALDO JOSE SERRANO COELLO', 'ASERRANO@JEANPAZMINO.COM', 1, date("Y-m-d"), bcrypt('password')],
            ['ALEXANDER LORENZO TIGUA PILLASAGUA', 'ATIGUA@JEANPAZMINO.COM', 1, date("Y-m-d"), bcrypt('password')],
            ['RONALD MAURICIO ALCOCER PIN', 'RALCOCER@JEANPAZMINO.COM', 1, date("Y-m-d"), bcrypt('password')],
            ['ANGELO JIMENEZ', 'AJIMENEZ@JEANPAZMINO.COM', 1, date("Y-m-d"), bcrypt('password')],
            ['ANGEL CIRINO MERELLO SANCHEZ', 'AMERELLO@JEANPAZMINO.COM', 1, date("Y-m-d"), bcrypt('password')],
            // ['LEIVER JOAO CELI GONZALEZ', '', 1, date("Y-m-d"), bcrypt('password')],
            ['ASHLEY MILENA ORELLANA FERNANDEZ', 'AORELLANA@JEANPAZMINO.COM', 1, date("Y-m-d"), bcrypt('password')],
        ];

        foreach ($usuarios as $fila) {
            $usuario = DB::insert('INSERT INTO users (name, email, status, email_verified_at, password) VALUES(?,?,?, ?, ?)', $fila);

        } */

        /* $gerente->empleado()->create([
            'nombres' => 'PATRICIO',
            'apellidos' => 'PAZMIÑO',
            'identificacion' => '0702875618001',
            'telefono' => '0987456748',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '2',
            'sucursal_id' => $localidad_machala->id
        ]); */
    }
}
