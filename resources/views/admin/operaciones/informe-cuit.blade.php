@extends('layouts.admin')

@section('content')

    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="card-title mb-0">Informe de Empresa</h1>
                    @if (isset($datos) && isset($datos['data']))
                        <a href="{{ url('admin/operaciones/pdf-cuit') }}" class="btn btn-success" target="_blank">
                            <i class="bi bi-printer-fill"></i> Generar PDF
                        </a>
                    @endif
                    <a href="{{ url('admin/operaciones/consultar-cuit') }}" class="btn btn-info">
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

                        #datos-empresa th {
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

                    @php $empresa = $data['datosGenerales']['empresa']['datos'] ?? null; @endphp
                    @if ($empresa)
                        <form class="row g-3 mt-4">

                            <div class="col-12 mb-2">
                                <h3>Datos de la Consulta</h3>
                            </div>
                            <table class="table table-bordered">
                                <thead id="datos-empresa">
                                    <tr>
                                        <th style="width: 34%;">CUIT Consultado</th>
                                        <th style="width: 33%;">Nodo Seleccionado</th>
                                        <th style="width: 33%;">Socio Seleccionado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $filtrosConsulta['cuit'] ?? ($empresa['cuit'] ?? '') }}</td>
                                        <td>{{ $nodoConsulta->nombre ?? 'Todos los nodos' }}</td>
                                        <td>{{ $socioConsulta->razon_social ?? ($socioConsulta->nombre ?? 'Todos los socios') }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="col-12 mb-3">
                                @if (isset($data['idLog']))
                                    <div><strong>ID Log:</strong> {{ $data['idLog'] }}</div>
                                @endif
                            </div>

                            {{-- DATOS GENERALES DE LA EMPRESA --}}
                            <table class="table table-bordered">
                                <div class="col-12 mt-3">
                                    <h3>Datos Generales</h3>
                                </div>
                                <tbody>
                                    <thead id="datos-empresa">
                                        <tr>
                                            <th>Razón Social</th>
                                            <th>CUIT</th>
                                            <th>Actividad</th>
                                            <th>CIIU</th>
                                            <th>Constitución</th>
                                            <th>Empleados</th>
                                        </tr>
                                    </thead>
                                    <tr>
                                        <td>{{ $empresa['razon'] ?? '' }}</td>
                                        <td>{{ $empresa['cuit'] ?? '' }}</td>
                                        <td>{{ $empresa['actividad'] ?? '' }}</td>
                                        <td>{{ $empresa['ciiu'] ?? '' }}</td>
                                        <td>{{ isset($empresa['constitucion']) ? \Carbon\Carbon::parse($empresa['constitucion'])->format('d-m-Y') : '' }}</td>
                                        <td>{{ $empresa['empleados'] ?? '' }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="table table-bordered">
                                <tbody>
                                    <thead id="datos-empresa">
                                        <tr>
                                            <th style="width: 15%;">Localidad</th>
                                            <th style="width: 15%;">Partido</th>
                                            <th style="width: 15%;">Provincia</th>
                                            <th style="width: 10%;">Cód.Postal</th>
                                            <th style="width: 45%;">Domicilio</th>
                                        </tr>
                                    </thead>
                                    <tr>
                                        <td>{{ $empresa['localidad'] ?? '' }}</td>
                                        <td>{{ $empresa['partido'] ?? '' }}</td>
                                        <td>{{ $empresa['provincia'] ?? '' }}</td>
                                        <td>{{ $empresa['cp'] ?? '' }}</td>
                                        <td>{{ $empresa['direccion'] ?? '' }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            {{-- ACTIVIDADES --}}
                            @php $actividades = $data['datosGenerales']['actividad']['datos'] ?? []; @endphp
                            @if (count($actividades) > 0)
                                <div class="col-12 mt-3">
                                    <h3>Actividades</h3>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>CUIT</th>
                                            <th>Descripción</th>
                                            <th>CIIU</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($actividades as $actividad)
                                            <tr>
                                                <td>{{ $actividad['cuil'] ?? '' }}</td>
                                                <td>{{ $actividad['descripcion'] ?? '' }}</td>
                                                <td>{{ $actividad['ciiu'] ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif

                            {{-- TELEFONOS FIJOS --}}
                            @php $telefonos = $data['telefonos']['datos'] ?? []; @endphp
                            @if (count($telefonos) > 0)
                                <div class="col-12 mt-3">
                                    <h3>Teléfonos Fijos</h3>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Área</th>
                                            <th>Número</th>
                                            <th>Teléfono Completo</th>
                                            <th>Operador</th>
                                            <th>Localidad</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 0.90em;">
                                        @foreach ($telefonos as $tel)
                                            <tr>
                                                <td>{{ $tel['area'] ?? '' }}</td>
                                                <td>{{ $tel['nro'] ?? '' }}</td>
                                                <td>{{ $tel['tel'] ?? '' }}</td>
                                                <td>{{ $tel['operador'] ?? '' }}</td>
                                                <td>{{ $tel['localidad'] ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info mt-2">
                                    No se encontraron teléfonos fijos registrados.
                                </div>
                            @endif

                            {{-- TELEFONOS CELULARES --}}
                            @php $telefonosCelulares = $data['telefonosCelulares']['datos'] ?? []; @endphp
                            @if (count($telefonosCelulares) > 0)
                                <div class="col-12 mt-3">
                                    <h3>Teléfonos Celulares</h3>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Área</th>
                                            <th>Número</th>
                                            <th>Teléfono Completo</th>
                                            <th>Operador</th>
                                            <th>WhatsApp</th>
                                            <th>Localidad</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 0.90em;">
                                        @foreach ($telefonosCelulares as $tel)
                                            <tr>
                                                <td>{{ $tel['area'] ?? '' }}</td>
                                                <td>{{ $tel['nro'] ?? '' }}</td>
                                                <td>{{ $tel['tel'] ?? '' }}</td>
                                                <td>{{ $tel['operador'] ?? '' }}</td>
                                                <td>
                                                    @if($tel['wsp'] ?? false)
                                                        <span class="badge badge-success">Sí</span>
                                                    @else
                                                        <span class="badge badge-secondary">No</span>
                                                    @endif
                                                </td>
                                                <td>{{ $tel['localidad'] ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info mt-2">
                                    No se encontraron teléfonos celulares registrados.
                                </div>
                            @endif

                            {{-- AUTOMOTORES HISTORICO --}}
                            @php $automotoresHistorial = $data['bienes']['automotores_historial']['datos'] ?? []; @endphp
                            @if (count($automotoresHistorial) > 0)
                                <div class="col-12 mt-3">
                                    <h3>Automotores (Historial)</h3>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Dominio</th>
                                            <th>Marca</th>
                                            <th>Modelo</th>
                                            <th>Año Modelo</th>
                                            <th>Tipo</th>
                                            <th>Origen</th>
                                            <th>Fecha Compra</th>
                                            <th>Porcentaje</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 0.90em;">
                                        @foreach ($automotoresHistorial as $auto)
                                            <tr>
                                                <td>{{ $auto['dominio'] ?? '' }}</td>
                                                <td>{{ $auto['marca'] ?? '' }}</td>
                                                <td>{{ $auto['modelo'] ?? '' }}</td>
                                                <td>{{ $auto['anioModelo'] ?? '' }}</td>
                                                <td>{{ $auto['tipo'] ?? '' }}</td>
                                                <td>{{ $auto['origen'] ?? '' }}</td>
                                                <td>{{ isset($auto['compra']) ? \Carbon\Carbon::parse($auto['compra'])->format('d-m-Y') : '' }}</td>
                                                <td>{{ $auto['porcentaje'] ?? '' }}%</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info mt-2">
                                    No se encontraron automotores registrados.
                                </div>
                            @endif

                            {{-- SITUACION FINANCIERA - BANCOS --}}
                            @php $bancoOpera = $data['sitFinanciera']['bancoOpera']['datos'] ?? []; @endphp
                            @if (count($bancoOpera) > 0)
                                <div class="col-12 mt-3">
                                    <h3>Situación Financiera - Bancos</h3>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Banco</th>
                                            <th>Titular</th>
                                            <th>Condición</th>
                                            <th>CUIT</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 0.90em;">
                                        @foreach ($bancoOpera as $banco)
                                            <tr>
                                                <td>{{ $banco['banco'] ?? '' }}</td>
                                                <td>{{ $banco['titular'] ?? '' }}</td>
                                                <td>{{ $banco['otros'] ?? '' }}</td>
                                                <td>{{ $banco['cuil'] ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info mt-2">
                                    No se encontraron bancos donde opera la empresa.
                                </div>
                            @endif

                            {{-- MOROSIDAD - INFORMACION BCRA --}}
                            @php $infoBcra = $data['morosidad']['informacionBcra']['datos'] ?? []; @endphp
                            @if (count($infoBcra) > 0)
                                <div class="col-12 mt-3">
                                    <h3>Información BCRA - Morosidad</h3>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Período</th>
                                            <th>Entidad</th>
                                            <th>Código Entidad</th>
                                            <th>Tipo Entidad</th>
                                            <th>Situación</th>
                                            <th>Préstamo</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 0.85em;">
                                        @foreach ($infoBcra as $bcra)
                                            <tr>
                                                <td>{{ isset($bcra['periodo']) ? \Carbon\Carbon::parse($bcra['periodo'])->format('m/Y') : '' }}</td>
                                                <td>{{ $bcra['entidad']['entidad'] ?? '' }}</td>
                                                <td>{{ $bcra['entidad']['codigoEnt'] ?? '' }}</td>
                                                <td>{{ $bcra['entidad']['tipo'] ?? '' }}</td>
                                                <td>
                                                    @php
                                                        $situaciones = [
                                                            '01' => 'Normal',
                                                            '02' => '30 a 89 días',
                                                            '03' => '90 a 179 días',
                                                            '04' => '180 a 365 días',
                                                            '05' => 'Más de 365 días',
                                                            '06' => 'Incobrable',
                                                            '07' => 'Castigado'
                                                        ];
                                                    @endphp
                                                    {{ $situaciones[$bcra['situacion'] ?? ''] ?? $bcra['situacion'] ?? '' }}
                                                </td>
                                                <td class="text-end">
                                                    ${{ isset($bcra['prestamo']) ? number_format($bcra['prestamo'], 2, ',', '.') : '0,00' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info mt-2">
                                    No se encontraron datos de morosidad BCRA.
                                </div>
                            @endif

                            {{-- CHEQUES RECHAZADOS --}}
                            @php $chequesRechazados = $data['morosidad']['chequesRechazados']['datos'] ?? []; @endphp
                            @if (count($chequesRechazados) > 0)
                                <div class="col-12 mt-3">
                                    <h3>Cheques Rechazados</h3>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>N° Cheque</th>
                                            <th>Fecha Rechazo</th>
                                            <th>Monto</th>
                                            <th>Causal</th>
                                            <th>Fecha Levantamiento</th>
                                            <th>Multa</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 0.90em;">
                                        @foreach ($chequesRechazados as $cheque)
                                            <tr>
                                                <td>{{ $cheque['nroCheque'] ?? '' }}</td>
                                                <td>{{ isset($cheque['fechaRechazo']) ? \Carbon\Carbon::parse($cheque['fechaRechazo'])->format('d-m-Y') : '' }}</td>
                                                <td class="text-end">
                                                    ${{ isset($cheque['monto']) ? number_format($cheque['monto'], 2, ',', '.') : '0,00' }}
                                                </td>
                                                <td>{{ $cheque['causal'] ?? '' }}</td>
                                                <td>{{ isset($cheque['fechaLevantamiento']) && $cheque['fechaLevantamiento'] ? \Carbon\Carbon::parse($cheque['fechaLevantamiento'])->format('d-m-Y') : 'No levantado' }}</td>
                                                <td>{{ $cheque['multa'] ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info mt-2">
                                    No se encontraron cheques rechazados.
                                </div>
                            @endif

                            {{-- VARIABLES DE EVALUACION --}}
                            @php $variables = $data['variables'] ?? []; @endphp
                            @if (isset($variables) && (count($variables['variableAprobada'] ?? []) > 0 || count($variables['variableRechazo'] ?? []) > 0))
                                <div class="col-12 mt-3">
                                    <h3>Variables de Evaluación</h3>
                                </div>
                                
                                @if (count($variables['variableAprobada'] ?? []) > 0)
                                    <div class="col-12 mb-2">
                                        <h5>Variables Aprobadas</h5>
                                        <ul class="list-group">
                                            @foreach ($variables['variableAprobada'] as $var)
                                                <li class="list-group-item list-group-item-success">
                                                    <i class="bi bi-check-circle-fill"></i> {{ $var }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if (count($variables['variableRechazo'] ?? []) > 0)
                                    <div class="col-12 mb-2">
                                        <h5>Variables de Rechazo</h5>
                                        <ul class="list-group">
                                            @foreach ($variables['variableRechazo'] as $var)
                                                <li class="list-group-item list-group-item-danger">
                                                    <i class="bi bi-x-circle-fill"></i> {{ $var }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            @endif

                        </form>
                    @else
                        <div class="alert alert-warning">
                            No se encontraron datos de la empresa.
                        </div>
                    @endif

                @else
                    <div class="alert alert-warning">
                        No hay datos disponibles. Por favor, realice una consulta primero.
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection
