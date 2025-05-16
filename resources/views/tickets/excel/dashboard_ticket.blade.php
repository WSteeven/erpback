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
                <th rowspan="1" valign="center" bgcolor="#99b4ff">{{ 'CÓDIGO DEL TICKET' }}</th>
                <th rowspan="1" valign="center" bgcolor="#99b4ff">{{ 'TIPO' }}</th>
                <th rowspan="1" valign="center" bgcolor="#99b4ff">{{ 'ASUNTO' }}</th>
                <th rowspan="1" valign="center" bgcolor="#99b4ff">{{ 'SOLICITANTE' }}</th>
                <th rowspan="1" valign="center" bgcolor="#99b4ff">{{ 'RESPONSABLE' }}</th>
                <th rowspan="1" valign="center" bgcolor="#99b4ff">{{ 'ESTADO' }}</th>
                <th rowspan="1" valign="center" bgcolor="#99b4ff">{{ 'FECHA Y HORA SOLICITUD' }}</th>
                <th rowspan="1" valign="center" bgcolor="#99b4ff">{{ 'FECHA Y HORA LÍMITE' }}</th>
                <th rowspan="1" valign="center" bgcolor="#99b4ff">{{ 'FECHA HORA EJECUCIÓN' }}</th>
                <th rowspan="1" valign="center" bgcolor="#99b4ff">{{ 'FECHA HORA FINALIZADO' }}</th>
                <th rowspan="1" valign="center" bgcolor="#99b4ff">{{ 'TIEMPO HASTA FINALIZAR' }}</th>
                <th rowspan="1" valign="center" bgcolor="#99b4ff">{{ 'TIEMPO HASTA FINALIZAR (H:m:s)' }}</th>
                <th rowspan="1" valign="center" bgcolor="#99b4ff">{{ 'TIEMPO OCUPADO PAUSAS' }}</th>
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
                    <td valign="center">{{ \Carbon\Carbon::parse($ticket['created_at'])->format('Y-m-d H:i:s') }}</td>
                    <td valign="center">
                        {{ \Carbon\Carbon::parse($ticket['fecha_hora_limite'])->format('Y-m-d H:i:s') }}</td>
                    <td valign="center">{{ $ticket['primera_fecha_hora_ejecucion'] }}</td>
                    <td valign="center">{{ $ticket['fecha_hora_finalizado'] }}</td>
                    <td valign="center">{{ $ticket['tiempo_hasta_finalizar'] }}</td>
                    <td valign="center">{{ $ticket['tiempo_hasta_finalizar_h_m_s'] }}</td>
                    <td valign="center">{{ $ticket['tiempo_ocupado_pausas'] }}</td>
                </tr>
            @endforeach
            <tr></tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td align="right" style="background-color: #99b4ff; font-weight: bold; border: 1px solid #000;">Suma tiempo ocupado finalizar: </td>
                <td align="center" style="font-weight: bold; border: 1px solid #000;">{{ $sumaTiempoOcupado }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
</body>

</html>
