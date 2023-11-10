<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte General de Ventas</title>
   </head>
<body>
<table>
    <tr>
        <td rowspan="3" colspan="2">
            <div >
                <img align="center" width="90" src="{{ public_path($config['logo_claro']) }}" border="0" />
            </div>
        </td>
        <td colspan="12" style="text-align: center; font-weight: bold; padding-bottom: 25%; padding-top: 25%;">INFORME GENERAL DE VENTAS DEL MES DE SEPTIEMBRE </td>
    </tr>
    <tr >
        <td style="font-weight: bold">JEFE DE VENTAS NACIONAL :</td>
        <td colspan="12">&nbsp;JUAN SALVADOR TAPIA SILVERS </td>
      </tr>
      <tr >
        <td style="font-weight: bold" >SUPERVISOR:</td>
        <td colspan="12">JUAN PABLO CALLE</td>
      </tr>
      <tr>
        <td style="font-weight: bold" >N <span style="color:#000000; font-family:'Calibri'; font-size:11pt">°</span></td>
        <td style="font-weight: bold"> CIUDAD</td>
        <td style="font-weight: bold"> VENDEDOR</td>
        <td style="font-weight: bold"> NOMBRE CLIENTE</td>
        <td style="font-weight: bold"> CODIGO DE ORDEN </td>
        <td style="font-weight: bold"> CEDULA O RUC</td>
        <td style="font-weight: bold"> VENTA</td>
        <td style="font-weight: bold"> FECHA DE INGRESO</td>
        <td style="font-weight: bold"> FECHA DE ACTIVACION</td>
        <td style="font-weight: bold"> PLAN DE INTERNET</td>
        <td style="font-weight: bold">FORMA PAGO</td>
        <td style="font-weight: bold">ORDEN INTERNA </td>
        <td style="font-weight: bold">VALOR SIN IVA </td>
        <td style="font-weight: bold" >OBSERVACIONES</td>
      </tr>

      @foreach($reportes as $key => $reporte)
        <tr >
            <td >{{ $reporte['item'] }}</td>
            <td >{{ $reporte['ciudad'] }}</td>
            <td >{{ $reporte['vendedor'] }}</td>
            <td ></td>
            <td >{{ $reporte['codigo_orden'] }}</td>
            <td ></td>
            <td >{{ $reporte['venta'] }}</td>
            <td >{{ $reporte['fecha_ingreso'] }}</td>
            <td >{{ $reporte['fecha_activ'] }}</td>
            <td >{{ $reporte['plan'] }} </td>
            <td >{{ $reporte['forma_pago'] }} </td>
            <td >{{ $reporte['orden_interna'] }}</td>
            <td >&nbsp;$ {{ $reporte['precio'] }} </td>
            <td >&nbsp;</td>
            <td >&nbsp;</td>

        </tr>
      @endforeach

</table>
</body>
</html>
