<?php

namespace Src\Config;



enum RutasStorage: string
{
        // Private
    case GESTOR_ARCHIVOS = 'private/GestorArchivos';
    case COMPROBANTES = 'private/comprobantes';
        // Public
    case SERVICIOS = 'public/servicios';
    case POPUP = 'public/popup';
    case TAREAS = 'public/tareas';
    case SUBTAREAS = 'public/subtareas';
    case REGISTROS_TENDIDOS = 'public/registrosTendidos';
    case FOTOS_PERFILES = 'public/fotosPerfiles';
    case FIRMAS = 'public/firmas';
    case TRABAJOS = 'public/trabajos';
    case COMPROBANTES_GASTOS = 'public/comprobantesViaticos';
    case TRANSFERENCIAS = 'public/transferencias';
    case TRANSFERENCIASALDO = 'public/transferenciasSaldo';
    case SEGUIMIENTO = 'public/seguimiento'; // fotografias
    case ARCHIVOS_SEGUIMIENTO = 'public/archivos_seguimiento';
    case JUSTIFICACION_PERMISO_EMPLEADO  = 'public/justificacion_permiso_empleado';
    case DEVOLUCIONES = 'public/devoluciones/evidencias';
    case PEDIDOS = 'public/pedidos/evidencias';
    case CLIENTES = 'public/clientes/logos';
    case TICKETS = 'public/tickets';
    case FOTOGRAFIAS_SEGUIMIENTOS_TICKETS = 'public/fotografias_seguimiento_tickets';
    case ARCHIVOS_SEGUIMIENTO_TICKETS = 'public/archivos_seguimiento_tickets';
    case FOTOGRAFIAS_PRESTAMO_EMPRESARIAL = 'public/fotografias_prestamo_empresarial';
    case DOCUMENTOS_PERMISO_EMPLEADO = 'public/documentos_permiso_empleado';
    case DOCUMENTOS_LICENCIA_EMPLEADO = 'public/documentos_licencia_empleado';
    case DOCUMENTOS_ROL_EMPLEADO = 'roles_de_pago';
    case CALIFICACIONES_PROVEEDORES = 'public/proveedores/calificaciones_proveedores';
    case EMPRESAS = 'public/empresas/';
    case PROVEEDORES = 'public/proveedores/empresas/';
    case CONFIGURACION_GENERAL = 'public/configuracion_general';
    case FOTOGRAFIAS_NOVEDADES_ORDENES_COMPRAS  = 'public/fotografias_novedades_ordenes_compras';
    case NOVEDADES_ORDENES_COMPRAS  = 'public/novedades_ordenes_compras';

    /**
     * MODULO DE VEHICULOS
     */
    case VEHICULOS = 'public/vehiculos/';
}
