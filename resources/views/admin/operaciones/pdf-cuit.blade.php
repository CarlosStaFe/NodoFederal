<div class="container mt-4">
    <div class="row">
        <div class="col-12 mb-3">
            <div class="d-flex align-items-center">
                <img src="{{ public_path('assets/img/NF-LOGO.jpg') }}" alt="Nodo Federal Logo"
                    style="height: 70px; margin-right: 15px; display: inline-block; vertical-align: middle;">
                <div>
                    <h2 class="mb-0" style="display: inline-block; vertical-align: middle; font-size: 1.15rem;">Informe de Empresa</h2>
                    <div style="font-size: 0.7em; color: #555;">Consulta por CUIT</div>
                </div>
            </div>
        </div>

        @php $data = $datos['data'] ?? null; @endphp
        @php $empresa = $data['datosGenerales']['empresa']['datos'] ?? null; @endphp

        <style>
            .table {
                width: 100% !important;
                border-collapse: collapse;
            }

            .table thead th {
                background-color: #37a395;
                color: #fff;
                font-weight: bold;
                font-size: 0.68em;
                border: 1px solid #9dbfbb;
                padding: 6px;
            }

            .table tbody td {
                font-size: 0.58em;
                border: 1px solid #b5b5b5;
                padding: 5px;
            }

            #datos-empresa th {
                background-color: #3750a3;
                color: #fff;
                font-weight: bold;
            }

            .section-title {
                color: #0d6efd;
                font-size: 0.92rem;
                font-weight: bold;
                margin-bottom: 0.35em;
            }

            .watermark {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) rotate(-40deg);
                width: 100vw;
                opacity: 0.10;
                z-index: 0;
                color: #3750a3;
                font-size: 4em;
                text-align: center;
                white-space: nowrap;
                pointer-events: none;
            }

            .page-break {
                page-break-after: always;
            }
        </style>

        <div class="watermark">Nodo Federal - 0342 156267364</div>

        @if ($empresa)
            <div class="col-12 mb-2">
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
            </div>

            @if (isset($data['idLog']))
                <div style="margin-bottom: 8px; font-size: 0.72em;"><strong>ID Log:</strong> {{ $data['idLog'] }}</div>
            @endif

            <div class="col-12 mt-2">
                <h4 style="font-size: 0.95rem; margin-bottom: 0.25rem;">Datos Generales</h4>
            </div>
            <table class="table table-bordered">
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
                <tbody>
                    <tr>
                        <td>{{ $empresa['razon'] ?? '' }}</td>
                        <td>{{ $empresa['cuit'] ?? '' }}</td>
                        <td>{{ $empresa['actividad'] ?? '' }}</td>
                        <td>{{ $empresa['ciiu'] ?? '' }}</td>
                        <td style="text-align: center;">{{ isset($empresa['constitucion']) ? \Carbon\Carbon::parse($empresa['constitucion'])->format('d-m-Y') : '' }}</td>
                        <td style="text-align: right;">{{ $empresa['empleados'] ?? '' }}</td>
                    </tr>
                </tbody>
            </table>

            <table class="table table-bordered">
                <thead id="datos-empresa">
                    <tr>
                        <th style="width: 15%;">Localidad</th>
                        <th style="width: 15%;">Partido</th>
                        <th style="width: 15%;">Provincia</th>
                        <th style="width: 10%;">Cód.Postal</th>
                        <th style="width: 45%;">Domicilio</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $empresa['localidad'] ?? '' }}</td>
                        <td>{{ $empresa['partido'] ?? '' }}</td>
                        <td>{{ $empresa['provincia'] ?? '' }}</td>
                        <td>{{ $empresa['cp'] ?? '' }}</td>
                        <td>{{ $empresa['direccion'] ?? '' }}</td>
                    </tr>
                </tbody>
            </table>

            @php $actividades = $data['datosGenerales']['actividad']['datos'] ?? []; @endphp
            @if (count($actividades) > 0)
                <div class="col-12 mt-2">
                    <h4 style="font-size: 0.95rem; margin-bottom: 0.25rem;">Actividades</h4>
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

            @php $telefonos = $data['telefonos']['datos'] ?? []; @endphp
            @if (count($telefonos) > 0)
                <div class="col-12 mt-2">
                    <h4 style="font-size: 0.95rem; margin-bottom: 0.25rem;">Teléfonos Fijos</h4>
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
                    <tbody>
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
            @endif

            @php $telefonosCelulares = $data['telefonosCelulares']['datos'] ?? []; @endphp
            @if (count($telefonosCelulares) > 0)
                <div class="col-12 mt-2">
                    <h4 style="font-size: 0.95rem; margin-bottom: 0.25rem;">Teléfonos Celulares</h4>
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
                    <tbody>
                        @foreach ($telefonosCelulares as $tel)
                            <tr>
                                <td>{{ $tel['area'] ?? '' }}</td>
                                <td>{{ $tel['nro'] ?? '' }}</td>
                                <td>{{ $tel['tel'] ?? '' }}</td>
                                <td>{{ $tel['operador'] ?? '' }}</td>
                                <td style="text-align: center;">{{ ($tel['wsp'] ?? false) ? 'Sí' : 'No' }}</td>
                                <td>{{ $tel['localidad'] ?? '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            @php $automotoresHistorial = $data['bienes']['automotores_historial']['datos'] ?? []; @endphp
            @if (count($automotoresHistorial) > 0)
                <div class="col-12 mt-2">
                    <h4 style="font-size: 0.95rem; margin-bottom: 0.25rem;">Automotores (Historial)</h4>
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
                    <tbody>
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
            @endif

            @php $bancoOpera = $data['sitFinanciera']['bancoOpera']['datos'] ?? []; @endphp
            @if (count($bancoOpera) > 0)
                <div class="col-12 mt-2">
                    <h4 style="font-size: 0.95rem; margin-bottom: 0.25rem;">Situación Financiera - Bancos</h4>
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
                    <tbody>
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
            @endif

            @php $infoBcra = $data['morosidad']['informacionBcra']['datos'] ?? []; @endphp
            @if (count($infoBcra) > 0)
                <div class="col-12 mt-2">
                    <h4 style="font-size: 0.95rem; margin-bottom: 0.25rem;">Información BCRA - Morosidad</h4>
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
                    <tbody>
                        @foreach ($infoBcra as $bcra)
                            <tr>
                                <td style="text-align: center;">{{ isset($bcra['periodo']) ? \Carbon\Carbon::parse($bcra['periodo'])->format('m/Y') : '' }}</td>
                                <td>{{ $bcra['entidad']['entidad'] ?? '' }}</td>
                                <td>{{ $bcra['entidad']['codigoEnt'] ?? '' }}</td>
                                <td>{{ $bcra['entidad']['tipo'] ?? '' }}</td>
                                <td style="text-align: center;">{{ $bcra['situacion'] ?? '' }}</td>
                                <td style="text-align: right;">{{ isset($bcra['prestamo']) ? '$' . number_format($bcra['prestamo'], 2, ',', '.') : '$0,00' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            @php $chequesRechazados = $data['morosidad']['chequesRechazados']['datos'] ?? []; @endphp
            @if (count($chequesRechazados) > 0)
                <div class="col-12 mt-2">
                    <h4 style="font-size: 0.95rem; margin-bottom: 0.25rem;">Cheques Rechazados</h4>
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
                    <tbody>
                        @foreach ($chequesRechazados as $cheque)
                            <tr>
                                <td>{{ $cheque['nroCheque'] ?? '' }}</td>
                                <td>{{ isset($cheque['fechaRechazo']) ? \Carbon\Carbon::parse($cheque['fechaRechazo'])->format('d-m-Y') : '' }}</td>
                                <td>{{ isset($cheque['monto']) ? '$' . number_format($cheque['monto'], 2, ',', '.') : '$0,00' }}</td>
                                <td>{{ $cheque['causal'] ?? '' }}</td>
                                <td>{{ isset($cheque['fechaLevantamiento']) && $cheque['fechaLevantamiento'] ? \Carbon\Carbon::parse($cheque['fechaLevantamiento'])->format('d-m-Y') : 'No levantado' }}</td>
                                <td>{{ $cheque['multa'] ?? '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            @php $variables = $data['variables'] ?? []; @endphp
            @if (isset($variables) && (count($variables['variableAprobada'] ?? []) > 0 || count($variables['variableRechazo'] ?? []) > 0))
                <div class="col-12 mt-2">
                    <h4 style="font-size: 0.95rem; margin-bottom: 0.25rem;">Variables de Evaluación</h4>
                </div>
                @if (count($variables['variableAprobada'] ?? []) > 0)
                    <div class="col-12 mb-2">
                        <strong>Variables Aprobadas:</strong> {{ implode(' | ', $variables['variableAprobada']) }}
                    </div>
                @endif
                @if (count($variables['variableRechazo'] ?? []) > 0)
                    <div class="col-12 mb-2">
                        <strong>Variables de Rechazo:</strong> {{ implode(' | ', $variables['variableRechazo']) }}
                    </div>
                @endif
            @endif
        @else
            <div class="col-12">
                <div class="alert alert-warning">No se encontraron datos de la empresa.</div>
            </div>
        @endif
    </div>
</div>
