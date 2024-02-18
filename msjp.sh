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
php artisan make:controller "$nmodule/$component"Controller --api
php artisan make:request "$nmodule/$component"Request
php artisan make:resource "$nmodule/$component"Resource

#crear permisos
archivo="./app/Http/Controllers/${nmodule}/${component}Controller.php"
linea=10
texto_a_insertar="
 public function __construct()
    {
        \$this->middleware('can:puede.ver.${component}s')->only('index', 'show');
        \$this->middleware('can:puede.crear.${component}s')->only('store');
        \$this->middleware('can:puede.editar.${component}s')->only('update');
        \$this->middleware('can:puede.eliminar.${component}s')->only('destroy');
    }"

# Crear un archivo temporal
tempfile=$(mktemp)

# Copiar las primeras 14 líneas del archivo original al archivo temporal
head -n $linea "$archivo" > "$tempfile"

# Insertar el nuevo texto en la línea deseada
echo "$texto_a_insertar" >> "$tempfile"

# Copiar las líneas restantes del archivo original al archivo temporal
tail -n +$((linea + 1)) "$archivo" >> "$tempfile"

# Reemplazar el archivo original con el archivo temporal
mv "$tempfile" "$archivo"

