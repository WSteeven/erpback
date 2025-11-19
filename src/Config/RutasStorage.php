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
    case IMAGENES_VALIJAS = 'public/imagenesValijas';
    case IMAGENES_PERMISOS_ARMAS = 'public/bodega/imagenesPermisosArmas';
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
    case ARCHIVOS_COMENTARIOS_TICKETS = 'public/tickets/comentarios';
    case ATRASOS = 'public/evidencias_atrasos_empleados';
    case FOTOGRAFIAS_ACTIVIDADES_REALIZADAS = 'public/fotografias_actividades_realizadas';
    case FOTOGRAFIAS_SEGUIMIENTOS_TICKETS = 'public/fotografias_seguimiento_tickets';
    case ARCHIVOS_SEGUIMIENTO_TICKETS = 'public/archivos_seguimiento_tickets';
    case FOTOGRAFIAS_PRESTAMO_EMPRESARIAL = 'public/fotografias_prestamo_empresarial';
    case DOCUMENTOS_PERMISO_EMPLEADO = 'public/documentos_permiso_empleado/';
    case DOCUMENTOS_LICENCIA_EMPLEADO = 'public/documentos_licencia_empleado';
    case DOCUMENTOS_ROL_EMPLEADO = 'roles_de_pago';
    case CALIFICACIONES_PROVEEDORES = 'public/proveedores/calificaciones_proveedores';
    case EMPRESAS = 'public/empresas/';
    case PROVEEDORES_INTERNACIONALES = 'public/ComprasProveedores/proveedores_internacionales/';
    case PROVEEDORES = 'public/proveedores/empresas/';
    case CONFIGURACION_GENERAL = 'public/configuracion_general';
    case PLANTILLAS_BASE= 'public/plantillas';
    case FOTOGRAFIAS_NOVEDADES_ORDENES_COMPRAS  = 'public/fotografias_novedades_ordenes_compras';


    /***********************
     * MODULO DE VENTAS CLARO
     ***********************/
    case VENTAS_CLARO = 'public/ventasClaro/';
    case FOTOGRAFIAS_NOVEDADES_VENTAS_CLARO  = 'public/ventasClaro/fotografias_novedades_ventas_claro';
    case NOVEDADES_VENTAS_CLARO  = 'public/ventasClaro/novedades_ventas_claro';
    case NOVEDADES_ORDENES_COMPRAS  = 'public/novedades_ordenes_compras';

    case FOTOGRAFIAS_CEDULAS_CLIENTES_CLARO_FRONTAL = 'public/VentasClaro/cedulas_clientes/frontal';
    case FOTOGRAFIAS_CEDULAS_CLIENTES_CLARO_POSTERIOR = 'public/VentasClaro/cedulas_clientes/posterior';

    /***********************
     * MODULO DE VEHICULOS
     ***********************/
    case VEHICULOS = 'public/vehiculos/';
    case PREINGRESOS  = 'public/preingresos/archivos';
    case FOTOGRAFIAS_ITEMS_PREINGRESOS  = 'public/fotografias_preingresos';
    case TRANSFERENCIAS_PRODUCTOS_EMPLEADOS  = 'public/transferencias_productos_empleados/archivos';
    case DOCUMENTOS_DIGITALIZADOS_EMPLEADOS = 'public/carpeta_digital_empleados/';
    case FOTOGRAFIAS_DIARIAS_VEHICULOS = 'public/vehiculos/fotografias_diarias';
    case EVIDENCIAS_INCIDENTES_VEHICULOS = 'public/vehiculos/evidencias_incidentes/';
    case EVIDENCIAS_ORDENES_REPARACIONES = 'public/vehiculos/evidencias_ordenes_reparaciones/';
    case EVIDENCIAS_TANQUEOS_COMBUSTIBLES = 'public/vehiculos/evidencias_tanqueo_combustible';
    case EVIDENCIAS_VEHICULOS_ASIGNADOS = 'public/vehiculos/evidencias_vehiculos_asignados/';
    case EVIDENCIAS_VEHICULOS_TRANSFERIDOS = 'public/vehiculos/evidencias_vehiculos_transferidos/';

/**********
     * Medico
     **********/
    case DETALLES_RESULTADOS_EXAMENES  = 'public/detalles_resultados_examenes/archivos';
    case ESQUEMAS_VACUNAS  = 'public/esquemas_vacunas/archivos';
    case SOLICITUD_EXAMEN  = 'public/solicitudes_examenes/archivos';
    case FICHAS_PREOCUPACIONALES  = 'public/fichas_preocupacionales/archivos';

    case FICHAS_APTITUD = 'public/fichas_aptitud/archivos';

/**
     * MODULO DE SELECCION Y CONTRATACION
     */
    case SOLICITUD_NUEVO_EMPLEADO = 'public/SeleccionContratacionPersonal/solicitudes_personal';
    case DOCUMENTOS_POSTULANTES = 'public/SeleccionContratacionPersonal/postulantes/';
    case CURRICULUM = 'public/SeleccionContratacionPersonal/curriculums/';
    case VACANTES_TRABAJO = 'public/SeleccionContratacionPersonal/vacantes';

/**
     * MODULO DE TRABAJO SOCIAL
     */
    case RUTAGRAMAS = 'public/TrabajoSocial/rutagramas';
    case CROQUIS = 'public/TrabajoSocial/croquis_empleados';
    case VISITAS_DOMICILIARIAS = 'public/TrabajoSocial/Visitas-Domiciliarias';
    case GENOGRAMAS = 'public/TrabajoSocial/genogramas';

/**********
     * Bodega
     **********/
    case FOTOGRAFIAS_DETALLE_PRODUCTO  = 'public/bodega/fotografiasDetalleProducto';
    case IMAGENES_NOTICIAS  = 'public/intranet/imagenesNoticias';

/****************
     * Activos Fijos
     ****************/
    case SEGUIMIENTO_CONSUMO_ACTIVOS_FIJOS  = 'public/activos_fijos/seguimientoConsumoActivosFijos';
    case ACTIVOS_FIJOS_ACTA_ENTREGA_RECEPCION  = 'public/activos_fijos/actaEntregaRecepcion';
    case ACTIVOS_FIJOS_JUSTIFICATIVO_USO  = 'public/activos_fijos/justificativoUso';

/*******
     * SSO
     *******/
    case INCIDENTES  = 'public/sso/incidentes';
    case SEGUIMIENTOS_INCIDENTES  = 'public/sso/seguimientos_incidentes';
    case SEGUIMIENTOS_ACCIDENTES  = 'public/sso/seguimientos_accidentes';
    case ACCIDENTES  = 'public/sso/accidentes';
    case SOLICITUDES_DESCUENTOS  = 'public/sso/solicitudes_descuentos';
    case CONSULTAS_MEDICAS  = 'public/sso/consultas_medicas';
    case INSPECCIONES  = 'public/sso/inspecciones';

    /*************
     * Seguridad
     *************/
    case ACTIVIDADES_BITACORAS  = 'public/seguridad/actividadesBitacoras';
    case ACTIVIDADES_BITACORAS_ARCHIVOS  = 'public/seguridad/actividadesBitacorasArchivos';
}
