<?php

namespace Src\Config;

enum PusherEvents: string
{
    // Modulo medico
    case SOLICITUD_EXAMEN = 'solicitud-examen';
    case CAMBIO_FECHA_HORA_SOLICITUD_EXAMEN = 'cambio-fecha-hora-solicitud-examen';
    case DIAS_DESCANSO = 'dias-descanso';
}
