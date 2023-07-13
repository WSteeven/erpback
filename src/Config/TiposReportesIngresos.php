<?php

namespace Src\Config;

//Seleccionar el tipo de reporte de ingreso
enum TiposReportesIngresos: int
{
  case SOLICITANTE=0;
  case BODEGUERO=1;
  case RESPONSABLE=2;
  case PER_RETIRA=3;
  case MOTIVO=4;
  case PEDIDO=5;
  case BODEGA=6;
  case DEVOLUCION=7;
  case TAREA=8;
}
