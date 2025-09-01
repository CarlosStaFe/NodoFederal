@extends('layouts.admin')

@section('content')

<div class="col-md-12">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="card-title mb-0">Informe de Antecedentes</h1>
                @if(isset($datos) && isset($datos['data'][0]))
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
                @php $p = $datos['data'][0] ?? null; @endphp
                @if ($p)
                    <form class="row g-3 mt-4">
                        <div class="col-12"><strong>Datos Personales</strong></div>
                        <div class="col-md-3">
                            <label class="form-label">Apellido</label>
                            <input type="text" class="form-control" value="{{ $p['apellido'] ?? '' }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" value="{{ $p['nombre'] ?? '' }}" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">CUIL</label>
                            <input type="text" class="form-control" value="{{ $p['cuil'] ?? '' }}" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">DNI</label>
                            <input type="text" class="form-control" value="{{ $p['dni'] ?? '' }}" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Fecha Nacimiento</label>
                            <input type="text" class="form-control" value="{{ $p['fechaNacimiento'] ?? '' }}"
                                readonly>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">Edad</label>
                            <input type="text" class="form-control" value="{{ $p['edad'] ?? '' }}" readonly>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">Sexo</label>
                            <input type="text" class="form-control" value="{{ $p['sexo'] ?? '' }}" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Estado Civil</label>
                            <input type="text" class="form-control" value="{{ $p['estadoCivil'] ?? '' }}" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Nacionalidad</label>
                            <input type="text" class="form-control" value="{{ $p['nacionalidad'] ?? '' }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Localidad</label>
                            <input type="text" class="form-control" value="{{ $p['localidad'] ?? '' }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Provincia</label>
                            <input type="text" class="form-control" value="{{ $p['provincia'] ?? '' }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Domicilio</label>
                            <input type="text" class="form-control" value="{{ $p['domicilio'] ?? '' }}" readonly>
                        </div>
                        <div class="col-12 mt-3"><strong>Teléfonos Fijos</strong></div>
                        @php $tf = $data['contactacion']['telefonosFijos']['datos'] ?? []; @endphp
                        @foreach ($tf as $tel)
                            <div class="col-md-3">
                                <label class="form-label">{{ $tel['tipo'] ?? '' }}</label>
                                <input type="text" class="form-control"
                                    value="{{ $tel['tel'] ?? '' }} ({{ $tel['operador'] ?? '' }})" readonly>
                            </div>
                        @endforeach
                        <div class="col-12 mt-3"><strong>Teléfonos Celulares</strong></div>
                        @php $tc = $data['contactacion']['telefonosCelulares']['datos'] ?? []; @endphp
                        @foreach ($tc as $cel)
                            <div class="col-md-3">
                                <label class="form-label">{{ $cel['tipo'] ?? '' }}
                                    {{ $cel['wsp'] ? '(WhatsApp)' : '' }}</label>
                                <input type="text" class="form-control"
                                    value="{{ $cel['tel'] ?? '' }} ({{ $cel['operador'] ?? '' }})" readonly>
                            </div>
                        @endforeach
                        <div class="col-12 mt-3"><strong>Mails</strong></div>
                        @php $mails = $data['contactacion']['mails']['datos'] ?? []; @endphp
                        @foreach ($mails as $mail)
                            <div class="col-md-3">
                                <label class="form-label">Email</label>
                                <input type="text" class="form-control" value="{{ $mail['email'] ?? '' }}" readonly>
                            </div>
                        @endforeach
                        <div class="col-12 mt-3"><strong>Monotributo</strong></div>
                        @php $monos = $data['datosLaborales']['monotributista']['datos'] ?? []; @endphp
                        @foreach ($monos as $mono)
                            <div class="col-md-4">
                                <label class="form-label">Tipo</label>
                                <input type="text" class="form-control" value="{{ $mono['tipo'] ?? '' }}" readonly>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Categoría</label>
                                <input type="text" class="form-control" value="{{ $mono['categoria'] ?? '' }}"
                                    readonly>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Estado</label>
                                <input type="text" class="form-control" value="{{ $mono['estado'] ?? '' }}"
                                    readonly>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Inicio</label>
                                <input type="text" class="form-control" value="{{ $mono['fechaInicio'] ?? '' }}"
                                    readonly>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Hasta</label>
                                <input type="text" class="form-control" value="{{ $mono['fechaHasta'] ?? '' }}"
                                    readonly>
                            </div>
                        @endforeach
                        <div class="col-12 mt-3"><strong>Actividad</strong></div>
                        @php $acts = $data['datosLaborales']['actividad']['datos'] ?? []; @endphp
                        @foreach ($acts as $act)
                            <div class="col-md-6">
                                <label class="form-label">Descripción</label>
                                <input type="text" class="form-control" value="{{ $act['descripcion'] ?? '' }}"
                                    readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">CIIU</label>
                                <input type="text" class="form-control" value="{{ $act['ciiu'] ?? '' }}"
                                    readonly>
                            </div>
                        @endforeach
                        <div class="col-12 mt-3"><strong>Jubilación</strong></div>
                        @php $jubs = $data['datosLaborales']['jubilacion']['datos'] ?? []; @endphp
                        @foreach ($jubs as $jub)
                            <div class="col-md-4">
                                <label class="form-label">Titular</label>
                                <input type="text" class="form-control" value="{{ $jub['titular'] ?? '' }}"
                                    readonly>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Sueldo Bruto</label>
                                <input type="text" class="form-control" value="{{ $jub['sueldoBruto'] ?? '' }}"
                                    readonly>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Sueldo Neto</label>
                                <input type="text" class="form-control" value="{{ $jub['sueldoNeto'] ?? '' }}"
                                    readonly>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Periodo</label>
                                <input type="text" class="form-control" value="{{ $jub['periodo'] ?? '' }}"
                                    readonly>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Rango</label>
                                <input type="text" class="form-control" value="{{ $jub['rango'] ?? '' }}"
                                    readonly>
                            </div>
                        @endforeach
                        <div class="col-12 mt-3"><strong>Automotores Historial</strong></div>
                        @php $autos = $data['bienesPersonales']['automotores_historial']['datos'] ?? []; @endphp
                        @foreach ($autos as $auto)
                            <div class="col-md-3">
                                <label class="form-label">Dominio</label>
                                <input type="text" class="form-control" value="{{ $auto['dominio'] ?? '' }}"
                                    readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Marca</label>
                                <input type="text" class="form-control" value="{{ $auto['marca'] ?? '' }}"
                                    readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Modelo</label>
                                <input type="text" class="form-control" value="{{ $auto['modelo'] ?? '' }}"
                                    readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Año</label>
                                <input type="text" class="form-control" value="{{ $auto['anioModelo'] ?? '' }}"
                                    readonly>
                            </div>
                        @endforeach
                        <div class="col-12 mt-3"><strong>Situación Financiera (BCRA)</strong></div>
                        @php $bcra = $data['situacionFinanciera']['informacionBcra']['datos'] ?? []; @endphp
                        @foreach ($bcra as $b)
                            <div class="col-md-3">
                                <label class="form-label">Entidad</label>
                                <input type="text" class="form-control"
                                    value="{{ $b['entidad']['entidad'] ?? '' }}" readonly>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Tipo</label>
                                <input type="text" class="form-control" value="{{ $b['entidad']['tipo'] ?? '' }}"
                                    readonly>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Situación</label>
                                <input type="text" class="form-control" value="{{ $b['situacion'] ?? '' }}"
                                    readonly>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Periodo</label>
                                <input type="text" class="form-control" value="{{ $b['periodo'] ?? '' }}"
                                    readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Préstamo</label>
                                <input type="text" class="form-control" value="{{ $b['prestamo'] ?? '' }}"
                                    readonly>
                            </div>
                        @endforeach
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
 
 