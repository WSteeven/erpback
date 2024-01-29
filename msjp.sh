#!/bin/bash

# Clear the screen for a cleaner output
clear

echo "Creador de estructura de componente de modulo en Laravel"

# Initialize variables
nmodule=""
prefix=""
component=""

# Iterate through command-line arguments
while [[ $# -gt 0 ]]; do
  arg="$1"

  # Remove leading dashes from argument names
  arg="${arg#-}"

  case $arg in
    nmodule)
      nmodule="$2"
      shift
      ;;
    prefix)
      prefix="$2"
      shift
      ;;
    component)
      component="$2"
      shift
      ;;
    *)
      echo "Invalid argument: $arg"
      exit 1
      ;;
  esac

  shift
done

# Check for missing arguments
if [[ -z "$nmodule" ]]; then
  echo "Falta el argumento -nmodule."
  exit 1
fi

if [[ -z "$prefix" ]]; then
  echo "Falta el argumento -prefix."
  exit 1
fi

if [[ -z "$component" ]]; then
  echo "Falta el argumento -component."
  exit 1
fi

# Execute Laravel commands
echo "Ejecutando comandos porfavor espere ..."
migration_name="create_${prefix}${component}s_table"
php artisan make:model  "$nmodule/$component"
php artisan make:migration "$migration_name"
php artisan make:controller "$nmodule/$component"Controller
php artisan make:request "$nmodule/$component"Request
php artisan make:resource "$nmodule/$component"Resource
