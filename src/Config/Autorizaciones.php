<?php

namespace Src\Config;

//Seleccionar el id de autorizacion según la tabla autorizaciones
enum Autorizaciones: int
{
  case PENDIENTE=1;
  case APROBADO=2;
  case CANCELADO=3;  
}
