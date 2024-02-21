<?php

namespace Src\Config;

//Seleccionar el tipo de estado según la tabla estados_transacciones_bodega
enum EstadosTransacciones: int
{
  case PENDIENTE = 1;
  case COMPLETA = 2;
  case PARCIAL = 3;
  case ANULADA = 4;
}
