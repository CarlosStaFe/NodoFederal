@extends('layouts.admin')

@section('content')

    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="card-title mb-0">Informe de Antecedentes</h1>
                    @if (isset($datos) && isset($datos['data']))
                        <a href="{{ url('admin/operaciones/pdf') }}" class="btn btn-success" target="_blank">
                            <i class="bi bi-printer-fill"></i> Generar PDF
                        </a>
                    @endif
                    <a href="{{ url('admin/operaciones/consultar') }}" class="btn btn-info">
                        <i class="bi bi-skip-backward-fill"></i> Otra Consulta
                    </a>
                    <a href="{{ url('admin') }}" class="btn btn-warning">
                        <i class="bi bi-clipboard-data"></i> Panel Principal
                    </a>
                </div>
            </div>
            <div class="card-body mt-0">
                @if (isset($datos))
                    @php $data = $datos['data'] ?? null; @endphp

                    <style>
                        .table thead th {
                            background-color: #37a395;
                            color: #fff;
                            font-weight: bold;
                        }

                        #datos-personales th {
                            background-color: #3750a3;
                            color: #fff;
                            font-weight: bold;
                        }

                        .section-title {
                            color: #0d6efd;
                            font-size: 1.3rem;
                            font-weight: bold;
                            margin-bottom: 0.5em;
                        }
                    </style>

                    @php $p = $data['datosParticulares'] ?? null; @endphp
                    @if ($p)
                        <form class="row g-3 mt-4">

                            <div class="col-12 mb-3">
                                @if (isset($data['idLog']))
                                    <div><strong>ID Log:</strong> {{ $data['idLog'] }}</div>
                                @endif
                            </div>

                            {{-- DATOS PERSONALES --}}
                            <table class="table table-bordered">
                                <div class="col-12 mt-3">
                                    <h3>Datos Personales</h3>
                                </div>
                                <tbody>
                                    <thead id="datos-personales">
                                        <tr>
                                            <th>Apellido y Nombres</th>
                                            <th>CUIL</th>
                                            <th>DNI</th>
                                            <th>Tipo</th>
                                            <th>Nacimiento</th>
                                            <th>Deceso</th>
                                            <th>Edad</th>
                                            <th>Sexo</th>
                                        </tr>
                                    </thead>
                                    <tr>
                                        <td>{{ $p['apellidoNombre'] ?? '' }}</td>
                                        <td>{{ $p['cuil'] ?? '' }}</td>
                                        <td>{{ $p['dni'] ?? '' }}</td>
                                        <td>{{ $p['tipo'] ?? '' }}</td>
                                        <td>{{ isset($p['fechaNacimiento']) ? \Carbon\Carbon::parse($p['fechaNacimiento'])->format('d-m-Y') : '' }}
                                        </td>
                                        <td>{{ isset($p['fechaFallecimiento']) ? \Carbon\Carbon::parse($p['fechaFallecimiento'])->format('d-m-Y') : '' }}
                                        </td>
                                        <td>{{ $p['edad'] ?? '' }}</td>
                                        <td>{{ $p['sexo'] ?? '' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table table-bordered">
                                <tbody>
                                    <thead id="datos-personales">
                                        <tr>
                                            <th style="width: 15%;">Nacionalidad</th>
                                            <th style="width: 20%;">Localidad</th>
                                            <th style="width: 20%;">Provincia</th>
                                            <th style="width: 10%;">Cód.Postal</th>
                                            <th style="width: 54%;">Domicilio</th>
                                        </tr>
                                    </thead>
                                    <tr>
                                        <td>{{ $p['nacionalidad'] ?? '' }}</td>
                                        <td>{{ $p['localidad'] ?? '' }}</td>
                                        <td>{{ $p['provincia'] ?? '' }}</td>
                                        <td>{{ $p['cp'] ?? '' }}</td>
                                        <td colspan="5">{{ $p['domicilio'] ?? '' }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            {{-- DATOS VERAZ --}}
                            @php $ver = $data['veraz'] ?? []; @endphp
                            @if ($ver)
                                <div class="col-12 mt-3">
                                    <h3>Datos Equifax Veraz</h3>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center flex-column">
                                        <p>
                                        <div class="w-100 text-center">
                                            <strong class="section-title">Score de riesgo crediticio</strong><br>
                                        </div>
                                        Este predictor representa la probabilidad que tiene una persona de cumplir con sus
                                        obligaciones crediticias en los próximos 12 meses.
                                        </p>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-center flex-column">
                                        <div class="w-100 text-center">
                                            <strong class="section-title">Predictor de Ingresos</strong><br>
                                        </div>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-center flex-column">
                                        <p>
                                        <div class="w-100 text-center">
                                            <strong class="section-title">Denuncias de Morosidad Vigentes</strong><br>
                                        </div>
                                        Monto adeudado de la persona en concepto de préstamos y créditos otorgados.</p>
                                        </p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 d-flex align-items-center justify-content-center">
                                        <div style="display: flex; flex-direction: column; align-items: center;">
                                            <div style="display: flex; min-width: 170px;">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @php
                                                        $labels = ['MUY BAJO', 'BAJO', 'MEDIO', 'ALTO', 'MUY ALTO'];
                                                        $colors = [
                                                            'rgb(255, 38, 42)',
                                                            'rgb(255, 182, 7)',
                                                            'rgb(255, 255, 47)',
                                                            'rgb(0, 255, 85)',
                                                            'rgb(0, 188, 42)',
                                                        ];
                                                        $textColors = [
                                                            'rgb(255,255,255)',
                                                            'rgb(0,0,0)',
                                                            'rgb(0,0,0)',
                                                            'rgb(0,0,0)',
                                                            'rgb(0,0,0)',
                                                        ];
                                                    @endphp
                                                    @if (($ver['scoreRango'] ?? '') == $i)
                                                        <div
                                                            style="position: relative; height: 20px; width: 20%; line-height: 1; display: flex; align-items: center; justify-content: center; background-color: {{ $colors[$i - 1] }}; color: {{ $textColors[$i - 1] }}; transform: scale(1.6, 1.6); box-shadow: rgba(0, 0, 0, 0.2) 0px 0px 5px 1px; border-radius: 2px; opacity: 1; z-index: 2;">
                                                            <strong
                                                                style="padding: 1rem; font-size: 7px; text-align: center;">{{ $labels[$i - 1] }}</strong>
                                                        </div>
                                                    @else
                                                        <div
                                                            style="position: relative; height: 20px; width: 20%; line-height: 1; display: flex; align-items: center; justify-content: center; background-color: {{ $colors[$i - 1] }}; color: {{ $textColors[$i - 1] }}; opacity: 0.5; z-index: 1;">
                                                            <strong
                                                                style="padding: 1rem; font-size: 7px; text-align: center;"
                                                                hidden>{{ $labels[$i - 1] }}</strong>
                                                        </div>
                                                    @endif
                                                @endfor
                                            </div>
                                            <div style="margin-top: 1rem;">
                                                @if (($ver['scoreRango'] ?? '') === 5)
                                                    <span><b>MUY ALTA</b> probabilidad de cumplir con obligaciones
                                                        crediticias</span>
                                                @elseif (($ver['scoreRango'] ?? '') === 4)
                                                    <span><b>ALTA</b> probabilidad de cumplir con obligaciones
                                                        crediticias</span>
                                                @elseif (($ver['scoreRango'] ?? '') === 3)
                                                    <span><b>MEDIA</b> probabilidad de cumplir con obligaciones
                                                        crediticias</span>
                                                @elseif (($ver['scoreRango'] ?? '') === 2)
                                                    <span><b>BAJA</b> probabilidad de cumplir con obligaciones
                                                        crediticias</span>
                                                @elseif (($ver['scoreRango'] ?? '') === 1)
                                                    <span><b>MUY BAJA</b> probabilidad de cumplir con obligaciones
                                                        crediticias</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-center justify-content-center">
                                        <div class="income-indicator w-100" style="margin-top: 0;">
                                            @php
                                                $incomeLabels = ['R1', 'R2', 'R3', 'R4', 'R5', 'R6', 'R7', 'R8'];
                                                $incomeColors = [
                                                    'rgb(130, 166, 82)',
                                                    'rgb(211, 214, 83)',
                                                    'rgb(243, 236, 58)',
                                                    'rgb(238, 189, 94)',
                                                    'rgb(226, 165, 74)',
                                                    'rgb(215, 112, 61)',
                                                    'rgb(210, 85, 55)',
                                                    'rgb(182, 57, 81)',
                                                ];
                                                $incomeWidths = [
                                                    '100%',
                                                    '88.8889%',
                                                    '77.7778%',
                                                    '66.6667%',
                                                    '55.5556%',
                                                    '44.4444%',
                                                    '33.3333%',
                                                    '22.2222%',
                                                ];
                                                $incomeTextColors = [
                                                    'rgb(255,255,255)',
                                                    'rgb(0,0,0)',
                                                    'rgb(0,0,0)',
                                                    'rgb(0,0,0)',
                                                    'rgb(0,0,0)',
                                                    'rgb(255,255,255)',
                                                    'rgb(255,255,255)',
                                                    'rgb(255,255,255)',
                                                ];
                                            @endphp
                                            @foreach ($incomeLabels as $idx => $lbl)
                                                <div
                                                    style="display: flex; height: 24px; width: {{ $incomeWidths[$idx] }}; position: relative; margin: 2px 0px;">
                                                    <div
                                                        style="width: 80%; height: 100%; background-color: {{ $incomeColors[$idx] }}; padding-left: 1rem; line-height: 24px; color: {{ $incomeTextColors[$idx] }};">
                                                        <strong>{{ $lbl }}</strong>
                                                        @if (($ver['incomePredictor'] ?? '') === $lbl)
                                                            <div
                                                                style="position: absolute; right: calc(100% + 2px); top: 4px;">
                                                                <div
                                                                    style="width: 0px; height: 0px; border-top: 8px solid transparent; border-bottom: 8px solid transparent; border-left: 8px solid rgba(0, 0, 0, 0.8);">
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div
                                                        style="width: 0px; height: 0px; border-style: solid; border-width: 12px 0px 12px 24px; border-color: transparent transparent transparent {{ $incomeColors[$idx] }};">
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-center justify-content-center">
                                        <div class="w-100 text-center">
                                            <strong>Cantidad de Denuncias:</strong>
                                            <span>{{ $ver['cantObsVigBa24m'] ?? 0 }}</span><br>
                                            <strong>Total:</strong>
                                            <span>${{ isset($ver['montoObsVigBa24m']) ? number_format($ver['montoObsVigBa24m'], 2, ',', '.') : '0,00' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Categoría</th>
                                            <th>Documento</th>
                                            <th>Predictor</th>
                                            <th>Ingresos</th>
                                            <th>Score Rango</th>
                                            <th>Score Segmento</th>
                                            <th>Score Texto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $ver['categoria'] ?? '' }}</td>
                                            <td>{{ $ver['documento'] ?? '' }}</td>
                                            <td>{{ $ver['incomePredictor'] ?? '' }}</td>
                                            <td>{{ $ver['incomePredictorRango'] ?? '' }}</td>
                                            <td>{{ $ver['scoreRango'] ?? '' }}</td>
                                            <td>{{ $ver['scoreSegmento'] ?? '' }}</td>
                                            <td>{{ $ver['scoreTexto'] ?? '' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            @endif

                            {{-- DATOS OBSERVACIONES VIGENTES BA --}}
                            @php 
                                // Intentar diferentes ubicaciones posibles
                                $obsVigentesBa = $data['obsVigentesBa'] ?? 
                                                $data['veraz']['obsVigentesBa'] ?? 
                                                $data['morosidad']['obsVigentesBa'] ?? 
                                                $data['morosidad']['obsVigentesBa']['datos'] ?? [];
                            @endphp
                            @if (count($obsVigentesBa ?? []) > 0)
                                <div class="col-12 mt-3">
                                    <h3>Denuncias de Morosidad Vigentes</h3>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Código Entidad</th>
                                            <th>Entidad</th>
                                            <th>Fecha</th>
                                            <th>Monto</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 0.80em;">
                                        @foreach ($obsVigentesBa as $obs)
                                            <tr>
                                                <td>{{ $obs['codigoEntidad'] ?? '' }}</td>
                                                <td>{{ $obs['nombreEntidad'] ?? '' }}</td>
                                                <td>{{ isset($obs['fecha']) ? \Carbon\Carbon::parse($obs['fecha'])->format('d-m-Y') : '' }}</td>
                                                <td class="text-end">
                                                    {{ isset($obs['monto']) ? '$' . number_format($obs['monto'], 2, ',', '.') : '' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info mt-2">
                                    No se encontraron denuncias de morosidad vigentes.
                                </div>
                            @endif

                            {{-- DATOS INFORMACION DE AGILDATA --}}
                            {{-- OPERACIONES LOCALES --}}
                            @if (isset($datos['operaciones']))
                                @php $operaciones = $datos['operaciones']; @endphp
                                <div class="col-12 mt-3">
                                    <h3>Operaciones en Nodo Federal</h3>
                                    <div class="row mb-2">
                                        <div class="col-md-3">
                                            <div class="alert alert-primary text-center">
                                                <strong>Como Titular:</strong><br>
                                                {{ $operaciones['total_como_titular'] ?? 0 }} operaciones
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="alert alert-secondary text-center">
                                                <strong>Como Garante:</strong><br>
                                                {{ $operaciones['total_como_garante'] ?? 0 }} operaciones
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="alert alert-success text-center">
                                                <strong>Activas (Titular):</strong><br>
                                                {{ $operaciones['resumen']['activas_titular'] ?? 0 }}
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="alert alert-warning text-center">
                                                <strong>Afectadas (Titular):</strong><br>
                                                {{ $operaciones['resumen']['afectadas_titular'] ?? 0 }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if (count($operaciones['como_titular'] ?? []) > 0)
                                    <div class="col-12 mt-2">
                                        <h4>Operaciones como Titular</h4>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>N° Op.</th>
                                                    <th>Estado</th>
                                                    <th>Fecha Operación</th>
                                                    <th>Fecha Estado</th>
                                                    <th>Nodo</th>
                                                    <th>Socio</th>
                                                    <th>Clase</th>
                                                    <th>Valor Cuota</th>
                                                    <th>Cuotas</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody style="font-size: 0.80em;">
                                                @foreach ($operaciones['como_titular'] as $op)
                                                    <tr>
                                                        <td>{{ $op['numero'] ?? '' }}</td>
                                                        <td>
                                                            <span
                                                                class="badge {{ ($op['estado_actual'] ?? '') == 'ACTIVO' ? 'badge-success' : 'badge-danger' }}">
                                                                {{ $op['estado_actual'] ?? '' }}
                                                            </span>
                                                        </td>
                                                        <td>{{ isset($op['fecha_operacion']) ? \Carbon\Carbon::parse($op['fecha_operacion'])->format('d-m-Y') : '' }}
                                                        </td>
                                                        <td>{{ isset($op['fecha_estado']) ? \Carbon\Carbon::parse($op['fecha_estado'])->format('d-m-Y') : '' }}
                                                        </td>
                                                        <td>{{ $op['nodo_nombre'] ?? '' }}</td>
                                                        <td>{{ $op['socio_razon_social'] ?? '' }}</td>
                                                        <td>{{ $op['clase'] ?? '' }}</td>
                                                        <td class="text-end">
                                                            {{ isset($op['valor_cuota']) ? '$' . number_format($op['valor_cuota'], 2, ',', '.') : '' }}
                                                        </td>
                                                        <td class="text-center">{{ $op['cant_cuotas'] ?? '' }}</td>
                                                        <td class="text-end">
                                                            {{ isset($op['total']) ? '$' . number_format($op['total'], 2, ',', '.') : '' }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif

                                @if (count($operaciones['como_garante'] ?? []) > 0)
                                    <div class="col-12 mt-2">
                                        <h4>Operaciones como Garante</h4>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>N° Op.</th>
                                                    <th>Estado</th>
                                                    <th>Fecha Operación</th>
                                                    <th>Fecha Estado</th>
                                                    <th>Nodo</th>
                                                    <th>Socio</th>
                                                    <th>Clase</th>
                                                    <th>Valor Cuota</th>
                                                    <th>Cuotas</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody style="font-size: 0.80em;">
                                                @foreach ($operaciones['como_garante'] as $op)
                                                    <tr>
                                                        <td>{{ $op['numero'] ?? '' }}</td>
                                                        <td>
                                                            <span
                                                                class="badge {{ ($op['estado_actual'] ?? '') == 'ACTIVO' ? 'badge-success' : 'badge-danger' }}">
                                                                {{ $op['estado_actual'] ?? '' }}
                                                            </span>
                                                        </td>
                                                        <td>{{ isset($op['fecha_operacion']) ? \Carbon\Carbon::parse($op['fecha_operacion'])->format('d-m-Y') : '' }}
                                                        </td>
                                                        <td>{{ isset($op['fecha_estado']) ? \Carbon\Carbon::parse($op['fecha_estado'])->format('d-m-Y') : '' }}
                                                        </td>
                                                        <td>{{ $op['nodo_nombre'] ?? '' }}</td>
                                                        <td>{{ $op['socio_razon_social'] ?? '' }}</td>
                                                        <td>{{ $op['clase'] ?? '' }}</td>
                                                        <td class="text-end">
                                                            {{ isset($op['valor_cuota']) ? '$' . number_format($op['valor_cuota'], 2, ',', '.') : '' }}
                                                        </td>
                                                        <td class="text-center">{{ $op['cant_cuotas'] ?? '' }}</td>
                                                        <td class="text-end">
                                                            {{ isset($op['total']) ? '$' . number_format($op['total'], 2, ',', '.') : '' }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif

                                @if (count($operaciones['nodos_involucrados'] ?? []) > 0 || count($operaciones['socios_involucrados'] ?? []) > 0)
                                    <div class="col-12 mt-2">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h5>Nodos Involucrados</h5>
                                                <ul class="list-group">
                                                    @foreach ($operaciones['nodos_involucrados'] ?? [] as $nodo)
                                                        <li class="list-group-item">{{ $nodo }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            <div class="col-md-6">
                                                <h5>Socios Involucrados</h5>
                                                <ul class="list-group">
                                                    @foreach ($operaciones['socios_involucrados'] ?? [] as $socio)
                                                        <li class="list-group-item">
                                                            <strong>N° {{ $socio['numero'] ?? '' }}:</strong>
                                                            {{ $socio['razon_social'] ?? '' }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-info mt-2">
                                    No se encontraron datos de deudores Nodo Federal.
                                </div>
                            @endif

                            {{-- DATOS DEUDORES CENTRO COMERCIAL --}}
                            @php $deudoresCentroComercial = $data['morosidad']['deudoresCentroComercial']['datos'] ?? []; @endphp
                            <div class="col-12 mt-3">
                                <h3>Deudores Nodo Federal</h3>
                            </div>
                            @if (count($deudoresCentroComercial ?? []) > 0)
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Estado</th>
                                            <th>Fecha</th>
                                            <th>Deuda Total</th>
                                            <th>Nodo</th>
                                            <th>Socio</th>
                                            <th>Tipo</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 0.80em;">
                                        @foreach ($deudoresCentroComercial as $deudor)
                                            <tr>
                                                <td>{{ $deudor['codDeEstado'] ?? '' }}</td>
                                                <td>{{ isset($deudor['fechaDeAtraso']) ? \Carbon\Carbon::parse($deudor['fechaDeAtraso'])->format('d-m-Y') : '' }}</td>
                                                <td class="text-end">
                                                    {{ isset($deudor['deudaTotal']) ? '$' . number_format($deudor['deudaTotal'], 2, ',', '.') : '' }}
                                                </td>
                                                <td>{{ $deudor['nombreInstituto'] ?? '' }}</td>
                                                <td>{{ $deudor['nombreSocio'] ?? '' }}</td>
                                                <td>{{ $deudor['tipoDeudor'] ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info mt-2">
                                    No se encontraron datos de Deudores Centro Comercial.
                                </div>
                            @endif

                            {{-- DATOS LABORALES --}}
                            @php $datosLaborales = $data['datosLaborales'] ?? []; @endphp
                            <div class="col-12 mt-3">
                                <h3>Antecedentes Laborales</h3>
                            </div>
                            @if (isset($datosLaborales['datoLaboral']['datos']) &&
                                    is_array($datosLaborales['datoLaboral']['datos']) &&
                                    count($datosLaborales['datoLaboral']['datos']) > 0)
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Estado</th>
                                            <th>Razón Social</th>
                                            <th>CUIT</th>
                                            <th style="width: 8%;">Alta</th>
                                            <th style="width: 8%;">Baja</th>
                                            <th>Actividad Empleador</th>
                                            <th>Cant. Empl.</th>
                                            <th>Domicilio</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 0.70em;">
                                        @foreach ($datosLaborales['datoLaboral']['datos'] as $laboral)
                                            <tr>
                                                <td>{{ $laboral['relacionDependencia']['estado'] ?? '' }}</td>
                                                <td>{{ $laboral['relacionDependencia']['razonSocial'] ?? '' }}</td>
                                                <td>{{ $laboral['relacionDependencia']['cuit'] ?? '' }}</td>
                                                <td>{{ isset($laboral['relacionDependencia']['alta']) ? \Carbon\Carbon::parse($laboral['relacionDependencia']['alta'])->format('d-m-Y') : '' }}
                                                </td>
                                                <td>
                                                    @if (($laboral['relacionDependencia']['estado'] ?? '') === 'Activo')
                                                        {{-- Si está activo, no mostrar fecha de baja --}}
                                                    @else
                                                        {{ isset($laboral['relacionDependencia']['baja']) ? \Carbon\Carbon::parse($laboral['relacionDependencia']['baja'])->format('d-m-Y') : '' }}
                                                    @endif
                                                </td>
                                                </td>
                                                <td>{{ $laboral['historialSueldo']['infoAdicionalEmpleador']['actividadempleador'] ?? '' }}
                                                </td>
                                                <td class="text-end">
                                                    {{ $laboral['historialSueldo']['infoAdicionalEmpleador']['cantidadempleados'] ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $laboral['historialSueldo']['infoAdicionalEmpleador']['domicilio']['direccion'] ?? '' }},
                                                    {{ $laboral['historialSueldo']['infoAdicionalEmpleador']['domicilio']['localidad'] ?? '' }},
                                                    {{ $laboral['historialSueldo']['infoAdicionalEmpleador']['domicilio']['provincia'] ?? '' }},
                                                    {{ $laboral['historialSueldo']['infoAdicionalEmpleador']['domicilio']['partido'] ?? '' }},
                                                    CP:
                                                    {{ $laboral['historialSueldo']['infoAdicionalEmpleador']['domicilio']['cp'] ?? '' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info mt-2">
                                    No se encontraron datos Laborales.
                                </div>
                            @endif

                            {{-- DATOS MONOTRIBUTISTA --}}
                            @php $monos = $data['datosLaborales']['monotributista']['datos'] ?? []; @endphp
                            <div class="col-12 mt-3">
                                <h3>Autónomo o Monotributo</h3>
                            </div>
                            @if (count($monos ?? []) > 0)
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Tipo</th>
                                            <th>Categoría</th>
                                            <th>Ganancias</th>
                                            <th>IVA</th>
                                            <th>Inicio</th>
                                            <th>Hasta</th>
                                            <th>Código</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($monos as $mono)
                                            <tr>
                                                <td>{{ $mono['tipo'] ?? '' }}</td>
                                                <td>{{ $mono['categoria'] ?? '' }}</td>
                                                <td>{{ $mono['ganancias'] ?? '' }}</td>
                                                <td>{{ $mono['iva'] ?? '' }}</td>
                                                <td>{{ isset($mono['fechaInicio']) ? \Carbon\Carbon::parse($mono['fechaInicio'])->format('d-m-Y') : '' }}
                                                </td>
                                                <td>{{ isset($mono['fechaHasta']) ? \Carbon\Carbon::parse($mono['fechaHasta'])->format('d-m-Y') : '' }}
                                                </td>
                                                <td>{{ $mono['codigo'] ?? '' }}</td>
                                                <td>{{ $mono['estado'] ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info mt-2">
                                    No se encontraron datos de Monotributistas.
                                </div>
                            @endif

                            {{-- DATOS ACTIVIDAD --}}
                            @php $acts = $data['datosLaborales']['actividad']['datos'] ?? []; @endphp
                            <div class="col-12 mt-3">
                                <h3>Actividad</h3>
                            </div>
                            @if (count($acts ?? []) > 0)
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>CUIL</th>
                                            <th>Descripción</th>
                                            <th>CIIU</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($acts as $act)
                                            <tr>
                                                <td>{{ $act['cuil'] ?? '' }}</td>
                                                <td>{{ $act['descripcion'] ?? '' }}</td>
                                                <td>{{ $act['ciiu'] ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info mt-2">
                                    No se encontraron datos de actividad laboral.
                                </div>
                            @endif

                            {{-- DATOS OBRA SOCIAL --}}
                            @php $os = $data['datosLaborales']['obraSocial']['datos'] ?? []; @endphp
                            <div class="col-12 mt-3">
                                <h3>Obra Social</h3>
                            </div>
                            @if (count($os ?? []) > 0)
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Descripción</th>
                                            <th>Código</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($os as $obra)
                                            <tr>
                                                <td>{{ $obra['descripcion'] ?? '' }}</td>
                                                <td>{{ $obra['codigo'] ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info mt-2">
                                    No se encontraron datos de Obra Social.
                                </div>
                            @endif

                            {{-- JUBILACION --}}
                            @php $jubilacion = $data['datosLaborales']['jubilacion']['datos'] ?? []; @endphp
                            <div class="col-12 mt-3">
                                <h3>Jubilación</h3>
                            </div>
                            @if (count($jubilacion ?? []) > 0)
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>CUIL</th>
                                            <th>Titular</th>
                                            <th>CUIL Apod.</th>
                                            <th>Apoderado</th>
                                            <th>Sueldo Bruto</th>
                                            <th>Sueldo Neto</th>
                                            <th>Periodo</th>
                                            <th>Rango</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($jubilacion as $jub)
                                            <tr>
                                                <td>{{ $jub['cuil'] ?? '' }}</td>
                                                <td>{{ $jub['titular'] ?? '' }}</td>
                                                <td>{{ $jub['cuilApo'] ?? '' }}</td>
                                                <td>{{ $jub['apoderado'] ?? '' }}</td>
                                                <td class="text-end">
                                                    {{ isset($jub['sueldoBruto']) ? number_format($jub['sueldoBruto'], 2, ',', '.') : '' }}
                                                </td>
                                                <td class="text-end">
                                                    {{ isset($jub['sueldoNeto']) ? number_format($jub['sueldoNeto'], 2, ',', '.') : '' }}
                                                </td>
                                                <td>{{ isset($jub['periodo']) ? \Carbon\Carbon::parse($jub['periodo'])->format('d-m-Y') : '' }}
                                                </td>
                                                <td>{{ $jub['rango'] ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info mt-2">
                                    No se encontraron datos de Jubilación.
                                </div>
                            @endif

                            {{-- TELEFONOS --}}
                            @php $telefonos = $data['telefonos']['datos'] ?? []; @endphp
                            <div class="col-12 mt-3">
                                <h3>Teléfonos</h3>
                            </div>
                            @if (count($telefonos ?? []) > 0)
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Tipo</th>
                                            <th>Número</th>
                                            <th>Operador</th>
                                            <th>Teléfono</th>
                                            <th>Localidad</th>
                                            <th>Área</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($telefonos as $tel)
                                            <tr>
                                                <td>{{ $tel['tipo'] ?? '' }}</td>
                                                <td>{{ $tel['nro'] ?? '' }}</td>
                                                <td>{{ $tel['operador'] ?? '' }}</td>
                                                <td>{{ $tel['tel'] ?? '' }}</td>
                                                <td>{{ $tel['localidad'] ?? '' }}</td>
                                                <td>{{ $tel['area'] ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info mt-2">
                                    No se encontraron datos de Teléfonos Fijos.
                                </div>
                            @endif

                            {{-- CELULARES --}}
                            @php $celulares = $data['telefonosCelulares']['datos'] ?? []; @endphp
                            <div class="col-12 mt-3">
                                <h3>Celulares</h3>
                            </div>
                            @if (count($celulares ?? []) > 0)
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Tipo</th>
                                            <th>Número</th>
                                            <th>Operador</th>
                                            <th>Teléfono</th>
                                            <th>Localidad</th>
                                            <th>Área</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($celulares as $cel)
                                            <tr>
                                                <td>{{ $cel['tipo'] ?? '' }}</td>
                                                <td>{{ $cel['nro'] ?? '' }}</td>
                                                <td>{{ $cel['operador'] ?? '' }}</td>
                                                <td>{{ $cel['tel'] ?? '' }}</td>
                                                <td>{{ $cel['localidad'] ?? '' }}</td>
                                                <td>{{ $cel['area'] ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info mt-2">
                                    No se encontraron datos de Teléfonos Celulares.
                                </div>
                            @endif

                            {{-- ADICIONALES --}}
                            @php $adicionales = $data['telefonosAdicionales']['datos'] ?? []; @endphp
                            <div class="col-12 mt-3">
                                <h3>Teléfonos Adicionales</h3>
                            </div>
                            @if (count($adicionales ?? []) > 0)
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Tipo</th>
                                            <th>Teléfono</th>
                                            <th>Localidad</th>
                                            <th>Domicilio</th>
                                            <th>CP</th>
                                            <th>Provincia</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($adicionales as $adi)
                                            <tr>
                                                <td>{{ $cel['tipo'] ?? '' }}</td>
                                                <td>{{ $cel['tel'] ?? '' }}</td>
                                                <td>{{ $adi['localidad'] ?? '' }}</td>
                                                <td>{{ $adi['domicilio'] ?? '' }}</td>
                                                <td>{{ $adi['cp'] ?? '' }}</td>
                                                <td>{{ $adi['provincia'] ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info mt-2">
                                    No se encontraron datos de Teléfonos Adicionales.
                                </div>
                            @endif


                            {{-- DATOS AUTOMOTORES --}}
                            @php
                                $automotores = $data['bienesPersonales']['automotores']['datos'] ?? [];
                                $automotoresHistorial =
                                    $data['bienesPersonales']['automotores_historial']['datos'] ?? [];
                                $autosembargos = $data['bienesPersonales']['autosembargos']['datos'] ?? [];
                            @endphp
                            <div class="col-12 mt-3">
                                <h3>Automotores</h3>
                            </div>
                            @if (count($automotores ?? []) > 0)
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Dominio</th>
                                            <th>Marca</th>
                                            <th>Modelo</th>
                                            <th>Año</th>
                                            <th>Tipo</th>
                                            <th>Origen</th>
                                            <th>Porcentaje</th>
                                            <th>Compra</th>
                                            <th>Trámite</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($automotores as $auto)
                                            <tr>
                                                <td>{{ $auto['dominio'] ?? '' }}</td>
                                                <td>{{ $auto['marca'] ?? '' }}</td>
                                                <td>{{ $auto['modelo'] ?? '' }}</td>
                                                <td>{{ $auto['anioModelo'] ?? '' }}</td>
                                                <td>{{ $auto['tipo'] ?? '' }}</td>
                                                <td>{{ $auto['origen'] ?? '' }}</td>
                                                <td>{{ $auto['porcentaje'] ?? '' }}</td>
                                                <td>{{ isset($auto['compra']) ? \Carbon\Carbon::parse($auto['compra'])->format('d-m-Y') : '' }}
                                                </td>
                                                <td>{{ isset($auto['tramite']) ? \Carbon\Carbon::parse($auto['tramite'])->format('d-m-Y') : '' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info mt-2">
                                    No se encontraron datos de Automotores.
                                </div>
                            @endif

                            <div class="col-12 mt-3">
                                <h3>Historial de Automotores</h3>
                            </div>
                            @if (count($automotoresHistorial ?? []) > 0)
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Dominio</th>
                                            <th>Marca</th>
                                            <th>Modelo</th>
                                            <th>Año</th>
                                            <th>Tipo</th>
                                            <th>Origen</th>
                                            <th>Porcentaje</th>
                                            <th>DNI</th>
                                            <th>Compra</th>
                                            <th>Trámite</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($automotoresHistorial as $autoh)
                                            <tr>
                                                <td>{{ $autoh['dominio'] ?? '' }}</td>
                                                <td>{{ $autoh['marca'] ?? '' }}</td>
                                                <td>{{ $autoh['modelo'] ?? '' }}</td>
                                                <td>{{ $autoh['anioModelo'] ?? '' }}</td>
                                                <td>{{ $autoh['tipo'] ?? '' }}</td>
                                                <td>{{ $autoh['origen'] ?? '' }}</td>
                                                <td>{{ $autoh['porcentaje'] ?? '' }}</td>
                                                <td>{{ $autoh['dni'] ?? '' }}</td>
                                                <td>{{ isset($autoh['compra']) ? \Carbon\Carbon::parse($autoh['compra'])->format('d-m-Y') : '' }}
                                                </td>
                                                <td>{{ isset($autoh['tramite']) ? \Carbon\Carbon::parse($autoh['tramite'])->format('d-m-Y') : '' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info mt-2">
                                    No se encontraron datos de historial de automotores.
                                </div>
                            @endif

                            <div class="col-12 mt-3">
                                <h3>Autos embargados</h3>
                            </div>
                            @if (count($autosEmbargos ?? []) > 0)
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Dominio</th>
                                            <th>Deuda</th>
                                            <th>Valuación</th>
                                            <th>Marca</th>
                                            <th>Localidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($autosEmbargos as $autoe)
                                            <tr>
                                                <td>{{ $autoe['dominio'] ?? '' }}</td>
                                                <td class="text-end">
                                                    {{ isset($autoe['deuda']) ? number_format($autoe['deuda'], 2, ',', '.') : '' }}
                                                </td>
                                                <td class="text-end">
                                                    {{ isset($autoe['valuacion']) ? number_format($autoe['valuacion'], 2, ',', '.') : '' }}
                                                </td>
                                                <td>{{ $autoe['marca'] ?? '' }}</td>
                                                <td>{{ $autoe['localidad'] ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info mt-2">
                                    No se encontraron datos de autos embargados.
                                </div>
                            @endif

                            {{-- DATOS INFORMACION DEL BCRA --}}
                            @php
                                $morosidad = $data['morosidad']['informacionBcra']['datos'] ?? [];
                                // Agrupar primero por tipo y dentro de cada tipo por entidad
                                $agrupados = collect($morosidad)
                                    ->sortBy([
                                        fn($item) => $item['entidad']['tipo'] ?? '',
                                        fn($item) => $item['entidad']['entidad'] ?? '',
                                    ])
                                    ->groupBy(fn($item) => $item['entidad']['tipo'] ?? '')
                                    ->map(function ($items) {
                                        return collect($items)->groupBy(fn($item) => $item['entidad']['entidad'] ?? '');
                                    });
                            @endphp
                            <div class="col-12 mt-3">
                                <h3>* Situación Financiera</h3>
                                <h4>Información suministrada por el BCRA</h4>
                            </div>
                            @if (!empty($agrupados) && $agrupados->count())
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Tipo</th>
                                            <th>Entidad</th>
                                            <th>Período</th>
                                            <th>Situación</th>
                                            <th>Préstamo</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 0.70em;">
                                        @foreach ($agrupados as $tipo => $entidades)
                                            @foreach ($entidades as $entidad => $registros)
                                                @foreach ($registros as $moras)
                                                    <tr>
                                                        <td>{{ $tipo }}</td>
                                                        <td>{{ $entidad }}</td>
                                                        <td class="text-center">
                                                            {{ isset($moras['periodo']) ? \Carbon\Carbon::parse($moras['periodo'])->format('m-Y') : '' }}
                                                        </td>
                                                        <td class="text-center">{{ $moras['situacion'] ?? '' }}</td>
                                                        <td class="text-end">
                                                            {{ isset($moras['prestamo']) ? number_format($moras['prestamo'], 2, ',', '.') : '' }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                                <p style="font-size: 0.70em;"><strong>Referencias:</strong></p>
                                <p style="font-size: 0.70em;">
                                    <b>0:</b> Sin información en BCRA -
                                    <b>1:</b> Situación normal (pago puntual o atrasos menores a 31 días) -
                                    <b>2:</b> Con riesgo potencial (con atrasos entre 31 y 90 días) -
                                    <b>3:</b> Cumplimiento deficiente (con atrasos entre 90 y 180 días) -
                                    <b>4:</b> Con alto riesgo de insolvencia (con atrasos entre 180 y 1 año) -
                                    <b>5:</b> Irrecuperable (con atrasos mayores a 1 año) -
                                    <b>6:</b> Irrecuperable por disposición técnica (entidades liquidadas, en proceso de
                                    disolución o en quiebra, en gestión judicial)
                                </p>
                            @else
                                <div class="alert alert-info mt-2">
                                    No se encontraron datos de Morosidad.
                                </div>
                            @endif

                            {{-- DATOS INFORMACION DEL BCRA CHEQUES RECHAZADOS --}}
                            @php $chqrs = $data['morosidad']['chequesRechazados']['datos'] ?? []; @endphp
                            <div class="col-12 mt-3">
                                <h3>Cheques Rechazados</h3>
                            </div>
                            @if (!empty($chqrs) && is_array($chqrs))
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Número</th>
                                            <th>Fecha Rechazo</th>
                                            <th>Importe</th>
                                            <th>Motivo</th>
                                            <th>Fecha Pago</th>
                                            <th>Levantado</th>
                                            <th>Multa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($chqrs as $chq)
                                            <tr>
                                                <td>{{ $chq['nroCheque'] ?? '' }}</td>
                                                <td>{{ isset($chq['fechaRechazo']) ? \Carbon\Carbon::parse($chq['fechaRechazo'])->format('d-m-Y') : '' }}
                                                </td>
                                                <td class="text-end">
                                                    {{ isset($chq['monto']) ? number_format($chq['monto'], 2, ',', '.') : '' }}
                                                </td>
                                                <td>{{ $chq['causal'] ?? '' }}</td>
                                                <td>{{ isset($chq['fechaPago']) ? \Carbon\Carbon::parse($chq['fechaPago'])->format('d-m-Y') : '' }}
                                                </td>
                                                <td>{{ isset($chq['fechaLevantamiento']) ? \Carbon\Carbon::parse($chq['fechaLevantamiento'])->format('d-m-Y') : '' }}
                                                </td>
                                                <td>{{ $chq['multa'] ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info mt-2">
                                    No se encontraron datos de Cheques Rechazados.
                                </div>
                            @endif

                            {{-- DATOS INFORMACION DEL BCRA DEUDORES BANCO CENTRAL --}}
                            @php $deudores = $data['morosidad']['deudoresBancoCentral']['datos'] ?? []; @endphp
                            <div class="col-12 mt-3">
                                <h3>Deudores Banco Central</h3>
                            </div>
                            @if (!empty($deudores) && is_array($deudores))
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Entidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($deudores as $deudor)
                                            <tr>
                                                <td>{{ $deudor['entidad'] ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info mt-2">
                                    No se encontraron datos de deudores BCRA.
                                </div>
                            @endif

                            {{-- BOLETÍN OFICIAL --}}
                            @php $boletin = $data['boletinOficial']['datos'] ?? []; @endphp
                            <div class="col-12 mt-3">
                                <h3>* Boletín Oficial</h3>
                                <h4>Sociedades Comerciales</h4>
                            </div>
                            @if (count($boletin ?? []) > 0)
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Detalle</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($boletin as $b)
                                            <tr>
                                                <td>{{ isset($b['fecha']) ? \Carbon\Carbon::parse($b['fecha'])->format('d-m-Y') : '' }}
                                                </td>
                                                <td>{{ $b['detalle'] ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info mt-2">
                                    No se encontraron datos de sociedades.
                                </div>
                            @endif

                            {{-- MENCIONES EN BOLETÍN OFICIAL --}}
                            @php $menciones = $data['boletinOficial']['menciones']['datos'] ?? []; @endphp
                            <div class="col-12 mt-3">
                                <h3>Menciones en Boletín Oficial</h3>
                            </div>
                            @if (count($menciones ?? []) > 0)
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Detalle</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($menciones as $m)
                                            <tr>
                                                <td>{{ isset($m['fecha']) ? \Carbon\Carbon::parse($m['fecha'])->format('d-m-Y') : '' }}
                                                </td>
                                                <td>{{ $m['detalle'] ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info mt-2">
                                    No se encontraron menciones en Boletín Oficial.
                                </div>
                            @endif

                            <p><strong>La información suministrada en el presente informe, extraída de bases públicas,
                                    privadas y
                                    propias, es confidencial y
                                    reservada.<br>El cliente es el único habilitado a consultar la información, estando
                                    expresamente prohibida la divulgación de la misma a
                                    terceros —total o parcialmente— observando el deber de confidencialidad y uso permitido,
                                    según Ley 25.236.</strong></p>

                        </form>
                    @else
                        <div class="alert alert-warning mt-4">
                            <strong>No se encontraron datos para la consulta.</strong>
                        </div>
                    @endif
                @else
                    <div class="alert alert-warning mt-4">
                        <strong>Realice una consulta para ver el informe.</strong>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection
