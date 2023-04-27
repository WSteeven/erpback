<?php

namespace Src\Config;

enum Endpoints: string
{
    case TAREAS = 'tareas';
    case SUBTAREAS = 'subtareas';
    case TRABAJO_ASIGNADO = 'trabajo-asignado';
}
