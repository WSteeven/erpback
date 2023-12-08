<?php

namespace Src\Config;

// Ayuda al frontend a seleccionar el icono de acuerdo al tipo
enum TiposNotificaciones: string{
    case INGRESO_MATERIALES = 'INGRESO DE MATERIALES';
    case PREINGRESO = 'PREINGRESO DE MATERIALES';
    case PEDIDO = 'PEDIDO';
    case DEVOLUCION = 'DEVOLUCION';
    case AUTORIZACION_GASTO = 'AUTORIZACION GASTO';
    case TAREA = 'TAREA';
    case SUBTAREA = 'SUBTAREA';
    case EGRESO = 'EGRESO';
    case TICKET = 'TICKET';
    case PERMISO_EMPLEADO = 'PERMISO EMPLEADO';
    case PRESTAMO_EMPRESARIAL = 'PRESTAMO EMPRESARIAL';
    case LICENCIA_EMPLEADO = 'LICENCIA EMPLEADO';
    case SOLICITUD_PRESTAMO_EMPRESARIAL = 'SOLICITUD PRESTAMO EMPRESARIAL';
    case VACACION = 'VACACION';
    //compras y proveedores
    case PREORDEN = 'PREORDEN';
    case ORDEN_COMPRA = 'ORDEN_COMPRA';
    case PROFORMA = 'PROFORMA';
    case PREFACTURA = 'PREFACTURA';
    case PROVEEDOR = 'PROVEEDOR';
}
