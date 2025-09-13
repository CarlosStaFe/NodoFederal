<div class="container mt-4">
    <div class="row">
        <div class="col-12 mb-3">
            <div class="d-flex align-items-center">
                <img src="{{ public_path('assets/img/NF-LOGO.jpg') }}" alt="Nodo Federal Logo"
                    style="height: 80px; margin-right: 15px; display: inline-block; vertical-align: middle;">
                <h1 class="mb-0" style="display: inline-block; vertical-align: middle;">Informe Comercial</h1>
            </div>
        </div>
        <div class="col-12">
            @php $data = $datos['data'] ?? null; @endphp

            @php $p = $data['datosParticulares'] ?? []; @endphp
            <style>
                .table {
                    width: 100% !important;
                }
                
                .table thead th {
                    background-color: #37a395;
                    color: #fff;
                    font-weight: bold;
                    font-size: 0.85em;
                }

                .table tbody td {
                    font-size: 0.60em;
                    border: 1px solid #b5b5b5;
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

                .watermark {
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%) rotate(-40deg);
                    width: 100vw;
                    opacity: 0.20;
                    z-index: 0;
                    color: #3750a3;
                    font-size: 5em;
                    text-align: center;
                    white-space: nowrap;
                    pointer-events: none;
                }
            </style>

            <div class="watermark">Nodo Federal - Tel. 0342 156267364</div>

            {{-- DATOS VERAZ --}}
            @if ($p)
                <table class="table table-bordered">
                    <div class="col-12 mb-3">
                        @if (isset($data['idLog']))
                            <div><strong>ID Log:</strong> {{ $data['idLog'] }}</div>
                        @endif
                    </div>

                    <div class="col-12 mt-3">
                        <h3>Datos Personales</h3>
                    </div>
                    <thead id="datos-personales">
                        <tr>
                            <th style="width: 30%; border: 1px solid #dee2e6;">Apellido y Nombres</th>
                            <th style="width: 15%; border: 1px solid #dee2e6;">CUIL</th>
                            <th style="width: 10%; border: 1px solid #dee2e6;">DNI</th>
                            <th style="width: 10%; border: 1px solid #dee2e6;">Tipo</th>
                            <th style="width: 15%; border: 1px solid #dee2e6;">Fecha Nac.</th>
                            <th style="width: 10%; border: 1px solid #dee2e6;">Edad</th>
                            <th style="width: 10%; border: 1px solid #dee2e6;">Sexo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="border: 1px solid #dee2e6;">{{ $p['apellidoNombre'] ?? '' }}</td>
                            <td style="border: 1px solid #dee2e6;">{{ $p['cuil'] ?? '' }}</td>
                            <td style="border: 1px solid #dee2e6;">{{ $p['dni'] ?? '' }}</td>
                            <td style="border: 1px solid #dee2e6;">{{ $p['tipo'] ?? '' }}</td>
                            <td style="border: 1px solid #dee2e6;">
                                {{ isset($p['fechaNacimiento']) ? \Carbon\Carbon::parse($p['fechaNacimiento'])->format('d-m-Y') : '' }}
                            </td>
                            <td style="border: 1px solid #dee2e6;">{{ $p['edad'] ?? '' }}</td>
                            <td style="border: 1px solid #dee2e6;">{{ $p['sexo'] ?? '' }}</td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-bordered">
                    <thead id="datos-personales">
                        <tr>
                            <th style="width: 15%; border: 1px solid #dee2e6;">Nacionalidad</th>
                            <th style="width: 25%; border: 1px solid #dee2e6;">Localidad</th>
                            <th style="width: 25%; border: 1px solid #dee2e6;">Provincia</th>
                            <th style="width: 10%; border: 1px solid #dee2e6;">Cód.Postal</th>
                            <th style="width: 54%; border: 1px solid #dee2e6;">Domicilio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="border: 1px solid #dee2e6;">{{ $p['nacionalidad'] ?? '' }}</td>
                            <td style="border: 1px solid #dee2e6;">{{ $p['localidad'] ?? '' }}</td>
                            <td style="border: 1px solid #dee2e6;">{{ $p['provincia'] ?? '' }}</td>
                            <td style="border: 1px solid #dee2e6;">{{ $p['cp'] ?? '' }}</td>
                            <td style="border: 1px solid #dee2e6;" colspan="5">{{ $p['domicilio'] ?? '' }}</td>
                        </tr>
                    </tbody>
                </table>
            @endif

            @php $ver = $data['veraz'] ?? []; @endphp
            @if ($ver)
                <div class="col-12 mt-3">
                    <h3>Datos Veraz</h3>
                </div>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 10%;">Categoría</th>
                            <th style="width: 15%;">Documento</th>
                            <th style="width: 10%;">Predictor</th>
                            <th style="width: 25%;">Ingresos</th>
                            <th style="width: 15%;">Score Rango</th>
                            <th style="width: 15%;">Score Segmento</th>
                            <th style="width: 20%;">Score Texto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="text-align: center;">{{ $ver['categoria'] ?? '' }}</td>
                            <td style="text-align: center;">{{ $ver['documento'] ?? '' }}</td>
                            <td style="text-align: center;">{{ $ver['incomePredictor'] ?? '' }}</td>
                            <td style="text-align: center;">{{ $ver['incomePredictorRango'] ?? '' }}</td>
                            <td style="text-align: center;">{{ $ver['scoreRango'] ?? '' }}</td>
                            <td style="text-align: center;">{{ $ver['scoreSegmento'] ?? '' }}</td>
                            <td style="text-align: center;">{{ $ver['scoreTexto'] ?? '' }}</td>
                        </tr>
                    </tbody>
                </table>
            @endif

            {{-- DATOS INFORMACION DEL BCRA DEUDORES CENTRO COMERCIAL --}}
            @php $nodo = $data['morosidad']['deudoresCentroComercial']['datos'] ?? []; @endphp
            @if (!empty($nodo) && is_array($nodo))
                <div class="col-12 mt-3">
                    <h3>Deudores Nodo Federal</h3>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Nodo</th>
                            <th>Socio</th>
                            <th>Tipo</th>
                            <th>Importe</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($nodo as $item)
                            <tr>
                                <td>{{ $item['codDeEstado'] ?? '' }}</td>
                                <td>{{ isset($item['fechaDeAtraso']) ? \Carbon\Carbon::parse($item['fechaDeAtraso'])->format('d-m-Y') : '' }}
                                </td>
                                <td>{{ $item['nombreInstituto'] ?? '' }}</td>
                                <td>{{ $item['nombreSocio'] ?? '' }}</td>
                                <td>{{ $item['tipoDeudor'] ?? '' }}</td>
                                <td>{{ isset($item['deudaTotal']) ? number_format($item['deudaTotal'], 2, ',', '.') : '' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <br>
                <div class="alert alert-info mt-2" style="border: 1px solid #769cc2; background-color: #3374b6; color: #fff; width: 100%;">
                    No se encontraron datos de deudores Nodo Federal.
                </div>
            @endif

            {{-- DATOS LABORALES --}}
            @php $datosLaborales = $data['datosLaborales'] ?? []; @endphp
            @if (isset($datosLaborales['datoLaboral']['datos']) && is_array($datosLaborales['datoLaboral']['datos']))
                <h3>Relaciones de Dependencia</h3>
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
                    <tbody style="font-size: 0.70em; important;">
                        {{-- <tbody style="font-size: 0.70em;"> --- IGNORE --- --}}
                        @foreach ($datosLaborales['datoLaboral']['datos'] as $laboral)
                            <tr>
                                <td>{{ $laboral['relacionDependencia']['estado'] ?? '' }}</td>
                                <td>{{ $laboral['relacionDependencia']['razonSocial'] ?? '' }}</td>
                                <td>{{ $laboral['relacionDependencia']['cuit'] ?? '' }}</td>
                                <td>{{ isset($laboral['relacionDependencia']['alta']) ? \Carbon\Carbon::parse($laboral['relacionDependencia']['alta'])->format('d-m-Y') : '' }}
                                </td>
                                <td>{{ isset($laboral['relacionDependencia']['baja']) ? \Carbon\Carbon::parse($laboral['relacionDependencia']['baja'])->format('d-m-Y') : '' }}
                                </td>
                                <td>{{ $laboral['historialSueldo']['infoAdicionalEmpleador']['actividadempleador'] ?? '' }}
                                </td>
                                <td>{{ $laboral['historialSueldo']['infoAdicionalEmpleador']['cantidadempleados'] ?? '' }}
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
                <br>
                <div class="alert alert-info mt-2" style="border: 1px solid #769cc2; background-color: #3374b6; color: #fff; width: 100%;">
                    No se encontraron datos Laborales.
                </div>
            @endif


            {{-- DATOS MONOTRIBUTISTA --}}
            @php $monos = $data['datosLaborales']['monotributista']['datos'] ?? []; @endphp
            @if (count($monos) > 0)
                <div class="col-12 mt-3">
                    <h3>Monotributo</h3>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Categoría</th>
                            <th>Estado</th>
                            <th>Inicio</th>
                            <th>Hasta</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($monos as $mono)
                            <tr>
                                <td>{{ $mono['tipo'] ?? '' }}</td>
                                <td>{{ $mono['categoria'] ?? '' }}</td>
                                <td>{{ $mono['estado'] ?? '' }}</td>
                                <td>{{ isset($mono['fechaInicio']) ? \Carbon\Carbon::parse($mono['fechaInicio'])->format('d-m-Y') : '' }}
                                </td>
                                <td>{{ isset($mono['fechaHasta']) ? \Carbon\Carbon::parse($mono['fechaHasta'])->format('d-m-Y') : '' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <br>
                <div class="alert alert-info mt-2" style="border: 1px solid #769cc2; background-color: #3374b6; color: #fff; width: 100%;">
                    No se encontraron datos de Monotributistas.
                </div>
            @endif

            {{-- DATOS ACTIVIDAD --}}
            @php $acts = $data['datosLaborales']['actividad']['datos'] ?? []; @endphp
            @if (count($acts) > 0)
                <div class="col-12 mt-3">
                    <h3>Actividad</h3>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Descripción</th>
                            <th>CIIU</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($acts as $act)
                            <tr>
                                <td>{{ $act['descripcion'] ?? '' }}</td>
                                <td>{{ $act['ciiu'] ?? '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <br>
                <div class="alert alert-info mt-2" style="border: 1px solid #769cc2; background-color: #3374b6; color: #fff; width: 100%;">
                    No se encontraron datos de actividad laboral.
                </div>
            @endif

            {{-- DATOS OBRA SOCIAL --}}
            @php $os = $data['datosLaborales']['obraSocial']['datos'] ?? []; @endphp
            @if (count($os) > 0)
                <div class="col-12 mt-3">
                    <h3>Obra Social</h3>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>CUIT</th>
                            <th>Nombre</th>
                            <th>Estado</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($os as $obra)
                            <tr>
                                <td>{{ $obra['cuit'] ?? '' }}</td>
                                <td>{{ $obra['nombre'] ?? '' }}</td>
                                <td>{{ $obra['estado'] ?? '' }}</td>
                                <td>{{ isset($obra['fechaInicio']) ? \Carbon\Carbon::parse($obra['fechaInicio'])->format('d-m-Y') : '' }}
                                </td>
                                <td>{{ isset($obra['fechaFin']) ? \Carbon\Carbon::parse($obra['fechaFin'])->format('d-m-Y') : '' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <br>
                <div class="alert alert-info mt-2" style="border: 1px solid #769cc2; background-color: #3374b6; color: #fff; width: 100%;">
                    No se encontraron datos de Obra Social.
                </div>
            @endif

            {{-- DATOS JUBILACION --}}
            @php $jubs = $data['datosLaborales']['jubilacion']['datos'] ?? []; @endphp
            @if (count($jubs) > 0)
                <div class="col-12 mt-3">
                    <h3>Jubilación</h3>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 30%;">Titular</th>
                            <th style="width: 15%;">Sueldo Bruto</th>
                            <th style="width: 15%;">Sueldo Neto</th>
                            <th style="width: 15%;">Periodo</th>
                            <th style="width: 10%;">Rango</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jubs as $jub)
                            <tr>
                                <td>{{ $jub['titular'] ?? '' }}</td>
                                <td>{{ isset($jub['sueldoBruto']) ? number_format($jub['sueldoBruto'], 2, ',', '.') : '' }}
                                </td>
                                <td>{{ isset($jub['sueldoNeto']) ? number_format($jub['sueldoNeto'], 2, ',', '.') : '' }}
                                </td>
                                <td>{{ isset($jub['periodo']) ? \Carbon\Carbon::parse($jub['periodo'])->format('d-m-Y') : '' }}
                                </td>
                                <td>{{ $jub['rango'] ?? '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <br>
                <div class="alert alert-info mt-2" style="border: 1px solid #769cc2; background-color: #3374b6; color: #fff; width: 100%;">
                    No se encontraron datos de Jubilación.
                </div>
            @endif

            {{-- DATOS AUTOMOTORES --}}
            @php $bo = $data['bienesPersonales']['automotores']['datos'] ?? []; @endphp
            @if (count($bo) > 0)
                <div class="col-12 mt-3">
                    <h3>Automotores</h3>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Dominio</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Año</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($autos as $auto)
                            <tr>
                                <td>{{ $auto['dominio'] ?? '' }}</td>
                                <td>{{ $auto['marca'] ?? '' }}</td>
                                <td>{{ $auto['modelo'] ?? '' }}</td>
                                <td>{{ $auto['anioModelo'] ?? '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <br>
                <div class="alert alert-info mt-2" style="border: 1px solid #769cc2; background-color: #3374b6; color: #fff; width: 100%;">
                    No se encontraron datos de Automotores.
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
            @if (!empty($agrupados) && $agrupados->count())
                <div class="col-12 mt-3">
                    <h3>BCRA Morosidad (Agrupado por Tipo y Entidad)</h3>
                </div>
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
                                        <td>{{ isset($moras['periodo']) ? \Carbon\Carbon::parse($moras['periodo'])->format('m-Y') : '' }}
                                        </td>
                                        <td>{{ $moras['situacion'] ?? '' }}</td>
                                        <td>{{ isset($moras['prestamo']) ? number_format($moras['prestamo'], 2, ',', '.') : '' }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            @else
                <br>
                <div class="alert alert-info mt-2" style="border: 1px solid #769cc2; background-color: #3374b6; color: #fff; width: 100%;">
                    No se encontraron datos de Morosidad.
                </div>
            @endif

            {{-- DATOS INFORMACION DEL BCRA CHEQUES RECHAZADOS --}}
            @php $chqrs = $data['morosidad']['chequesRechazados']['datos'] ?? []; @endphp
            @if (!empty($chqrs) && is_array($chqrs))
                <div class="col-12 mt-3">
                    <h3>BCRA Cheques Rechazados</h3>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Entidad</th>
                            <th>Fecha Rechazo</th>
                            <th>Importe</th>
                            <th>Motivo</th>
                            <th>Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($chqrs as $chq)
                            <tr>
                                <td>{{ $chq['entidad'] ?? '' }}</td>
                                <td>{{ isset($chq['fechaRechazo']) ? \Carbon\Carbon::parse($chq['fechaRechazo'])->format('d-m-Y') : '' }}
                                </td>
                                <td>{{ isset($chq['importe']) ? number_format($chq['importe'], 2, ',', '.') : '' }}
                                </td>
                                <td>{{ $chq['motivo'] ?? '' }}</td>
                                <td>{{ $chq['cantidad'] ?? '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <br>
                <div class="alert alert-info mt-2" style="border: 1px solid #769cc2; background-color: #3374b6; color: #fff; width: 100%;">
                    No se encontraron datos de Cheques Rechazados.
                </div>
            @endif

            {{-- DATOS INFORMACION DEL BCRA DEUDORES BANCO CENTRAL --}}
            @php $deudores = $data['morosidad']['deudoresBancoCentral']['datos'] ?? []; @endphp
            @if (!empty($deudores) && is_array($deudores))
                <div class="col-12 mt-3">
                    <h3>BCRA Deudores Banco Central</h3>
                </div>
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
                <br>
                <div class="alert alert-info mt-2" style="border: 1px solid #769cc2; background-color: #3374b6; color: #fff; width: 100%;">
                    No se encontraron datos de deudores BCRA.
                </div>
            @endif

            <p style="font-size: 0.75em;"><strong>La información suministrada en el presente informe, extraída de bases públicas, privadas y
                    propias, es confidencial y
                    reservada.<br>El cliente es el único habilitado a consultar la información, estando
                    expresamente prohibida la divulgación de la misma a
                    terceros —total o parcialmente— observando el deber de confidencialidad y uso permitido,
                    según Ley 25.236.</strong></p>
        </div>
    </div>
</div>
