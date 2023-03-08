<?php

namespace Src\Config;

enum TiposNotificaciones: string{
    case PEDIDO = 'PEDIDO';
    case AUTORIZACION_GASTO = 'AUTORIZACION GASTO';
    case TAREA = 'TAREA';
    case SUBTAREA = 'SUBTAREA';
}