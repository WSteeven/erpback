<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>

<body>
    <table
        style="color:#000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;margin-top: 20px;">
        <thead>
            <tr>
                <th rowspan="1" valign="center" bgcolor="#daf1f3">{{ 'CÓDIGO DEL TICKET' }}</th>
                <th rowspan="1" valign="center" bgcolor="#daf1f3">{{ 'TIPO' }}</th>
                <th rowspan="1" valign="center" bgcolor="#daf1f3">{{ 'ASUNTO' }}</th>
                <th rowspan="1" valign="center" bgcolor="#daf1f3">{{ 'SOLICITANTE' }}</th>
                <th rowspan="1" valign="center" bgcolor="#daf1f3">{{ 'RESPONSABLE' }}</th>
                <th rowspan="1" valign="center" bgcolor="#daf1f3">{{ 'ESTADO' }}</th>
                <th rowspan="1" valign="center" bgcolor="#daf1f3">{{ 'FECHA Y HORA SOLICITUD' }}</th>
                <th rowspan="1" valign="center" bgcolor="#daf1f3">{{ 'FECHA Y HORA LÍMITE' }}</th>
                <th rowspan="1" valign="center" bgcolor="#daf1f3">{{ 'FECHA HORA EJECUCIÓN' }}</th>
                <th rowspan="1" valign="center" bgcolor="#daf1f3">{{ 'FECHA HORA FINALIZADO' }}</th>
                <th rowspan="1" valign="center" bgcolor="#daf1f3">{{ 'TIEMPO HASTA FINALIZAR' }}</th>
                <th rowspan="1" valign="center" bgcolor="#daf1f3">{{ 'TIEMPO HASTA FINALIZAR (H:m:s)' }}</th>
                <th rowspan="1" valign="center" bgcolor="#daf1f3">{{ 'TIEMPO OCUPADO PAUSAS' }}</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($reporte as $ticket)
                <tr>
                    <td valign="center">{{ $ticket['codigo'] }}</td>
                    <td valign="center">{{ $ticket['tipo_ticket'] }}</td>
                    <td valign="center">{{ $ticket['asunto'] }}</td>
                    <td valign="center">{{ $ticket['solicitante'] }}</td>
                    <td valign="center">{{ $ticket['responsable'] }}</td>
                    <td valign="center">{{ $ticket['estado'] }}</td>
                    <td valign="center">{{ \Carbon\Carbon::parse($ticket['fecha_hora_limite'])->format('Y-m-d H:i:s') }}</td>
                    <td valign="center">{{ \Carbon\Carbon::parse($ticket['created_at'])->format('Y-m-d H:i:s') }}</td>
                    <td valign="center">{{ $ticket['primera_fecha_hora_ejecucion'] }}</td>
                    <td valign="center">{{ $ticket['fecha_hora_finalizado'] }}</td>
                    <td valign="center">{{ $ticket['tiempo_hasta_finalizar'] }}</td>
                    <td valign="center">{{ $ticket['tiempo_hasta_finalizar_h_m_s'] }}</td>
                    <td valign="center">{{ $ticket['tiempo_ocupado_pausas'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
