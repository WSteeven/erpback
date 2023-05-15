<?php

namespace Src\Config;

enum MotivosTransaccionesBodega: string
{
    case venta = 'VENTA';
    case compraProveedor = 'COMPRA A PROVEEDOR';
    case mercaderiaClienteTarea = 'MERCADERIA DE CLIENTE PARA TAREA';
    case devolucionFinalizacionLaboral = 'DEVOLUCION POR FINALIZACION LABORAL';
    case devolucionTarea = 'DEVOLUCION DE TAREA';
    case stockInicial = 'STOCK INICIAL';
    case despachoTarea = 'DESPACHO DE TAREA';
    case despacho = 'DESPACHO';
    case devolucionAlProveedor = 'DEVOLUCION AL PROVEEDOR';
    case reposicion = 'REPOSICION';
    case ingresoTransferenciaBodegas = 'INGRESO TRANSFERENCIA ENTRE BODEGAS';
    case egresoTransferenciaBodegas = 'EGRESO TRANSFERENCIA ENTRE BODEGAS';
    case ingresoLiquidacionMateriales = 'INGRESO POR LIQUIDACION DE MATERIALES';
    case egresoLiquidacionMateriales = 'EGRESO POR LIQUIDACION DE MATERIALES';
    case ingresoAjusteRegularizacion = 'AJUSTE DE INGRESO POR REGULARIZACION';
    case egresoAjusteRegularizacion = 'AJUSTE DE EGRESO POR REGULARIZACION';
    case mercaderiaClienteStock = 'MERCADERIA DE CLIENTE PARA STOCK';
    case devolucionGarantia = 'DEVOLUCION POR GARANTIA';
    case devolucionDanio = 'DEVOLUCION POR DAÑO';
    case despachoGarantia = 'DESPACHO POR GARANTIA';
}
