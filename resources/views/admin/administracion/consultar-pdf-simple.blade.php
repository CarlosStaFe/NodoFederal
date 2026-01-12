<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Consulta de Consumos</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Reporte de Consulta de Consumos</h1>
    
    <h3>Filtros Aplicados:</h3>
    <p><strong>Nodo:</strong> {{ $filtros['nodo'] }}</p>
    <p><strong>Socio:</strong> {{ $filtros['socio'] }}</p>
    <p><strong>Desde:</strong> {{ $filtros['desde_fecha'] }}</p>
    <p><strong>Hasta:</strong> {{ $filtros['hasta_fecha'] }}</p>
    
    <table>
        <thead>
            <tr>
                <th>NRO.</th>
                <th>FECHA</th>
                <th>HORA</th>
                <th>TIPO</th>
                <th>CUIT</th>
                <th>APELLIDO</th>
                <th>NODO</th>
                <th>SOCIO</th>
            </tr>
        </thead>
        <tbody>
            @foreach($resultados as $item)
                @php
                    $fechaObj = $item->fecha ? new DateTime($item->fecha) : null;
                    $fecha = $fechaObj ? $fechaObj->format('d/m/Y') : '';
                    $hora = $fechaObj ? $fechaObj->format('H:i') : '';
                @endphp
                <tr>
                    <td>{{ $item->numero ?? '' }}</td>
                    <td>{{ $fecha }}</td>
                    <td>{{ $hora }}</td>
                    <td>{{ $item->tipo ?? '' }}</td>
                    <td>{{ $item->cuit ?? '' }}</td>
                    <td>{{ $item->apelynombres ?? '' }}</td>
                    <td>{{ $item->nodo ? $item->nodo->nombre : '' }}</td>
                    <td>{{ $item->socio ? $item->socio->razon_social : '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <p><strong>Total: {{ $resultados->count() }} consultas</strong></p>
</body>
</html>