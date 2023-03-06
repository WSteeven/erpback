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
}
