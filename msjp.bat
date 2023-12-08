@echo off
setlocal enabledelayedexpansion
echo Creador de estructura de componente de modulo en Laravel
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

rem Ejecutamos comandos de laravel
echo Ejecutando comandos porfavor espere ...
set migration_name= create_%prefix%%component%_table
php artisan make:model  %nmodule%/%component%
php artisan make:migration %migration_name%
php artisan make:controller %nmodule%/%component%Controller
php artisan make:request %nmodule%/%component%Request
php artisan make:resource %nmodule%/%component%Resource

endlocal
