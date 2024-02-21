#!/bin/bash

echo "Creador de estructura de componente de módulo en Laravel"
nmodule=""
abrmodule=""
component=""

while [ "$1" != "" ]; do
    case $1 in
        -nmodule )
            shift
            nmodule=$1
            ;;
        -abrmodule )
            shift
            abrmodule=$1
            ;;
        -component )
            shift
            component=$1
            ;;
        * )
            echo "Argumento no reconocido: $1"
            exit 1
    esac
    shift
done

if [ -z "$nmodule" ] || [ -z "$abrmodule" ] || [ -z "$component" ]; then
    echo "Faltan argumentos. Uso: $0 -nmodule <nmodule> -abrmodule <abrmodule> -component <component>"
    exit 1
fi

echo "Ejecutando comandos, por favor espere..."
migration_name="create_${abrmodule}${component}_table"
php artisan make:model "$nmodule/"$component
php artisan make:migration "$migration_name"
php artisan make:controller "$nmodule/"$component"Controller"
php artisan make:request "$nmodule/"$component"Request"
php artisan make:resource "$nmodule/"$component"Resource"

