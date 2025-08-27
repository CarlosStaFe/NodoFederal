@extends('layouts.admin')

@section('content')

<div class="row">
    <h1>Consulta de antecedentes: {{ auth()->user()->name }}</h1>
</div>

<div class="col-md-12">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Consultar</h3>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.operaciones.consultar.api') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-lg-1 col-md-3 position-relative">
                        <label for="tipo" class="form-label">Tipo Doc.</label>
                        <select id="tipo" name="tipo" class="form-select" required>
                            <option value="">Tipo</option>
                            <option value="DNI">DNI</option>
                            <option value="CUIL">CUIL</option>
                        </select>
                        @error('tipo')
                            <small style="color: red">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="col-lg-1 col-md-2 position-relative">
                        <label for="sexo" class="form-label">Sexo</label>
                        <select id="sexo" name="sexo" class="form-select" required>
                            <option value="">Sexo</option>
                            <option value="M">M</option>
                            <option value="F">F</option>
                        </select>
                        @error('sexo')
                            <small style="color: red">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="col-lg-2 col-md-2 position-relative">
                        <label for="documento" class="form-label">Número</label>
                        <input id="documento" name="documento" type="text" value="" class="form-control" placeholder="Ingrese un número" required>
                        @error('documento')
                            <small style="color: red">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="col-lg-2 col-md-2 position-relative">
                        <label for="cuit" class="form-label">C.U.I.T.</label>
                        <input id="cuit" name="cuit" type="text" value="" class="form-control" placeholder="Ingrese un CUIT" required>
                        @error('cuit')
                            <small style="color: red">{{$message}}</small>
                        @enderror
                    </div>
                    <br>
                </div>
                <br>
                <button type="button" class="btn btn-success me-5" id="limpiarBtn">Limpiar</button>
                <button type="submit" class="btn btn-primary me-5">Consultar</button>
                <a href="{{ url('admin') }}" class="btn btn-info">Salir</a>  </div>
            </form>
        </div>

        @isset($datos)
            {{-- <div class="alert alert-warning">
                <strong>Debug datos:</strong>
                <pre>{{ print_r($datos, true) }}</pre>
            </div> --}}
            @php $data = $datos['data'] ?? null; @endphp
            {{-- <div class="alert alert-warning">
                <strong>Debug data:</strong>
                <pre>{{ print_r($data, true) }}</pre>
            </div> --}}
            @php $p = $datos['data'][0] ?? null; @endphp
            @if($p)
            <div class="alert alert-info mt-4">
                <h5>Datos obtenidos de la API:</h5>
                <a href="{{ url('admin/operaciones/pdf') }}" class="btn btn-danger"><i class="bi bi-printer-fill"></i> Imprimir</a>
            </div>

                <form class="row g-3">
                    {{-- @php $p = $data['datosParticulares'] ?? []; @endphp --}}
                    <div class="col-12"><strong>Datos Personales</strong></div>
                    <div class="col-md-3">
                        <label class="form-label">Apellido</label>
                        <input type="text" class="form-control" value="{{ $p['apellido'] ?? '' }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" value="{{ $p['nombre'] ?? '' }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">CUIL</label>
                        <input type="text" class="form-control" value="{{ $p['cuil'] ?? '' }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">DNI</label>
                        <input type="text" class="form-control" value="{{ $p['dni'] ?? '' }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Fecha Nacimiento</label>
                        <input type="text" class="form-control" value="{{ $p['fechaNacimiento'] ?? '' }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Edad</label>
                        <input type="text" class="form-control" value="{{ $p['edad'] ?? '' }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Sexo</label>
                        <input type="text" class="form-control" value="{{ $p['sexo'] ?? '' }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Estado Civil</label>
                        <input type="text" class="form-control" value="{{ $p['estadoCivil'] ?? '' }}" readonly>
                    </div>
                    <div class="col-md-3">
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
                    <div class="col-md-3">
                        <label class="form-label">Domicilio</label>
                        <input type="text" class="form-control" value="{{ $p['domicilio'] ?? '' }}" readonly>
                    </div>
                    <div class="col-12 mt-3"><strong>Teléfonos Fijos</strong></div>
                    @php $tf = $data['contactacion']['telefonosFijos']['datos'] ?? []; @endphp
                    @foreach($tf as $tel)
                        <div class="col-md-3">
                            <label class="form-label">{{ $tel['tipo'] ?? '' }}</label>
                            <input type="text" class="form-control" value="{{ $tel['tel'] ?? '' }} ({{ $tel['operador'] ?? '' }})" readonly>
                        </div>
                    @endforeach
                    <div class="col-12 mt-3"><strong>Teléfonos Celulares</strong></div>
                    @php $tc = $data['contactacion']['telefonosCelulares']['datos'] ?? []; @endphp
                    @foreach($tc as $cel)
                        <div class="col-md-3">
                            <label class="form-label">{{ $cel['tipo'] ?? '' }} {{ $cel['wsp'] ? '(WhatsApp)' : '' }}</label>
                            <input type="text" class="form-control" value="{{ $cel['tel'] ?? '' }} ({{ $cel['operador'] ?? '' }})" readonly>
                        </div>
                    @endforeach
                    <div class="col-12 mt-3"><strong>Mails</strong></div>
                    @php $mails = $data['contactacion']['mails']['datos'] ?? []; @endphp
                    @foreach($mails as $mail)
                        <div class="col-md-3">
                            <label class="form-label">Email</label>
                            <input type="text" class="form-control" value="{{ $mail['email'] ?? '' }}" readonly>
                        </div>
                    @endforeach
                    <div class="col-12 mt-3"><strong>Monotributo</strong></div>
                    @php $monos = $data['datosLaborales']['monotributista']['datos'] ?? []; @endphp
                    @foreach($monos as $mono)
                        <div class="col-md-4">
                            <label class="form-label">Tipo</label>
                            <input type="text" class="form-control" value="{{ $mono['tipo'] ?? '' }}" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Categoría</label>
                            <input type="text" class="form-control" value="{{ $mono['categoria'] ?? '' }}" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Estado</label>
                            <input type="text" class="form-control" value="{{ $mono['estado'] ?? '' }}" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Inicio</label>
                            <input type="text" class="form-control" value="{{ $mono['fechaInicio'] ?? '' }}" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Hasta</label>
                            <input type="text" class="form-control" value="{{ $mono['fechaHasta'] ?? '' }}" readonly>
                        </div>
                    @endforeach
                    <div class="col-12 mt-3"><strong>Actividad</strong></div>
                    @php $acts = $data['datosLaborales']['actividad']['datos'] ?? []; @endphp
                    @foreach($acts as $act)
                        <div class="col-md-6">
                            <label class="form-label">Descripción</label>
                            <input type="text" class="form-control" value="{{ $act['descripcion'] ?? '' }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">CIIU</label>
                            <input type="text" class="form-control" value="{{ $act['ciiu'] ?? '' }}" readonly>
                        </div>
                    @endforeach
                    <div class="col-12 mt-3"><strong>Jubilación</strong></div>
                    @php $jubs = $data['datosLaborales']['jubilacion']['datos'] ?? []; @endphp
                    @foreach($jubs as $jub)
                        <div class="col-md-4">
                            <label class="form-label">Titular</label>
                            <input type="text" class="form-control" value="{{ $jub['titular'] ?? '' }}" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Sueldo Bruto</label>
                            <input type="text" class="form-control" value="{{ $jub['sueldoBruto'] ?? '' }}" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Sueldo Neto</label>
                            <input type="text" class="form-control" value="{{ $jub['sueldoNeto'] ?? '' }}" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Periodo</label>
                            <input type="text" class="form-control" value="{{ $jub['periodo'] ?? '' }}" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Rango</label>
                            <input type="text" class="form-control" value="{{ $jub['rango'] ?? '' }}" readonly>
                        </div>
                    @endforeach
                    <div class="col-12 mt-3"><strong>Automotores Historial</strong></div>
                    @php $autos = $data['bienesPersonales']['automotores_historial']['datos'] ?? []; @endphp
                    @foreach($autos as $auto)
                        <div class="col-md-3">
                            <label class="form-label">Dominio</label>
                            <input type="text" class="form-control" value="{{ $auto['dominio'] ?? '' }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Marca</label>
                            <input type="text" class="form-control" value="{{ $auto['marca'] ?? '' }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Modelo</label>
                            <input type="text" class="form-control" value="{{ $auto['modelo'] ?? '' }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Año</label>
                            <input type="text" class="form-control" value="{{ $auto['anioModelo'] ?? '' }}" readonly>
                        </div>
                    @endforeach
                    <div class="col-12 mt-3"><strong>Situación Financiera (BCRA)</strong></div>
                    @php $bcra = $data['situacionFinanciera']['informacionBcra']['datos'] ?? []; @endphp
                    @foreach($bcra as $b)
                        <div class="col-md-3">
                            <label class="form-label">Entidad</label>
                            <input type="text" class="form-control" value="{{ $b['entidad']['entidad'] ?? '' }}" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tipo</label>
                            <input type="text" class="form-control" value="{{ $b['entidad']['tipo'] ?? '' }}" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Situación</label>
                            <input type="text" class="form-control" value="{{ $b['situacion'] ?? '' }}" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Periodo</label>
                            <input type="text" class="form-control" value="{{ $b['periodo'] ?? '' }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Préstamo</label>
                            <input type="text" class="form-control" value="{{ $b['prestamo'] ?? '' }}" readonly>
                        </div>
                    @endforeach
                </form>
            </div>
            @endif
        @endisset


    </div>
</div>

@endsection