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
	case TRABAJOS = 'public/trabajos';
	case REGISTROS_TENDIDOS = 'public/registrosTendidos';
    case COMPROBANTES_GASTOS = 'public/comprobantesViaticos';
}
