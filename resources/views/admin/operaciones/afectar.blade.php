@extends('layouts.admin')

@section('content')
<div class="row">
    <h1>Afectar Operación</h1>
</div>
<div class="col-md-12">
    <div class="card card-outline card-danger">
        <div class="card-header">
            <h3 class="card-title">Datos de la Operación</h3>
        </div>
        <div class="card-body">
            @if(isset($operacion))
            <form action="{{ route('admin.operaciones.afectar.store', ['id' => $operacion->id]) }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="cuit">C.U.I.T.</label>
                            <input type="text" class="form-control" id="cuit" value="{{ $cliente->cuit ?? '' }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-1 col-sm-4 position-relative">
                        <div class="form-group">
                            <label for="tipodoc">Tipo Doc.</label>
                            <input type="text" class="form-control" id="tipodoc" value="{{ $cliente->tipodoc ?? '' }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-1 col-sm-4 position-relative">
                        <div class="form-group">
                            <label for="sexo">Sexo</label>
                            <input type="text" class="form-control" id="sexo" value="{{ $cliente->sexo ?? '' }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-4 position-relative">
                        <div class="form-group">
                            <label for="documento">Documento</label>
                            <input type="text" class="form-control" id="documento" value="{{ $cliente->documento ?? '' }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 position-relative">
                        <div class="form-group">
                            <label for="apelnombres">Apellido y Nombres</label>
                            <input type="text" class="form-control" id="apelnombres" value="{{ $cliente->apelnombres ?? '' }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-4 position-relative">
                        <div class="form-group">
                            <label for="nacimiento">Fecha Nacimiento</label>
                            <input type="date" class="form-control" id="nacimiento" value="{{ isset($cliente->nacimiento) ? \Carbon\Carbon::parse($cliente->nacimiento)->format('Y-m-d') : '' }}" readonly>
                        </div>
                    </div>
                </div>
                <h4>Socio de la operación</h4>
                <div class="row">
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="numero_socio">N° Socio</label>
                            <input type="text" class="form-control text-end" id="numero_socio" value="{{ $socio->numero ?? '' }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-11">
                        <div class="form-group">
                            <label for="nombre_socio">Razón Social</label>
                            <input type="text" class="form-control" id="nombre_socio" value="{{ $socio->razon_social ?? '' }}" readonly>
                        </div>
                    </div>
                </div>
                <h4>Datos de la operación</h4>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="numero">Número</label>
                            <input type="text" class="form-control" id="numero" value="{{ $operacion->numero }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_operacion">Fecha</label>
                            <input type="text" class="form-control" id="fecha_operacion" value="{{ \Carbon\Carbon::parse($operacion->fecha_operacion)->format('d-m-Y') }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="clase">Clase</label>
                            <input type="text" class="form-control" id="clase" value="{{ $operacion->clase }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="valor_cuota">Valor Cuota</label>
                            <input type="text" class="form-control" id="valor_cuota" value="{{ $operacion->valor_cuota }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="cant_cuotas">Cant. Cuotas</label>
                            <input type="text" class="form-control" id="cant_cuotas" value="{{ $operacion->cant_cuotas }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="total">Total</label>
                            <input type="text" class="form-control" id="total" value="{{ $operacion->total }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="estado_actual">Estado</label>
                            <input type="text" class="form-control" id="estado_actual" value="{{ $operacion->estado_actual }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_afectacion">Fecha de Afectación</label>
                            <input type="date" class="form-control" id="fecha_afectacion" name="fecha_afectacion" value="{{ old('fecha_afectacion', isset($operacion->fecha_afectacion) ? \Carbon\Carbon::parse($operacion->fecha_afectacion)->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d')) }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mt-4 ml-50">
                            <button type="submit" class="btn btn-sm btn-danger bi bi-fire">AFECTAR</button>
                        </div>
                    </div>
                </div>
            </form>
                <hr>
                <h4>Garantes de la operación</h4>
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th class="text-center">C.U.I.T.</th>
                            <th class="text-center">Apellido y Nombres</th>
                            <th class="text-center">Documento</th>
                            <th class="text-center">Fecha Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($operacion->garantes ?? collect()) as $garante)
                            @if($garante->cliente)
                            <tr>
                                <td class="text-center">{{ $garante->cliente->cuit }}</td>
                                <td>{{ $garante->cliente->apelnombres }}</td>
                                <td class="text-center">{{ $garante->cliente->documento }}</td>
                                <td class="text-center">{{ $garante->fecha_estado ? \Carbon\Carbon::parse($garante->fecha_estado)->format('d-m-Y') : '' }}</td>
                            </tr>
                            @endif
                        @empty
                            <tr><td colspan="4">No hay garantes asociados a esta operación.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @else
                <div class="alert alert-warning">No se encontró la operación.</div>
            @endif
        </div>
    </div>
</div>

@endsection
