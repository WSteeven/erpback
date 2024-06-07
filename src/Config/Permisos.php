<?php

namespace Src\Config;

enum Permisos: string{

    case AUTORIZAR = 'puede.autorizar.';
    case ACCEDER = 'puede.acceder.'; // Formulario
    case VER = 'puede.ver.'; // Consultar index y show
    case CREAR = 'puede.crear.';
    case EDITAR = 'puede.editar.';
    case ELIMINAR = 'puede.eliminar.';
    case RECHAZAR = 'puede.rechazar.';
}