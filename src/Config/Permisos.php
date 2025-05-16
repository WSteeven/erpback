<?php

namespace Src\Config;

// enum Permisos: string{

//     case AUTORIZAR = 'puede.autorizar.';
//     case ACCEDER = 'puede.acceder.'; // Formulario
//     case VER = 'puede.ver.'; // Consultar index y show
//     case CREAR = 'puede.crear.';
//     case EDITAR = 'puede.editar.';
//     case ELIMINAR = 'puede.eliminar.';
//     case RECHAZAR = 'puede.rechazar.';
// }
class Permisos
{
    const AUTORIZAR = 'puede.autorizar.';
    const ACCEDER = 'puede.acceder.'; // Formulario
    const VER = 'puede.ver.'; // Consultar index y show
    const BOTON = 'puede.ver.btn.'; // + accion + formulario, ejm ( puede.ver.btn.modificar_stock.materiales_empleados )

    const CREAR = 'puede.crear.';
    const EDITAR = 'puede.editar.';
    const ELIMINAR = 'puede.eliminar.';
    const RECHAZAR = 'puede.rechazar.';
    const ELABORAR = 'puede.elaborar.';
    const BUSCAR = 'puede.buscar.';
    const ANULAR = 'puede.anular.';
    const GESTIONAR = 'puede.gestionar.';
    const CONFIRMAR = 'puede.confirmar.';
}
