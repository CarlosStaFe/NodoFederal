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
                @php $p = $data['datosParticulares'] ?? null; @endphp
                @if ($p)
                    <form class="row g-3 mt-4">

                        {{-- <div id="chart"></div> --}}
                        <style>
                            .table thead th {
                                background-color: #37a395;
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
                        <table class="table table-bordered">
                            <div class="col-12 mt-3">
                                <h3>Datos Personales</h3>
                            </div>
                            <tbody>
                                <thead>
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
                                <thead>
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
                                        <th>Titular</th>
                                        <th>Sueldo Bruto</th>
                                        <th>Sueldo Neto</th>
                                        <th>Periodo</th>
                                        <th>Rango</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($jubs as $jub)
                                        <tr>
                                            <td>{{ $jub['titular'] ?? '' }}</td>
                                            <td>{{ $jub['sueldoBruto'] ?? '' }}</td>
                                            <td>{{ $jub['sueldoNeto'] ?? '' }}</td>
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

<script>

    var options = {
      series: [{
      data: [800, 700, 600, 500, 400, 300, 200, 100]
    }],
      chart: {
      type: 'bar',
      height: 300
    },
    plotOptions: {
      bar: {
        barHeight: '100%',
        distributed: true,
        horizontal: true,
        // dataLabels: {
        //   position: 'bottom'
        // },
      }
    },
    colors: ['#33b2df', '#546E7A', '#d4526e', '#13d8aa', '#A5978B', '#2b908f', '#90ee7e', '#69d2e7'],
    dataLabels: {
      enabled: true,
      textAnchor: 'start',
      style: {
        colors: ['#fff']
      },
      formatter: function (val, opt) {
        return opt.w.globals.labels[opt.dataPointIndex] + ":  " + val
      },
      offsetX: 0,
      dropShadow: {
        enabled: true
      }
    },
    stroke: {
      width: 1,
      colors: ['#fff']
    },
    xaxis: {
      categories: ['R1', 'R2', 'R3', 'R4', 'R5', 'R6', 'R7', 'R8'
      ],
    },
    yaxis: {
      labels: {
        show: false
      }
    },
    title: {
        text: 'Nivel de Ingresos',
        align: 'center',
        floating: true
    },
    subtitle: {
        text: '',
        align: 'center',
    },
    tooltip: {
      theme: 'dark',
      x: {
        show: false
      },
      y: {
        title: {
          formatter: function () {
            return ''
          }
        }
      }
    }
    };
    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();

</script>



@endsection
 
 