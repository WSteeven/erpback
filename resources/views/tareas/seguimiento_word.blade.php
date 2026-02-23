<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
body { font-family: Arial; font-size: 12px; }
table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
td, th { border: 1px solid #000; padding: 5px; }
h1 { text-align: center; }
.page-break { page-break-after: always; }
img { max-width: 500px; display:block; margin:auto; }
</style>
</head>
<body>

<h1>INFORME TÉCNICO</h1>

<h3>Información General</h3>

<table>
@foreach($info_general as $label => $valor)
<tr>
<td><strong>{{ $label }}</strong></td>
<td>{{ $valor }}</td>
</tr>
@endforeach
</table>

<h3>Detalle de Equipos</h3>

<table>
<tr>
<th>Item</th>
<th>Descripción</th>
<th>Cantidad</th>
</tr>

@foreach($equipos as $equipo)
<tr>
<td>{{ $equipo['item'] }}</td>
<td>{{ $equipo['descripcion'] }}</td>
<td>{{ $equipo['cantidad'] }}</td>
</tr>
@endforeach
</table>

<div class="page-break"></div>

<h3>Informe Fotográfico</h3>

@foreach($imagenes as $imagen)
<p><strong>{{ $imagen['titulo'] }}</strong></p>
<img src="{{ public_path('storage/'.$imagen['ruta']) }}">
<br>
@endforeach

</body>
</html>
