@echo off
setlocal enabledelayedexpansion
echo Creador de estructura de componente de módulo en Laravel
rem Inicializamos las variables
set "nmodule="
set "prefix="
set "component="

rem Iteramos a través de los argumentos de línea de comandos
:loop
if "%~1"=="" goto :done

set "arg=%~1"
set "arg=!arg:-nmodule=!"
set "arg=!arg:-prefix=!"
set "arg=!arg:-component=!"

if "%~1" neq "!arg!" (
    if "!nmodule!"=="" (
        set "nmodule=%~2"
    ) else if "!prefix!"=="" (
        set "prefix=%~2"
    ) else (
        set "component=%~2"
    )
    shift
    shift
    goto :loop
) else (
    shift
    goto :loop
)

:done

rem Comprobamos si se proporcionaron los tres argumentos
if not defined nmodule (
    echo Falta el argumento -nmodule.
    exit /b 1
)

if not defined prefix (
    echo Falta el argumento -prefix.
    exit /b 1
)

if not defined component (
    echo Falta el argumento -component.
    exit /b 1
)

rem Ejecutamos comandos de Laravel
echo Ejecutando comandos, por favor espere ...
set "migration_name=create_%prefix%%component%s_table"
php artisan make:model %nmodule%/%component%
php artisan make:migration %migration_name%
php artisan make:controller %nmodule%/%component%Controller --api
php artisan make:request %nmodule%/%component%Request
php artisan make:resource %nmodule%/%component%Resource

echo Creando permisos...

set "archivo=./app/Http/Controllers/%nmodule%/%component%Controller.php"
set "linea=10"
set "texto_a_insertar=    public function __construct() {^
        $this->middleware('can:puede.ver.%component%')->only('index', 'show');^
        $this->middleware('can:puede.crear.%component%')->only('store');^
        $this->middleware('can:puede.editar.%component%')->only('update');^
        $this->middleware('can:puede.eliminar.%component%')->only('destroy');^
    }"

REM Crear un archivo temporal
set "tempfile=%temp%\tempfile_%random%.tmp"

REM Copiar las primeras 14 líneas del archivo original al archivo temporal
for /f "tokens=1,* delims=:" %%a in ('findstr /n "^" "%archivo%"') do (
    if %%a leq %linea% (
        echo %%b >> "%tempfile%"
    ) else (
        goto InsertText
    )
)

:InsertText
REM Insertar el nuevo texto en la línea deseada
echo %texto_a_insertar% >> "%tempfile%"

REM Copiar las líneas restantes del archivo original al archivo temporal
for /f "skip=%linea% tokens=*" %%a in ('type "%archivo%" ^| findstr /n "^"') do (
    echo %%b >> "%tempfile%"
)

REM Reemplazar el archivo original con el archivo temporal
move /y "%tempfile%" "%archivo%"

endlocal
