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
                                            <th>Fecha Nacimiento</th>
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
                                    <div class="col-md-6 d-flex align-items-center">
                                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem;">
                                            <div style="display: flex; min-width: 170px;">
                                                @if (($ver['scoreRango'] ?? '') === 1)
                                                    <div
                                                        style="position: relative; height: 20px; width: 20%; line-height: 1; display: flex; align-items: center; justify-content: center; background-color: rgb(255, 38, 42); color: rgb(255, 255, 255); transform: scale(1.6, 1.6); box-shadow: rgba(0, 0, 0, 0.2) 0px 0px 5px 1px; border-radius: 2px; opacity: 1; z-index: 2;">
                                                        <strong
                                                            style="padding: 1rem; font-size: 7px; text-align: center;">MUY
                                                            BAJO</strong>
                                                    </div>
                                                @else
                                                    <div
                                                        style="position: relative; height: 20px; width: 20%; line-height: 1; display: flex; align-items: center; justify-content: center; background-color: rgb(255, 38, 42); color: rgb(255, 255, 255); opacity: 0.5; z-index: 1;">
                                                        <strong style="padding: 1rem; font-size: 7px; text-align: center;"
                                                            hidden>MUY BAJO</strong>
                                                    </div>
                                                @endif
                                                @if (($ver['scoreRango'] ?? '') === 2)
                                                    <div
                                                        style="position: relative; height: 20px; width: 20%; line-height: 1; display: flex; align-items: center; justify-content: center; background-color: rgb(255, 182, 7); color: rgb(0, 0, 0); transform: scale(1.6, 1.6); box-shadow: rgba(0, 0, 0, 0.2) 0px 0px 5px 1px; border-radius: 2px; opacity: 1; z-index: 2;">
                                                        <strong
                                                            style="padding: 1rem; font-size: 7px; text-align: center;">BAJO</strong>
                                                    </div>
                                                @else
                                                    <div
                                                        style="position: relative; height: 20px; width: 20%; line-height: 1; display: flex; align-items: center; justify-content: center; background-color: rgb(255, 182, 7); color: rgb(0, 0, 0); opacity: 0.5; z-index: 1;">
                                                        <strong style="padding: 1rem; font-size: 7px; text-align: center;"
                                                            hidden>BAJO</strong>
                                                    </div>
                                                @endif
                                                @if (($ver['scoreRango'] ?? '') === 3)
                                                    <div
                                                        style="position: relative; height: 20px; width: 20%; line-height: 1; display: flex; align-items: center; justify-content: center; background-color: rgb(255, 255, 47); color: rgb(0, 0, 0); transform: scale(1.6, 1.6); box-shadow: rgba(0, 0, 0, 0.2) 0px 0px 5px 1px; border-radius: 2px; opacity: 1; z-index: 2;">
                                                        <strong
                                                            style="padding: 1rem; font-size: 7px; text-align: center;">MEDIO</strong>
                                                    </div>
                                                @else
                                                    <div
                                                        style="position: relative; height: 20px; width: 20%; line-height: 1; display: flex; align-items: center; justify-content: center; background-color: rgb(255, 255, 47); color: rgb(0, 0, 0); opacity: 0.5; z-index: 1;">
                                                        <strong style="padding: 1rem; font-size: 7px; text-align: center;"
                                                            hidden>MEDIO</strong>
                                                    </div>
                                                @endif
                                                @if (($ver['scoreRango'] ?? '') === 4)
                                                    <div
                                                        style="position: relative; height: 20px; width: 20%; line-height: 1; display: flex; align-items: center; justify-content: center; background-color: rgb(0, 255, 85); color: rgb(0, 0, 0); transform: scale(1.6, 1.6); box-shadow: rgba(0, 0, 0, 0.2) 0px 0px 5px 1px; border-radius: 2px; opacity: 1; z-index: 2;">
                                                        <strong
                                                            style="padding: 1rem; font-size: 7px; text-align: center;">ALTO</strong>
                                                    </div>
                                                @else
                                                    <div
                                                        style="position: relative; height: 20px; width: 20%; line-height: 1; display: flex; align-items: center; justify-content: center; background-color: rgb(0, 255, 85); color: rgb(0, 0, 0); opacity: 0.5; z-index: 1;">
                                                        <strong style="padding: 1rem; font-size: 7px; text-align: center;"
                                                            hidden>ALTO</strong>
                                                    </div>
                                                @endif
                                                @if (($ver['scoreRango'] ?? '') === 5)
                                                    <div
                                                        style="position: relative; height: 20px; width: 20%; line-height: 1; display: flex; align-items: center; justify-content: center; background-color: rgb(0, 188, 42); color: rgb(0, 0, 0); transform: scale(1.6, 1.6); box-shadow: rgba(0, 0, 0, 0.2) 0px 0px 5px 1px; border-radius: 2px; opacity: 1; z-index: 2;">
                                                        <strong
                                                            style="padding: 1rem; font-size: 7px; text-align: center;">MUY
                                                            ALTO</strong>
                                                    </div>
                                                @else
                                                    <div
                                                        style="position: relative; height: 20px; width: 20%; line-height: 1; display: flex; align-items: center; justify-content: center; background-color: rgb(0, 188, 42); color: rgb(0, 0, 0); opacity: 0.5; z-index: 1;">
                                                        <strong style="padding: 1rem; font-size: 7px; text-align: center;"
                                                            hidden>MUY ALTO</strong>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                @if (($ver['scoreRango'] ?? '') === 5)
                                                    <span><b>MUY ALTA</b> probabilidad de cumplir con obligaciones
                                                        crediticias</span>
                                                @endif
                                                @if (($ver['scoreRango'] ?? '') === 4)
                                                    <span><b>ALTA</b> probabilidad de cumplir con obligaciones
                                                        crediticias</span>
                                                @endif
                                                @if (($ver['scoreRango'] ?? '') === 3)
                                                    <span><b>MEDIA</b> probabilidad de cumplir con obligaciones
                                                        crediticias</span>
                                                @endif
                                                @if (($ver['scoreRango'] ?? '') === 2)
                                                    <span><b>BAJA</b> probabilidad de cumplir con obligaciones
                                                        crediticias</span>
                                                @endif
                                                @if (($ver['scoreRango'] ?? '') === 1)
                                                    <span><b>MUY BAJA</b> probabilidad de cumplir con obligaciones
                                                        crediticias</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 d-flex align-items-center">
                                        <div class="income-indicator w-100">
                                            <div class="pull-right" style="min-width: 170px;">
                                                <div
                                                    style="display: flex; height: 24px; width: 100%; position: relative; margin: 2px 0px;">
                                                    <div
                                                        style="width: 80%; height: 100%; background-color: rgb(130, 166, 82); padding-left: 1rem; line-height: 24px; color: rgb(255, 255, 255);">
                                                        <strong>R1</strong>
                                                        @if (($ver['incomePredictor'] ?? '') === 'R1')
                                                            <div
                                                                style="position: absolute; right: calc(100% + 2px); top: 4px;">
                                                                <div
                                                                    style="width: 0px; height: 0px; border-top: 8px solid transparent; border-bottom: 8px solid transparent; border-left: 8px solid rgba(0, 0, 0, 0.8);">
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div
                                                        style="width: 0px; height: 0px; border-style: solid; border-width: 12px 0px 12px 24px; border-color: transparent transparent transparent rgb(130, 166, 82);">
                                                    </div>
                                                </div>
                                                <div
                                                    style="display: flex; height: 24px; width: 88.8889%; position: relative; margin: 2px 0px;">
                                                    <div
                                                        style="width: 80%; height: 100%; background-color: rgb(211, 214, 83); padding-left: 1rem; line-height: 24px; color: rgb(0, 0, 0);">
                                                        <strong>R2</strong>
                                                        @if (($ver['incomePredictor'] ?? '') === 'R2')
                                                            <div
                                                                style="position: absolute; right: calc(100% + 2px); top: 4px;">
                                                                <div
                                                                    style="width: 0px; height: 0px; border-top: 8px solid transparent; border-bottom: 8px solid transparent; border-left: 8px solid rgba(0, 0, 0, 0.8);">
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div
                                                        style="width: 0px; height: 0px; border-style: solid; border-width: 12px 0px 12px 24px; border-color: transparent transparent transparent rgb(211, 214, 83);">
                                                    </div>
                                                </div>
                                                <div
                                                    style="display: flex; height: 24px; width: 77.7778%; position: relative; margin: 2px 0px;">
                                                    <div
                                                        style="width: 80%; height: 100%; background-color: rgb(243, 236, 58); padding-left: 1rem; line-height: 24px; color: rgb(0, 0, 0);">
                                                        <strong>R3</strong>
                                                        @if (($ver['incomePredictor'] ?? '') === 'R3')
                                                            <div
                                                                style="position: absolute; right: calc(100% + 2px); top: 4px;">
                                                                <div
                                                                    style="width: 0px; height: 0px; border-top: 8px solid transparent; border-bottom: 8px solid transparent; border-left: 8px solid rgba(0, 0, 0, 0.8);">
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div
                                                        style="width: 0px; height: 0px; border-style: solid; border-width: 12px 0px 12px 24px; border-color: transparent transparent transparent rgb(243, 236, 58);">
                                                    </div>
                                                </div>
                                                <div
                                                    style="display: flex; height: 24px; width: 66.6667%; position: relative; margin: 2px 0px;">
                                                    <div
                                                        style="width: 80%; height: 100%; background-color: rgb(238, 189, 94); padding-left: 1rem; line-height: 24px; color: rgb(0, 0, 0);">
                                                        <strong>R4</strong>
                                                        @if (($ver['incomePredictor'] ?? '') === 'R4')
                                                            <div
                                                                style="position: absolute; right: calc(100% + 2px); top: 4px;">
                                                                <div
                                                                    style="width: 0px; height: 0px; border-top: 8px solid transparent; border-bottom: 8px solid transparent; border-left: 8px solid rgba(0, 0, 0, 0.8);">
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div
                                                        style="width: 0px; height: 0px; border-style: solid; border-width: 12px 0px 12px 24px; border-color: transparent transparent transparent rgb(238, 189, 94);">
                                                    </div>
                                                </div>
                                                <div
                                                    style="display: flex; height: 24px; width: 55.5556%; position: relative; margin: 2px 0px;">
                                                    <div
                                                        style="width: 80%; height: 100%; background-color: rgb(226, 165, 74); padding-left: 1rem; line-height: 24px; color: rgb(0, 0, 0);">
                                                        <strong>R5</strong>
                                                        @if (($ver['incomePredictor'] ?? '') === 'R5')
                                                            <div
                                                                style="position: absolute; right: calc(100% + 2px); top: 4px;">
                                                                <div
                                                                    style="width: 0px; height: 0px; border-top: 8px solid transparent; border-bottom: 8px solid transparent; border-left: 8px solid rgba(0, 0, 0, 0.8);">
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div
                                                        style="width: 0px; height: 0px; border-style: solid; border-width: 12px 0px 12px 24px; border-color: transparent transparent transparent rgb(226, 165, 74);">
                                                    </div>
                                                </div>
                                                <div
                                                    style="display: flex; height: 24px; width: 44.4444%; position: relative; margin: 2px 0px;">
                                                    <div
                                                        style="width: 80%; height: 100%; background-color: rgb(215, 112, 61); padding-left: 1rem; line-height: 24px; color: rgb(255, 255, 255);">
                                                        <strong>R6</strong>
                                                        @if (($ver['incomePredictor'] ?? '') === 'R6')
                                                            <div
                                                                style="position: absolute; right: calc(100% + 2px); top: 4px;">
                                                                <div
                                                                    style="width: 0px; height: 0px; border-top: 8px solid transparent; border-bottom: 8px solid transparent; border-left: 8px solid rgba(0, 0, 0, 0.8);">
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div
                                                        style="width: 0px; height: 0px; border-style: solid; border-width: 12px 0px 12px 24px; border-color: transparent transparent transparent rgb(215, 112, 61);">
                                                    </div>
                                                </div>
                                                <div
                                                    style="display: flex; height: 24px; width: 33.3333%; position: relative; margin: 2px 0px;">
                                                    <div
                                                        style="width: 80%; height: 100%; background-color: rgb(210, 85, 55); padding-left: 1rem; line-height: 24px; color: rgb(255, 255, 255);">
                                                        <strong>R7</strong>
                                                        @if (($ver['incomePredictor'] ?? '') === 'R7')
                                                            <div
                                                                style="position: absolute; right: calc(100% + 2px); top: 4px;">
                                                                <div
                                                                    style="width: 0px; height: 0px; border-top: 8px solid transparent; border-bottom: 8px solid transparent; border-left: 8px solid rgba(0, 0, 0, 0.8);">
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div
                                                        style="width: 0px; height: 0px; border-style: solid; border-width: 12px 0px 12px 24px; border-color: transparent transparent transparent rgb(210, 85, 55);">
                                                    </div>
                                                </div>
                                                <div
                                                    style="display: flex; height: 24px; width: 22.2222%; position: relative; margin: 2px 0px;">
                                                    <div
                                                        style="width: 80%; height: 100%; background-color: rgb(182, 57, 81); padding-left: 1rem; line-height: 24px; color: rgb(255, 255, 255);">
                                                        <strong>R8</strong>
                                                        @if (($ver['incomePredictor'] ?? '') === 'R8')
                                                            <div
                                                                style="position: absolute; right: calc(100% + 2px); top: 4px;">
                                                                <div
                                                                    style="width: 0px; height: 0px; border-top: 8px solid transparent; border-bottom: 8px solid transparent; border-left: 8px solid rgba(0, 0, 0, 0.8);">
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div
                                                        style="width: 0px; height: 0px; border-style: solid; border-width: 12px 0px 12px 24px; border-color: transparent transparent transparent rgb(182, 57, 81);">
                                                    </div>
                                                </div>
                                            </div>
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

                            {{-- DATOS INFORMACION DE AGILDATA --}}
                            @php $nodo = $data['morosidad']['deudoresCentroComercial']['datos'] ?? []; @endphp
                            <div class="col-12 mt-3">
                                <h3>Deudores Nodo Federal</h3>
                            </div>
                            @if (!empty($nodo) && is_array($nodo))
                                @if (count($nodo) > 0)
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
                                    <div class="alert alert-info mt-2">
                                        No se encontraron datos de deudores Nodo Federal.
                                    </div>
                                @endif
                            @endif

                            {{-- DATOS LABORALES --}}
                            @php $datosLaborales = $data['datosLaborales'] ?? []; @endphp
                            <div class="col-12 mt-3">
                                <h3>Antecedentes Laborales</h3>
                            </div>
                            @if (isset($datosLaborales['datoLaboral']['datos']) && is_array($datosLaborales['datoLaboral']['datos']))
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
                                <div class="alert alert-info mt-2">
                                    No se encontraron datos Laborales.
                                </div>
                            @endif

                            {{-- JUBILACION --}}
                            @php $jubilacion = $data['datosLaborales']['jubilacion']['datos'] ?? []; @endphp
                            <div class="col-12 mt-3">
                                <h3>Jubilación</h3>
                            </div>
                            @if (count($jubilacion) > 0)
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Titular</th>
                                            <th>Sueldo Bruto</th>
                                            <th>Sueldo Neto</th>
                                            <th>Periodo</th>
                                            <th>Rango</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($jubilacion as $jub)
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
                                <div class="alert alert-info mt-2">
                                    No se encontraron datos de Jubilación.
                                </div>
                            @endif

                            {{-- DATOS MONOTRIBUTISTA --}}
                            @php $monos = $data['datosLaborales']['monotributista']['datos'] ?? []; @endphp
                            <div class="col-12 mt-3">
                                <h3>Autónomo o Monotributo</h3>
                            </div>
                            @if (count($monos) > 0)
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
                                <div class="alert alert-info mt-2">
                                    No se encontraron datos de Monotributistas.
                                </div>
                            @endif

                            {{-- DATOS ACTIVIDAD --}}
                            @php $acts = $data['datosLaborales']['actividad']['datos'] ?? []; @endphp
                            <div class="col-12 mt-3">
                                <h3>Actividad</h3>
                            </div>
                            @if (count($acts) > 0)
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
                                <div class="alert alert-info mt-2">
                                    No se encontraron datos de actividad laboral.
                                </div>
                            @endif

                            {{-- DATOS OBRA SOCIAL --}}
                            @php $os = $data['datosLaborales']['obraSocial']['datos'] ?? []; @endphp
                            <div class="col-12 mt-3">
                                <h3>Obra Social</h3>
                            </div>
                            @if (count($os) > 0)
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
                                <div class="alert alert-info mt-2">
                                    No se encontraron datos de Obra Social.
                                </div>
                            @endif

                            {{-- DATOS AUTOMOTORES --}}
                            @php
                                $automotores = $data['bienesPersonales']['automotores']['datos'] ?? [];
                                $automotoresTotal = $data['bienesPersonales']['automotores']['cantTotal'] ?? 0;
                                $automotoresHistorial = $data['bienesPersonales']['automotores_historial']['datos'] ?? [];
                                $automotoresHistorialTotal = $data['bienesPersonales']['automotores_historial']['cantTotal'] ?? 0;
                                $autosembargos = $data['bienesPersonales']['autosembargos']['datos'] ?? [];
                                $autosembargosTotal = $data['bienesPersonales']['autosembargos']['cantTotal'] ?? 0;
                            @endphp
                            <div class="col-12 mt-3">
                                <h3>Automotores</h3>
                            </div>
                            @if (count($automotores) > 0)
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
                                            <th>CUIL</th>
                                            <th>DNI</th>
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
                                                <td>{{ $auto['cuil'] ?? '' }}</td>
                                                <td>{{ $auto['dni'] ?? '' }}</td>
                                                <td>{{ isset($auto['compra']) ? \Carbon\Carbon::parse($auto['compra'])->format('d-m-Y') : '' }}</td>
                                                <td>{{ isset($auto['tramite']) ? \Carbon\Carbon::parse($auto['tramite'])->format('d-m-Y') : '' }}</td>
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
                            @if (count($automotoresHistorial) > 0)
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
                                            <th>CUIL</th>
                                            <th>DNI</th>
                                            <th>Compra</th>
                                            <th>Trámite</th>
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
                                                <td>{{ $auto['porcentaje'] ?? '' }}</td>
                                                <td>{{ $auto['cuil'] ?? '' }}</td>
                                                <td>{{ $auto['dni'] ?? '' }}</td>
                                                <td>{{ isset($auto['compra']) ? \Carbon\Carbon::parse($auto['compra'])->format('d-m-Y') : '' }}</td>
                                                <td>{{ isset($auto['tramite']) ? \Carbon\Carbon::parse($auto['tramite'])->format('d-m-Y') : '' }}</td>
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
                            @if (count($autosembargos) > 0)
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Datos</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($autosembargos as $embargo)
                                            <tr>
                                                <td>{{ json_encode($embargo) }}</td>
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
                                <p style="font-size: 0.70em;"><strong>Referencias:</strong></p>
                                <p style="font-size: 0.70em;">
                                    <b>0:</b> Sin información en BCRA -
                                    <b>1:</b> Situación normal (pago puntual o atrasos menores a 31 días) -
                                    <b>2:</b> Con riesgo potencial (con atrasos entre 31 y 90 días) -
                                    <b>3:</b> Cumplimiento deficiente (con atrasos entre 90 y 180 días) -
                                    <b>4:</b> Con alto riesgo de insolvencia (con atrasos entre 180 y 1 año) -
                                    <b>5:</b> Irrecuperable (con atrasos mayores a 1 año) -
                                    <b>6:</b> Irrecuperable por disposición técnica (entidades liquidadas, en proceso de disolución o en quiebra, en gestión judicial)
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
                            @if (count($boletin) > 0)
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
                            @if (count($menciones) > 0)
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

                            <p><strong>La información suministrada en el presente informe, extraída de bases públicas, privadas y
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
