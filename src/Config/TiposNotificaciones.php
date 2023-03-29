<?php

namespace Src\Config;

// Ayuda al frontend a seleccionar el icono de acuerdo al tipo
enum TiposNotificaciones: string{
    case PEDIDO = 'PEDIDO';
    case AUTORIZACION_GASTO = 'AUTORIZACION GASTO';
    case TAREA = 'TAREA';
    case SUBTAREA = 'SUBTAREA';
    case EGRESO = 'EGRESO';
}
