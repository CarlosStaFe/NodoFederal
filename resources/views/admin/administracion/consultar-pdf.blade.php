<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Consulta de Consumos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 20px;
        }
        .header {
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            position: relative;
        }
        .header img {
            max-height: 60px;
            margin-bottom: 10px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .header-content {
            display: table;
            width: 100%;
            margin-top: 10px;
        }
        .header-left {
            display: table-cell;
            vertical-align: middle;
            width: 30%;
            text-align: left;
        }
        .header-center {
            display: table-cell;
            vertical-align: middle;
            width: 40%;
            text-align: center;
        }
        .header-right {
            display: table-cell;
            vertical-align: middle;
            width: 30%;
            text-align: right;
        }
        .nodo-info {
            font-size: 12px;
            font-weight: bold;
            color: #0e6ba9;
            margin: 0;
        }
        .header h1 {
            margin: 0;
            color: #333;
            font-size: 16px;
        }
        .header h2 {
            margin: 5px 0;
            color: #666;
            font-size: 12px;
        }
        .filtros {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            border: 1px solid #dee2e6;
        }
        .filtros h3 {
            margin: 0 0 8px 0;
            font-size: 11px;
            color: #333;
        }
        .filtro-item {
            display: inline-block;
            margin-right: 15px;
            margin-bottom: 5px;
        }
        .filtro-label {
            font-weight: bold;
            color: #495057;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }
        th {
            background-color: #0e6ba9;
            color: white;
            padding: 6px;
            text-align: center;
            border: 1px solid #ccc;
            font-size: 9px;
            font-weight: bold;
        }
        td {
            padding: 4px;
            border: 1px solid #ccc;
            text-align: center;
            vertical-align: middle;
        }
        .subtotal-nodo {
            background-color: #d4edda;
            font-weight: bold;
            color: #155724;
        }
        .subtotal-socio {
            background-color: #fff3cd;
            font-weight: bold;
            color: #856404;
        }
        .total-general {
            background-color: #cce5ff;
            font-weight: bold;
            color: #004085;
            font-size: 10px;
        }
        .fecha {
            text-align: center;
        }
        .numero, .cuit {
            text-align: center;
            font-family: monospace;
        }
        .apellido, .nodo, .socio {
            text-align: left;
            padding-left: 6px;
        }
        .footer {
            position: fixed;
            bottom: 20px;
            width: 100%;
            height: 25px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <img src="{{ public_path('assets/img/NF-LOGO.jpg') }}" alt="Logo Nodo Federal">
            <div class="header-center">
                <h1>REPORTE DE CONSULTA DE CONSUMOS</h1>
                <h2>Sistema Nodo Federal</h2>
            </div>
            <div class="header-right">
                <p style="margin: 0; color: #666; font-size: 10px;">
                    Generado el {{ date('d/m/Y H:i:s') }}<br>
                    por {{ auth()->user()->name }}
                </p>
            </div>
        </div>
    </div>

    <div class="filtros">
        <h3>Filtros Aplicados:</h3>
        <div class="filtro-item">
            <span class="filtro-label">Nodo:</span> {{ $filtros['nodo'] }}
        </div>
        <div class="filtro-item">
            <span class="filtro-label">Socio:</span> {{ $filtros['socio'] }}
        </div>
        <div class="filtro-item">
            <span class="filtro-label">Desde:</span> {{ $filtros['desde_fecha'] }}
        </div>
        <div class="filtro-item">
            <span class="filtro-label">Hasta:</span> {{ $filtros['hasta_fecha'] }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 8%;">NRO.</th>
                <th style="width: 10%;">FECHA</th>
                <th style="width: 7%;">HORA</th>
                <th style="width: 8%;">TIPO</th>
                <th style="width: 11%;">CUIT</th>
                <th style="width: 21%;">APELLIDO</th>
                <th style="width: 12%;">NODO</th>
                <th style="width: 14%;">SOCIO</th>
                <th style="width: 9%;">USUARIO</th>
            </tr>
        </thead>
        <tbody>
            @php
                $nodoActual = '';
                $socioActual = '';
                $contadorNodo = 0;
                $contadorSocio = 0;
                $totalGeneral = $resultados->count();
            @endphp

            @foreach($resultados as $index => $item)
                @php
                    $fechaObj = $item->fecha ? new DateTime($item->fecha) : null;
                    $fecha = $fechaObj ? $fechaObj->format('d/m/Y') : '';
                    $hora = $fechaObj ? $fechaObj->format('H:i') : '';
                    $nombreNodo = $item->nodo ? $item->nodo->nombre : '';
                    $nombreSocio = $item->socio ? $item->socio->razon_social : '';
                    $nombreUsuario = (isset($item->user) && $item->user) ? $item->user->name : '';
                @endphp

                {{-- Si cambió el nodo --}}
                @if($nodoActual !== '' && $nodoActual !== $nombreNodo)
                    {{-- Subtotal del socio anterior si existe --}}
                    @if($socioActual !== '')
                        <tr class="subtotal-socio">
                            <td colspan="6"></td>
                            <td style="text-align: right; font-weight: bold;">Subtotal {{ $socioActual }}:</td>
                            <td style="text-align: center; font-weight: bold;">{{ $contadorSocio }} consultas</td>
                            <td></td>
                        </tr>
                    @endif
                    {{-- Subtotal del nodo anterior --}}
                    <tr class="subtotal-nodo">
                        <td colspan="6"></td>
                        <td style="text-align: center; font-weight: bold;">Subtotal Nodo {{ $nodoActual }}:</td>
                        <td style="text-align: center; font-weight: bold;">{{ $contadorNodo }} consultas</td>
                    </tr>
                    @php
                        $contadorNodo = 0;
                        $contadorSocio = 0;
                        $socioActual = '';
                    @endphp
                {{-- Si cambió el socio pero no el nodo --}}
                @elseif($socioActual !== '' && $socioActual !== $nombreSocio)
                    <tr class="subtotal-socio">
                        <td colspan="6"></td>
                        <td style="text-align: right; font-weight: bold;">Subtotal {{ $socioActual }}:</td>
                        <td style="text-align: center; font-weight: bold;">{{ $contadorSocio }} consultas</td>
                        <td></td>
                    </tr>
                    @php $contadorSocio = 0; @endphp
                @endif

                {{-- Fila de datos --}}
                <tr>
                    <td class="numero">{{ $item->numero ?? '' }}</td>
                    <td class="fecha">{{ $fecha }}</td>
                    <td class="fecha">{{ $hora }}</td>
                    <td>{{ $item->tipo ?? '' }}</td>
                    <td class="cuit">{{ $item->cuit ?? '' }}</td>
                    <td class="apellido">{{ $item->apelynombres ?? '' }}</td>
                    <td class="nodo">{{ $nombreNodo }}</td>
                    <td class="socio">{{ $nombreSocio }}</td>
                    <td class="apellido">{{ $nombreUsuario }}</td>
                </tr>

                @php
                    $nodoActual = $nombreNodo;
                    $socioActual = $nombreSocio;
                    $contadorNodo++;
                    $contadorSocio++;
                @endphp

                {{-- Si es el último elemento, agregar subtotales finales --}}
                @if($loop->last)
                    {{-- Subtotal del último socio --}}
                    <tr class="subtotal-socio">
                        <td colspan="6"></td>
                        <td style="text-align: right; font-weight: bold;">Subtotal {{ $socioActual }}:</td>
                        <td style="text-align: center; font-weight: bold;">{{ $contadorSocio }} consultas</td>
                        <td></td>
                    </tr>
                    {{-- Subtotal del último nodo --}}
                    <tr class="subtotal-nodo">
                        <td colspan="7"></td>
                        <td style="text-align: center; font-weight: bold;">Subtotal Nodo {{ $nodoActual }}:</td>
                        <td style="text-align: center; font-weight: bold;">{{ $contadorNodo }} consultas</td>
                    </tr>
                @endif
            @endforeach

            {{-- Total general --}}
            @if($totalGeneral > 0)
                <tr class="total-general">
                    <td colspan="8" style="text-align: center; font-weight: bold; font-size: 11px;">
                        TOTAL GENERAL: {{ $totalGeneral }} consultas
                    </td>
                    <td></td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <script type="text/php">
            if (isset($pdf)) {
                $pdf->page_script('
                    $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                    $size = 8;
                    $pageText = "Página " . $PAGE_NUM . " de " . $PAGE_COUNT;
                    $y = $pdf->get_height() - 30;
                    $x = $pdf->get_width() / 2 - 50;
                    $pdf->text($x, $y, $pageText, $font, $size);
                    
                    $footerText = "Sistema Nodo Federal - " . date("Y");
                    $x2 = $pdf->get_width() / 2 - 60;
                    $y2 = $y + 10;
                    $pdf->text($x2, $y2, $footerText, $font, $size);
                ');
            }
        </script>
    </div>
</body>
</html>