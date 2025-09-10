@extends('layouts.admin')

@section('content')

<div class="col-md-12">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="card-title mb-0">Informe de Antecedentes</h1>
                @if(isset($datos) && isset($datos['data']))
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
                            @if(isset($data['idLog']))
                                <div><strong>ID Log:</strong> {{ $data['idLog'] }}</div>
                            @endif
                        </div>

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
                                    <td>{{ isset($p['fechaNacimiento']) ? \Carbon\Carbon::parse($p['fechaNacimiento'])->format('d-m-Y') : '' }}</td>
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

                        @php $ver = $data['veraz'] ?? []; @endphp
                        @if($ver)
                            <div class="col-12 mt-3">
                                <h3>Datos Veraz</h3>
                            </div>

                            <div class="row">
                                <div class="col-md-6 d-flex align-items-center">
                                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem;">
                                        <div style="display: flex; min-width: 170px;">
                                            @if(($ver['scoreRango'] ?? '') === 1)
                                                <div
                                                    style="position: relative; height: 20px; width: 20%; line-height: 1; display: flex; align-items: center; justify-content: center; background-color: rgb(255, 38, 42); color: rgb(255, 255, 255); transform: scale(1.6, 1.6); box-shadow: rgba(0, 0, 0, 0.2) 0px 0px 5px 1px; border-radius: 2px; opacity: 1; z-index: 2;">
                                                    <strong style="padding: 1rem; font-size: 7px; text-align: center;">MUY BAJO</strong>
                                                </div>
                                            @else
                                                <div
                                                    style="position: relative; height: 20px; width: 20%; line-height: 1; display: flex; align-items: center; justify-content: center; background-color: rgb(255, 38, 42); color: rgb(255, 255, 255); opacity: 0.5; z-index: 1;">
                                                    <strong style="padding: 1rem; font-size: 7px; text-align: center;" hidden>MUY BAJO</strong>
                                                </div>
                                            @endif
                                            @if(($ver['scoreRango'] ?? '') === 2)
                                                <div
                                                    style="position: relative; height: 20px; width: 20%; line-height: 1; display: flex; align-items: center; justify-content: center; background-color: rgb(255, 182, 7); color: rgb(0, 0, 0); transform: scale(1.6, 1.6); box-shadow: rgba(0, 0, 0, 0.2) 0px 0px 5px 1px; border-radius: 2px; opacity: 1; z-index: 2;">
                                                    <strong style="padding: 1rem; font-size: 7px; text-align: center;">BAJO</strong>
                                                </div>
                                            @else
                                                <div
                                                    style="position: relative; height: 20px; width: 20%; line-height: 1; display: flex; align-items: center; justify-content: center; background-color: rgb(255, 182, 7); color: rgb(0, 0, 0); opacity: 0.5; z-index: 1;">
                                                    <strong style="padding: 1rem; font-size: 7px; text-align: center;" hidden>BAJO</strong>
                                                </div>
                                            @endif
                                            @if(($ver['scoreRango'] ?? '') === 3)
                                                <div
                                                    style="position: relative; height: 20px; width: 20%; line-height: 1; display: flex; align-items: center; justify-content: center; background-color: rgb(255, 255, 47); color: rgb(0, 0, 0); transform: scale(1.6, 1.6); box-shadow: rgba(0, 0, 0, 0.2) 0px 0px 5px 1px; border-radius: 2px; opacity: 1; z-index: 2;">
                                                    <strong style="padding: 1rem; font-size: 7px; text-align: center;">MEDIO</strong>
                                                </div>
                                            @else
                                                <div
                                                    style="position: relative; height: 20px; width: 20%; line-height: 1; display: flex; align-items: center; justify-content: center; background-color: rgb(255, 255, 47); color: rgb(0, 0, 0); opacity: 0.5; z-index: 1;">
                                                    <strong style="padding: 1rem; font-size: 7px; text-align: center;" hidden>MEDIO</strong>
                                                </div>
                                            @endif
                                            @if(($ver['scoreRango'] ?? '') === 4)
                                                <div
                                                    style="position: relative; height: 20px; width: 20%; line-height: 1; display: flex; align-items: center; justify-content: center; background-color: rgb(0, 255, 85); color: rgb(0, 0, 0); transform: scale(1.6, 1.6); box-shadow: rgba(0, 0, 0, 0.2) 0px 0px 5px 1px; border-radius: 2px; opacity: 1; z-index: 2;">
                                                    <strong style="padding: 1rem; font-size: 7px; text-align: center;">ALTO</strong>
                                                </div>
                                            @else
                                                <div
                                                    style="position: relative; height: 20px; width: 20%; line-height: 1; display: flex; align-items: center; justify-content: center; background-color: rgb(0, 255, 85); color: rgb(0, 0, 0); opacity: 0.5; z-index: 1;">
                                                    <strong style="padding: 1rem; font-size: 7px; text-align: center;" hidden>ALTO</strong>
                                                </div>
                                            @endif
                                            @if(($ver['scoreRango'] ?? '') === 5)
                                                <div
                                                    style="position: relative; height: 20px; width: 20%; line-height: 1; display: flex; align-items: center; justify-content: center; background-color: rgb(0, 188, 42); color: rgb(0, 0, 0); transform: scale(1.6, 1.6); box-shadow: rgba(0, 0, 0, 0.2) 0px 0px 5px 1px; border-radius: 2px; opacity: 1; z-index: 2;">
                                                    <strong style="padding: 1rem; font-size: 7px; text-align: center;">MUY ALTO</strong>
                                                </div>
                                            @else
                                                <div
                                                    style="position: relative; height: 20px; width: 20%; line-height: 1; display: flex; align-items: center; justify-content: center; background-color: rgb(0, 188, 42); color: rgb(0, 0, 0); opacity: 0.5; z-index: 1;">
                                                    <strong style="padding: 1rem; font-size: 7px; text-align: center;" hidden>MUY ALTO</strong>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            @if(($ver['scoreRango'] ?? '') === 5)
                                                <span><b>MUY ALTA</b> probabilidad de cumplir con obligaciones crediticias</span>
                                            @endif
                                            @if(($ver['scoreRango'] ?? '') === 4)
                                                <span><b>ALTA</b> probabilidad de cumplir con obligaciones crediticias</span>
                                            @endif
                                            @if(($ver['scoreRango'] ?? '') === 3)
                                                <span><b>MEDIA</b> probabilidad de cumplir con obligaciones crediticias</span>
                                            @endif
                                            @if(($ver['scoreRango'] ?? '') === 2)
                                                <span><b>BAJA</b> probabilidad de cumplir con obligaciones crediticias</span>
                                            @endif
                                            @if(($ver['scoreRango'] ?? '') === 1)
                                                <span><b>MUY BAJA</b> probabilidad de cumplir con obligaciones crediticias</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex align-items-center">
                                    <div class="income-indicator w-100">
                                        <div class="pull-right" style="min-width: 170px;">
                                            <div style="display: flex; height: 24px; width: 100%; position: relative; margin: 2px 0px;">
                                                <div style="width: 80%; height: 100%; background-color: rgb(130, 166, 82); padding-left: 1rem; line-height: 24px; color: rgb(255, 255, 255);">
                                                    <strong>R1</strong>
                                                    @if(($ver['incomePredictor'] ?? '') === 'R1')
                                                        <div style="position: absolute; right: calc(100% + 2px); top: 4px;">
                                                            <div style="width: 0px; height: 0px; border-top: 8px solid transparent; border-bottom: 8px solid transparent; border-left: 8px solid rgba(0, 0, 0, 0.8);"></div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div style="width: 0px; height: 0px; border-style: solid; border-width: 12px 0px 12px 24px; border-color: transparent transparent transparent rgb(130, 166, 82);"></div>
                                            </div>
                                            <div style="display: flex; height: 24px; width: 88.8889%; position: relative; margin: 2px 0px;">
                                                <div style="width: 80%; height: 100%; background-color: rgb(211, 214, 83); padding-left: 1rem; line-height: 24px; color: rgb(0, 0, 0);">
                                                    <strong>R2</strong>
                                                    @if(($ver['incomePredictor'] ?? '') === 'R2')
                                                        <div style="position: absolute; right: calc(100% + 2px); top: 4px;">
                                                            <div style="width: 0px; height: 0px; border-top: 8px solid transparent; border-bottom: 8px solid transparent; border-left: 8px solid rgba(0, 0, 0, 0.8);"></div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div style="width: 0px; height: 0px; border-style: solid; border-width: 12px 0px 12px 24px; border-color: transparent transparent transparent rgb(211, 214, 83);"></div>
                                            </div>
                                            <div style="display: flex; height: 24px; width: 77.7778%; position: relative; margin: 2px 0px;">
                                                <div style="width: 80%; height: 100%; background-color: rgb(243, 236, 58); padding-left: 1rem; line-height: 24px; color: rgb(0, 0, 0);">
                                                    <strong>R3</strong>
                                                    @if(($ver['incomePredictor'] ?? '') === 'R3')
                                                        <div style="position: absolute; right: calc(100% + 2px); top: 4px;">
                                                            <div style="width: 0px; height: 0px; border-top: 8px solid transparent; border-bottom: 8px solid transparent; border-left: 8px solid rgba(0, 0, 0, 0.8);"></div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div style="width: 0px; height: 0px; border-style: solid; border-width: 12px 0px 12px 24px; border-color: transparent transparent transparent rgb(243, 236, 58);"></div>
                                            </div>
                                            <div style="display: flex; height: 24px; width: 66.6667%; position: relative; margin: 2px 0px;">
                                                <div style="width: 80%; height: 100%; background-color: rgb(238, 189, 94); padding-left: 1rem; line-height: 24px; color: rgb(0, 0, 0);">
                                                    <strong>R4</strong>
                                                    @if(($ver['incomePredictor'] ?? '') === 'R4')
                                                        <div style="position: absolute; right: calc(100% + 2px); top: 4px;">
                                                            <div style="width: 0px; height: 0px; border-top: 8px solid transparent; border-bottom: 8px solid transparent; border-left: 8px solid rgba(0, 0, 0, 0.8);"></div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div style="width: 0px; height: 0px; border-style: solid; border-width: 12px 0px 12px 24px; border-color: transparent transparent transparent rgb(238, 189, 94);"></div>
                                            </div>
                                            <div style="display: flex; height: 24px; width: 55.5556%; position: relative; margin: 2px 0px;">
                                                <div style="width: 80%; height: 100%; background-color: rgb(226, 165, 74); padding-left: 1rem; line-height: 24px; color: rgb(0, 0, 0);">
                                                    <strong>R5</strong>
                                                    @if(($ver['incomePredictor'] ?? '') === 'R5')
                                                        <div style="position: absolute; right: calc(100% + 2px); top: 4px;">
                                                            <div style="width: 0px; height: 0px; border-top: 8px solid transparent; border-bottom: 8px solid transparent; border-left: 8px solid rgba(0, 0, 0, 0.8);"></div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div style="width: 0px; height: 0px; border-style: solid; border-width: 12px 0px 12px 24px; border-color: transparent transparent transparent rgb(226, 165, 74);"></div>
                                            </div>
                                            <div style="display: flex; height: 24px; width: 44.4444%; position: relative; margin: 2px 0px;">
                                                <div style="width: 80%; height: 100%; background-color: rgb(215, 112, 61); padding-left: 1rem; line-height: 24px; color: rgb(255, 255, 255);">
                                                    <strong>R6</strong>
                                                    @if(($ver['incomePredictor'] ?? '') === 'R6')
                                                        <div style="position: absolute; right: calc(100% + 2px); top: 4px;">
                                                            <div style="width: 0px; height: 0px; border-top: 8px solid transparent; border-bottom: 8px solid transparent; border-left: 8px solid rgba(0, 0, 0, 0.8);"></div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div style="width: 0px; height: 0px; border-style: solid; border-width: 12px 0px 12px 24px; border-color: transparent transparent transparent rgb(215, 112, 61);"></div>
                                            </div>
                                            <div style="display: flex; height: 24px; width: 33.3333%; position: relative; margin: 2px 0px;">
                                                <div style="width: 80%; height: 100%; background-color: rgb(210, 85, 55); padding-left: 1rem; line-height: 24px; color: rgb(255, 255, 255);">
                                                    <strong>R7</strong>
                                                    @if(($ver['incomePredictor'] ?? '') === 'R7')
                                                        <div style="position: absolute; right: calc(100% + 2px); top: 4px;">
                                                            <div style="width: 0px; height: 0px; border-top: 8px solid transparent; border-bottom: 8px solid transparent; border-left: 8px solid rgba(0, 0, 0, 0.8);"></div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div style="width: 0px; height: 0px; border-style: solid; border-width: 12px 0px 12px 24px; border-color: transparent transparent transparent rgb(210, 85, 55);"></div>
                                            </div>
                                            <div style="display: flex; height: 24px; width: 22.2222%; position: relative; margin: 2px 0px;">
                                                <div style="width: 80%; height: 100%; background-color: rgb(182, 57, 81); padding-left: 1rem; line-height: 24px; color: rgb(255, 255, 255);">
                                                    <strong>R8</strong>
                                                    @if(($ver['incomePredictor'] ?? '') === 'R8')
                                                        <div style="position: absolute; right: calc(100% + 2px); top: 4px;">
                                                            <div style="width: 0px; height: 0px; border-top: 8px solid transparent; border-bottom: 8px solid transparent; border-left: 8px solid rgba(0, 0, 0, 0.8);"></div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div style="width: 0px; height: 0px; border-style: solid; border-width: 12px 0px 12px 24px; border-color: transparent transparent transparent rgb(182, 57, 81);"></div>
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
                                            <td>{{ $mono['fechaInicio'] ?? '' }}</td>
                                            <td>{{ $mono['fechaHasta'] ?? '' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif

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
                        @endif

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
                                            <td>{{ isset($jub['sueldoBruto']) ? number_format($jub['sueldoBruto'], 2, ',', '.') : '' }}</td>
                                            <td>{{ isset($jub['sueldoNeto']) ? number_format($jub['sueldoNeto'], 2, ',', '.') : '' }}</td>
                                            <td>{{ isset($jub['periodo']) ? \Carbon\Carbon::parse($jub['periodo'])->format('d-m-Y') : '' }}</td>
                                            <td>{{ $jub['rango'] ?? '' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                        @php $autos = $data['bienesPersonales']['automotores_historial']['datos'] ?? []; @endphp
                        @if (count($autos) > 0)
                            <div class="col-12 mt-3">
                                <h3>Automotores Historial</h3>
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
                        @endif
                        @php $bcra = $data['situacionFinanciera']['informacionBcra']['datos'] ?? []; @endphp
                        @if(count($bcra) > 0)
                            <div class="col-12 mt-3">
                                <h3>Situación Financiera (BCRA)</h3>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Entidad</th>
                                        <th>Tipo</th>
                                        <th>Situación</th>
                                        <th>Periodo</th>
                                        <th>Préstamo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bcra as $b)
                                        <tr>
                                            <td>{{ $b['entidad']['entidad'] ?? '' }}</td>
                                            <td>{{ $b['entidad']['tipo'] ?? '' }}</td>
                                            <td>{{ $b['situacion'] ?? '' }}</td>
                                            <td>{{ $b['periodo'] ?? '' }}</td>
                                            <td>{{ $b['prestamo'] ?? '' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="alert alert-info">No hay datos de situación financiera BCRA.</div>
                        @endif
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
 
 