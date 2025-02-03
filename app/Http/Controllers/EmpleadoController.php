<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmpleadoRequest;
use App\Http\Resources\EmpleadoResource;
use App\Http\Resources\EmpleadoRolePermisoResource;
use App\Http\Resources\Vehiculos\ConductorResource;
use App\Models\Departamento;
use App\Models\Empleado;
use App\Models\User;
use App\Models\Vehiculos\Conductor;
use App\Models\Vehiculos\Licencia;
use DateTime;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Src\App\ArchivoService;
use Src\App\EmpleadoService;
use Src\App\FondosRotativos\ReportePdfExcelService;
use Src\App\PolymorphicGenericService;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;
use Src\Shared\Utils;
use Throwable;


class EmpleadoController extends Controller
{
    private string $entidad = 'Empleado';
    private EmpleadoService $servicio;
    private ReportePdfExcelService $reporteService;
    private PolymorphicGenericService $polymorphicGenericService;
    private ArchivoService $archivoService;


    public function __construct()
    {
        $this->servicio = new EmpleadoService();
        $this->reporteService = new ReportePdfExcelService();
        $this->archivoService = new ArchivoService();
        $this->polymorphicGenericService = new PolymorphicGenericService();

        $this->middleware('can:puede.ver.empleados')->only('index', 'show');
        $this->middleware('can:puede.crear.empleados')->only('store');
        $this->middleware('can:puede.editar.empleados')->only('update');
        $this->middleware('can:puede.eliminar.empleados')->only('destroy');
    }

    public function list()
    {
        // Obtener parametros
        $rol = explode(',', request('rol'));
        $search = request('search');
        $campos = request('campos') ? explode(',', request('campos')) : '*';


        // Devuelve en un array al  empleado resposanble del departamento que se pase como parametro
        // Requiere de campos: es_responsable_departamento=true&departamento_id=
        if (request('es_responsable_departamento')) {
            $idResponsable = Departamento::find(request('departamento_id'))->responsable_id;
            if ($idResponsable) {
                return Empleado::where('id', $idResponsable)->get($campos);
            } else return [];
        }

        // Si es de RRHH devuelve incluso de inactivos
        if (auth()->user()->hasRole([User::ROL_RECURSOS_HUMANOS])) {
            return $this->servicio->obtenerTodosSinEstado();
        }

        if (request('empleados_autorizadores_gasto')) {
            return $this->servicio->obtenerEmpleadosAutorizadoresGasto();
        }
        /*  if ($user->hasRole([User::ROL_COORDINADOR, User::COORDINADOR_TECNICO, User::ROL_COORDINADOR_BACKUP, User::ROL_COORDINADOR_BODEGA])) {
            return Empleado::where('jefe_id', Auth::user()->empleado->id)->get($campos);
        }*/

        // Procesar respuesta
        if (request('rol')) return $this->servicio->getUsersWithRoles($rol, $campos); // EmpleadoResource::collection(Empleado::whereIn('usuario_id', User::role($rol)->pluck('id'))->get());
        if (request('campos')) return $this->servicio->obtenerTodosCiertasColumnas($campos);
        if ($search) return $this->servicio->search($search);

        return $this->servicio->obtenerTodos();
    }

    /**
     * Listar
     */
    public function index()
    {
        $results = $this->list();
        $results = EmpleadoResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     * @throws Throwable
     */
    public function store(EmpleadoRequest $request)
    {
        //  Log::channel('testing')->info('Log', ['request', $request->all()]);
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['jefe_id'] = $request->safe()->only(['jefe'])['jefe'];
        $datos['canton_id'] = $request->safe()->only(['canton'])['canton'];
        $datos['grupo_id'] = $request->safe()->only(['grupo'])['grupo'];
        $datos['cargo_id'] = $request->safe()->only(['cargo'])['cargo'];
        $datos['departamento_id'] = $request->safe()->only(['departamento'])['departamento'];
        /* $datos['fecha_salida'] =  $datos['fecha_salida'] ? $request->safe()->only(['fecha_salida'])['fecha_salida'] : null;//$request->safe()->only(['departamento'])['departamento'];
        $datos['fecha_nacimiento'] =new DateTime($datos['fecha_nacimiento']);*/

        if ($datos['foto_url']) {
            $datos['foto_url'] = (new GuardarImagenIndividual($datos['foto_url'], RutasStorage::FOTOS_PERFILES))->execute();
        }
        if ($datos['firma_url']) {
            $datos['firma_url'] = (new GuardarImagenIndividual($datos['firma_url'], RutasStorage::FIRMAS))->execute();
        }

        try {
            DB::beginTransaction();
            $username = $this->generarNombreUsuario($datos);
//            $email = $username . '@' . explode("@", $datos['email'])[1];
            $user = User::create([
                'name' => $username,
                'email' => $datos['email'],
                'password' => bcrypt($datos['password']),
            ])->assignRole($datos['roles']);
            //Adaptar datos
            $datos['usuario_id'] = $user->id;
            $datos['fecha_vinculacion'] = $datos['fecha_ingreso'];
            $datos['fecha_nacimiento'] = new DateTime($datos['fecha_nacimiento']);
            $datos['fecha_salida'] = $datos['fecha_salida'] ?: null;

            //Crear empleado
            $empleado = $user->empleado()->create($datos);
            if (array_key_exists('discapacidades', $datos)) $this->polymorphicGenericService->actualizarDiscapacidades($user, $datos['discapacidades']);
            //Si hay datos en $request->conductor se crea un conductor asociado al empleado recién creado
            if (!empty($request->conductor)) {
                $datos_conductor = $request->conductor;
                $datos_conductor['empleado_id'] = $empleado->id;
                $datos_conductor['tipo_licencia'] = Utils::convertArrayToString($request->conductor['tipo_licencia']);
                /* $conductor = */
                Conductor::create($datos_conductor);
            }


            //$esResponsableGrupo = $request->safe()->only(['es_responsable_grupo'])['es_responsable_grupo'];
            //$grupo = Grupo::find($datos['grupo_id']);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages(['error' => [Utils::obtenerMensajeError($e)]]);
        }

        $modelo = new EmpleadoResource($user->empleado);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    public function datos_empleado($id)
    {
        $empleado = Empleado::find($id);
        return response()->json(compact('empleado'));
    }

    public function HabilitaEmpleado(Request $request)
    {
        $empleado = Empleado::find($request->id);
        $empleado->estado = $request->estado == 'true' ? 1 : 0;
        $empleado->save();
        EmpleadoService::eliminarUmbralFondosRotativos($empleado);
        $modelo = $empleado;
        return response()->json(compact('modelo'));
    }

    /**
     * @throws ValidationException
     */
//    public function existeResponsableGrupo(Request $request)
//    {
//        $request->validate([
//            'grupo_id' => 'required|numeric|integer',
//        ]);
//
//        $responsableActualGrupo = Empleado::where('grupo_id', $request['grupo_id'])->where('es_responsable_grupo', true)->first();
//
//        if ($responsableActualGrupo) {
//            throw ValidationException::withMessages([
//                'responsable_grupo' => ['Ya existe un empleado designado como responsable del grupo. ¿Desea reemplazarlo por el empleado actual?'],
//            ]);
//        }
//
//        return response()->isOk();
//    }

    /**
     * Consultar
     */
    public function show(Empleado $empleado)
    {
        $modelo = new EmpleadoResource($empleado);
        if($modelo->conductor) $modelo['conductor'] = new ConductorResource($modelo->conductor);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     * @throws Throwable
     */
    public function update(EmpleadoRequest $request, Empleado $empleado)
    {
        //Respuesta
        $datos = $request->validated();
        $datos['grupo_id'] = $request->safe()->only(['grupo'])['grupo'];
        $datos['cargo_id'] = $request->safe()->only(['cargo'])['cargo'];
        $datos['departamento_id'] = $request->safe()->only(['departamento'])['departamento'];
        $datos['jefe_id'] = $request->safe()->only(['jefe'])['jefe'];
        $datos['canton_id'] = $request->safe()->only(['canton'])['canton'];

        if ($datos['foto_url'] && Utils::esBase64($datos['foto_url'])) {
            $datos['foto_url'] = (new GuardarImagenIndividual($datos['foto_url'], RutasStorage::FOTOS_PERFILES))->execute();
        } else {
            unset($datos['foto_url']);
        }
        if ($datos['firma_url'] && Utils::esBase64($datos['firma_url'])) {
            $datos['firma_url'] = (new GuardarImagenIndividual($datos['firma_url'], RutasStorage::FIRMAS))->execute();
        } else {
            unset($datos['firma_url']);
        }

        $empleado->update($datos);
        $empleado->user->syncRoles($datos['roles']);
        if (array_key_exists('discapacidades', $datos)) $this->polymorphicGenericService->actualizarDiscapacidades(User::find($empleado->usuario_id), $datos['discapacidades']);
        if (array_key_exists('familiares', $datos)) $this->servicio->agregarFamiliares($empleado, $datos['familiares']);

        //Si hay datos en $request->conductor se crea un conductor asociado al empleado recién creado
        if (!empty($request->conductor)) {
            $datos_conductor = $request->conductor;
            $datos_conductor['empleado_id'] = $empleado->id;
            $datos_conductor['tipo_licencia'] = Utils::convertArrayToString($request->conductor['tipo_licencia']);
            //buscamos un conductor
            $conductor = Conductor::find($empleado->id);
            if ($conductor) {
                $conductor->update($datos_conductor);
                $datos['licencias'] = array_map(function ($licencia) use ($conductor) {
                    return [
                        'conductor_id' => $conductor->empleado_id,
                        'tipo_licencia' => $licencia['tipo_licencia'],
                        'inicio_vigencia' => $licencia['inicio_vigencia'],
                        'fin_vigencia' => $licencia['fin_vigencia'],
                    ];
                }, $request->conductor['licencias']);
                $tiposLicencias = array_column($datos['licencias'], 'tipo_licencia');
                Licencia::upsert(
                    $datos['licencias'],
                    uniqueBy: ['conductor_id', 'tipo_licencia'],
                    update: ['tipo_licencia', 'inicio_vigencia', 'fin_vigencia']
                );
                Licencia::eliminarObsoletos($conductor->empleado_id, $tiposLicencias);
            } else {
                $conductor = Conductor::create($datos_conductor);
                $datos['licencias'] = array_map(function ($licencia) use ($conductor) {
                    return [
                        'conductor_id' => $conductor->empleado_id,
                        'tipo_licencia' => $licencia['tipo_licencia'],
                        'inicio_vigencia' => $licencia['inicio_vigencia'],
                        'fin_vigencia' => $licencia['fin_vigencia'],
                    ];
                }, $request->conductor['licencias']);
                $tiposLicencias = array_column($datos['licencias'], 'tipo_licencia');
                Licencia::upsert(
                    $datos['licencias'],
                    uniqueBy: ['conductor_id', 'tipo_licencia'],
                    update: ['tipo_licencia', 'inicio_vigencia', 'fin_vigencia']
                );
                Licencia::eliminarObsoletos($conductor->empleado_id, $tiposLicencias);
            }
        } else {
            //Eliminamos el conductor
            $conductor = Conductor::find($empleado->id);
            if ($conductor) $conductor->delete();
        }

        if (!is_null($request->password)) {
            $empleado->user()->update([
                'password' => bcrypt($request->password),
            ]);
        }

        $empleado->user()->update([
            'name' => $request->usuario,
            'email' => $request->email,
        ]);

        // $empleado->user()->update(['status' => $request->estado === 'ACTIVO' ? true : false]);
        $modelo = new EmpleadoResource($empleado->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Eliminar
     */
    public function destroy(Empleado $empleado)
    {
        $empleado->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }

    /**
     * Listar a los tecnicos filtrados por id de grupo
     */
    public function obtenerTecnicos(Request $request)
    {
        $request->validate(['grupo' => 'required|numeric|integer']);
        $grupo = $request['grupo'];

        $results = $this->servicio->obtenerTecnicosPorGrupo($grupo);
        return response()->json(compact('results'));
    }


    /**
     * @throws Throwable
     * @throws ValidationException
     */
    public function designarLiderGrupo(Request $request, Empleado $empleado)
    {
        $request->validate([
            'grupo' => 'required|numeric|integer',
        ]);

        if ($empleado->user->hasRole(User::ROL_LIDER_DE_GRUPO) && $empleado->grupo_id == $request['grupo']) {
            throw ValidationException::withMessages([
                '403' => ['No puede designar a la misma persona'],
            ]);
        }

        if ($empleado->grupo_id != $request['grupo']) {
            throw ValidationException::withMessages([
                '403' => ['No se puede designar como líder porque no pertence al grupo seleccionado.'],
            ]);
        }

        DB::transaction(function () use ($request, $empleado) {
            // Buscar lider del grupo actual
            $empleadosGrupoActual = Empleado::where('grupo_id', $request['grupo'])->get();
            $liderActual = $empleadosGrupoActual->filter(fn($item) => $item->user->hasRole(User::ROL_LIDER_DE_GRUPO));

            if ($liderActual) $liderActual->first()->user->removeRole(User::ROL_LIDER_DE_GRUPO);
            // if ($liderActual[0]) $liderActual[0]->user->removeRole(User::ROL_LIDER_DE_GRUPO);

            $empleado->user->assignRole(User::ROL_LIDER_DE_GRUPO);
        });

        // Empleados
        /* $empleados = Grupo::find($request['grupo'])->empleados;
        $jefeActual = null;

        // Buscar lider de cuadrilla actual
        foreach ($empleados as $empleado) {
            $esTecnico = $empleado->user->hasRole(User::ROL_TECNICO);
            $empleado->cargo = $empleado;

            $es_lider = in_array(User::ROL_TECNICO_JEFE_CUADRILLA, $empleado->user->getRoleNames()->toArray()); //()->with(User::ROL_TECNICO_JEFE_CUADRILLA)->first();

            if ($es_lider) $jefeActual = $empleado->user;
        }

        $nuevoJefe = User::find(Empleado::find($request['nuevo_jefe'])->usuario_id);

        if (Empleado::find($request['nuevo_jefe'])->grupo_id !== $request['grupo']) {
            throw ValidationException::withMessages([
                'error_grupo' => ['Este empleado no pertenece al grupo seleccionado. Asígnelo y vuelva a intentar.'],
            ]);
        }

        // Validar que nuevo jefe no sea secretario
        if (in_array(User::ROL_TECNICO_SECRETARIO, $nuevoJefe->getRoleNames()->toArray())) {
            throw ValidationException::withMessages([
                'secretario' => ['Este empleado tiene rol de Secretario de cuadrilla. Cámbielo y vuelva a intentar.'],
            ]);
        } */

        // Quitar rol de jefe al actual
        /*$nuevosRolesAnteriorJefe = $jefeActual->getRoleNames()->filter(fn ($item) => $item !== User::ROL_LIDER_DE_GRUPO);
        $jefeActual->syncRoles($nuevosRolesAnteriorJefe);

        // Agregar rol de jefe a nuevo jefed
        $nuevosRolesNuevoJefe = $nuevoJefe->getRoleNames()->push(User::ROL_LIDER_DE_GRUPO);
        $nuevoJefe->syncRoles($nuevosRolesNuevoJefe);*/

        $modelo = new EmpleadoResource($empleado->refresh());
        $mensaje = 'Nuevo líder de grupo designado exitosamente!';
        return response()->json(compact('mensaje', 'modelo'));
    }

//    public function designarSecretarioGrupo(Request $request)
//    {
//        /* $request->validate([
//            'grupo' => 'required|numeric|integer',
//            'nuevo_jefe' => 'required|numeric|integer',
//        ]);
//
//        // Empleados
//        $empleados = Grupo::find($request['grupo'])->empleados;
//        $secretarioActual = null;
//
//        // Buscar secretario actual
//        foreach ($empleados as $empleado) {
//            $es_secretario = in_array(User::ROL_TECNICO_SECRETARIO, $empleado->user->getRoleNames()->toArray()); //()->with(User::ROL_TECNICO_JEFE_CUADRILLA)->first();
//            if ($es_secretario) $secretarioActual = $empleado->user;
//        }
//
//        $nuevoJefe = User::find(Empleado::find($request['nuevo_jefe'])->usuario_id);
//
//        Log::channel('testing')->info('Log', ['Grupo empleado ', Empleado::find($request['nuevo_jefe'])->grupo_id]);
//        Log::channel('testing')->info('Log', ['Grupo asignado ', $request['grupo']]);
//
//        // Validar que el empleado pertenezca al grupo seleccionado
//        if (Empleado::find($request['nuevo_jefe'])->grupo_id !== $request['grupo']) {
//            throw ValidationException::withMessages([
//                'error_grupo' => ['Este empleado no pertenece al grupo seleccionado. Asígnelo y vuelva a intentar.'],
//            ]);
//        }
//
//        // Validar que nuevo jefe no sea secretario
//        if (in_array(User::ROL_TECNICO_JEFE_CUADRILLA, $nuevoJefe->getRoleNames()->toArray())) {
//            throw ValidationException::withMessages([
//                'jefe_cuadrilla' => ['Este empleado tiene rol de Jefe de cuadrilla. Cámbielo y vuelva a intentar.'],
//            ]);
//        }
//
//        // Quitar rol de secretario al actual
//        $nuevosRolesAnteriorJefe = $secretarioActual->getRoleNames()->filter(fn ($item) => $item !== User::ROL_TECNICO_SECRETARIO);
//        $secretarioActual->syncRoles($nuevosRolesAnteriorJefe);
//
//        // Agregar rol de secretario a nuevo secretario
//        $nuevosRolesNuevoJefe = $nuevoJefe->getRoleNames()->push(User::ROL_TECNICO_SECRETARIO);
//        $nuevoJefe->syncRoles($nuevosRolesNuevoJefe);
//
//        return response()->json(['mensaje' => 'Nuevo secretario asignado exitosamente!']); */
//    }

    /**
     * Obtiene los empleados que tengan los roles enviados en la `$request`. Enviar `$request->excluir:true` para devolver los empleados que no tienen el/los role/s enviados
     * @param Request $request
     * @return JsonResponse
     */
    public function empleadosRoles(Request $request)
    {
        $results = [];
        if (!is_null($request->roles)) {
            $roles = explode(',', $request->roles);
            if ($request->excluir) { // devuelve los usuarios que no tienen los roles proporcionados en $request->roles
//                $roles_filtrados =Role::whereNotIn('name', [User::ROL_TECNICO, User::ROL_LIDER_DE_GRUPO, User::ROL_EMPLEADO])->get();
                $roles_filtrados = Role::whereNotIn('name', $roles)->get();
                $results = EmpleadoRolePermisoResource::collection(User::role($roles_filtrados)->with('empleado')->whereHas('empleado', function ($query) {
                    $query->where('estado', true);
                })->get());
            } else
                $results = EmpleadoRolePermisoResource::collection(User::role($roles)->with('empleado')->whereHas('empleado', function ($query) {
                    $query->where('estado', true);
                })->get());
        }
        return response()->json(compact('results'));
    }

    public function empleadoPermisos(Request $request)
    {
        $results = [];
        if (!is_null($request->permisos)) {
            $permisos = explode(',', $request->permisos);
            $permisos_consultados = Permission::whereIn('name', $permisos)->get();
            $results = EmpleadoRolePermisoResource::collection(User::permission($permisos_consultados)->with('empleado')->get());
        }
        return response()->json(compact('results'));
    }

    /**
     * @throws Exception
     */
    public function imprimir_reporte_general_empleado()
    {
        $reportes = Empleado::where('estado', 1)
            ->where('id', '>', 2)
            ->where('esta_en_rol_pago', '1')
            ->where('realiza_factura', '0')
            ->where('salario', '!=', 0)
            ->orderBy('area_id', 'asc')
            ->orderBy('apellidos', 'asc')
            ->get();
        $results = Empleado::empaquetarListado($reportes);
        $nombre_reporte = 'lista_empleados';
        $vista = 'recursos-humanos.empleados';
        return $this->reporteService->imprimirReporte('pdf', 'A4', 'landscape', compact('results'), $nombre_reporte, $vista);
    }

    /**
     * La función genera un nombre de usuario único basado en el nombre de pila y verifica si ya existe
     * en la base de datos.
     *
     * @param array $request El parámetro  es una instancia de la clase Request, que se utiliza
     * para recuperar datos de la solicitud HTTP. Contiene información como los campos de entrada
     * enviados en un formulario o los parámetros pasados en la URL.
     *
     * @return string el nombre de usuario generado.
     */
    function generarNombreUsuario(array $request)
    {
        $nombreUsuario = $request['usuario'];
        $nombres = str_replace(['ñ', 'Ñ'], ['n', 'N'], $request['nombres']);
        $apellidos = str_replace(['ñ', 'Ñ'], ['n', 'N'], $request['apellidos']);
        // Comprobamos si el nombre de usuario ya existe
        $query = User::where('name', $nombreUsuario)->get();
        $username = $nombreUsuario;
        if ($query->count() > 0 && (!$request['sobreescribir'])){
            // Separamos el nombre y el apellido en dos cadenas
            $nombre = explode(" ", $nombres);
            // ['primer', 'segundo']
            $apellido = explode(" ", $apellidos);
            $inicio_username = $nombre[1][0];
            $username = $nombre[0][0] . $inicio_username . $apellido[0];
            $contador = 1;
            while (User::where('name', $username)->exists()) {
                if ($contador <= strlen($nombre[0])) {
                    $inicio_username .= $nombre[0][$contador];
                    $username = $inicio_username . $nombre[1][0] . $apellido[0];
                    $contador++;
                }
            }
        }
        return  str_replace(['ñ', 'Ñ'], ['n', 'N'],  $username); // Se usa para reemplazar las eñes en mayusculas y en minusculas
    }

    function obtenerNombreUsuario(Request $request)
    {
        $datos = $request->validate([
            'nombres' => 'required|string',
            'apellidos' => 'required|string',
            'usuario' => 'required|string',
            'sobreescribir'=>'boolean']);
        $username = $this->generarNombreUsuario($datos);
        return response()->json(compact('username'));
    }

    public function empleadosConSaldoFondosRotativos()
    {
//        $campos = request('campos') ? explode(',', request('campos')) : '*';
        $empleados = $this->servicio->obtenerEmpleadosConSaldoFondosRotativos();

        $results = EmpleadoResource::collection($empleados);
        return response()->json(compact('results'));
    }

    public function empleadosConOrdenes()
    {
        $campos = request('campos') ? explode(',', request('campos')) : '*';
        $empleados = Empleado::has('ordenesCompras')->ignoreRequest(['campos'])->filter()->get($campos);

        $results = EmpleadoResource::collection($empleados);
        return response()->json(compact('results'));
    }

    /**
     * @throws ValidationException
     */
    function obtenerEmpleadosFondosRotativos()
    {
        try {
            $empleados = Empleado::has('gastos')->get();

            $results = EmpleadoResource::collection($empleados);
        } catch (Throwable $th) {
            throw ValidationException::withMessages(['error' => Utils::obtenerMensajeError($th)]);
        }
        return response()->json(compact('results'));
    }

    /**
     * Listar archivos
     */
    public function indexFiles(Empleado $empleado)
    {
        try {
            $results = $this->archivoService->listarArchivos($empleado);

            return response()->json(compact('results'));
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
            return response()->json(compact('mensaje'), 500);
        }
    }

    /**
     * Guardar archivos
     */
    public function storeFiles(Request $request, Empleado $empleado)
    {
        try {
            $modelo = $this->archivoService->guardarArchivo($empleado, $request->file, RutasStorage::DOCUMENTOS_DIGITALIZADOS_EMPLEADOS->value . $empleado->identificacion);
            $mensaje = 'Archivo subido correctamente';
        } catch (Throwable $th) {
            $mensaje = $th->getMessage() . '. ' . $th->getLine();
            Log::channel('testing')->info('Log', ['Error en el storeFiles de EmpleadoController', $th->getMessage(), $th->getCode(), $th->getLine()]);
            return response()->json(compact('mensaje'), 500);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }
}
