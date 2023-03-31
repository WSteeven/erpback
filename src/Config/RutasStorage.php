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
	case SUBTAREAS = 'public/subtareas';
	case REGISTROS_TENDIDOS = 'public/registrosTendidos';
	case FOTOS_PERFILES = 'public/fotosPerfiles';
	case FIRMAS = 'public/firmas';
	case TRABAJOS = 'public/trabajos';
    case COMPROBANTES_GASTOS = 'public/comprobantesViaticos';
    case TRANSFERENCIAS = 'public/transferencias';
    case TRANSFERENCIASALDO = 'public/transferenciasSaldo';
    case SEGUIMIENTO = 'public/seguimiento';
}
