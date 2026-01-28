@extends('layouts.admin')

@section('content')
    <div class="row">
        <h1>Socio: {{ $socio->razon_social }}</h1>
    </div>

    <div class="col-md-12">
        <div class="card card-danger">
            <div class="card-header">
                <h3 class="card-title">¿Desea eliminar este registro?</h3>
            </div>

            <div class="card-body">
                <form action="{{ url('admin/socios/' . $socio->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="row">
                        <div>
                            <input id="nombrelocal" name="nombrelocal" type="hidden">
                            <input id="nombreprov" name="nombreprov" type="hidden">
                        </div>
                        <div class="col-md-2 col-sm-12 position-relative">
                            <div class="form group">
                                <label for="numero">Número</label>
                                <p>{{ $socio->numero }}</p>
                            </div>
                        </div>
                        <div class="col-md-5 col-sm-12 position-relative">
                            <div class="form group">
                                <label for="nodo">Nodo</label>
                                <p>
                                    @if ($socio->nodo)
                                        {{ $socio->nodo->numero }} - {{ $socio->nodo->nombre }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 position-relative">
                            <div class="form group">
                                <label for="clase">Clase</label>
                                <p>{{ $socio->clase }}</p>
                            </div>
                        </div>
                        <div class="col-md-5 col-sm-12 position-relative">
                            <div class="form group">
                                <label for="razon_social">Razón Social</label>
                                <p>{{ $socio->razon_social }}</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 position-relative">
                            <div class="form group">
                                <label for="cuit">C.U.I.T.</label>
                                <p>{{ $socio->cuit }}</p>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12 position-relative">
                            <div class="form group">
                                <label for="tipo">Tipo I.V.A.</label>
                                <p>{{ $socio->tipo }}</p>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4 col-sm-12 position-relative">
                            <div class="form group">
                                <label for="domicilio">Domicilio</label>
                                <p>{{ $socio->domicilio }}</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 position-relative">
                            <div class="form-group">
                                <label for="provincia">Provincia</label>
                                <p>{{ strtoupper($socio->localidad->provincia ?? 'N/A') }}</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 position-relative">
                            <div class="form-group">
                                <label for="localidad">Localidad</label>
                                <p>{{ strtoupper($socio->localidad->localidad ?? 'N/A') }}</p>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12 position-relative">
                            <div class="form-group">
                                <label for="cod_postal">Cod.Postal</label>
                                <p>{{ $socio->localidad->cod_postal ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4 col-sm-12 position-relative">
                            <div class="form group">
                                <label for="telefono">Teléfono</label>
                                <p>{{ $socio->telefono }}</p>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 position-relative">
                            <div class="form group">
                                <label for="email">E-mail</label>
                                <p>{{ $socio->email }}</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 position-relative">
                            <div class="form group">
                                <label for="estado">Estado</label>
                                <p>{{ $socio->estado }}</p>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12 position-relative">
                            <div class="form-group">
                                <label for="observacion">Observación</label>
                                <p>{{ $socio->observacion }}</p>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="form group">
                        <a href="{{ url('admin/socios') }}" class="btn btn-secondary">Volver</a>
                        <button type="submit" class="btn btn-danger">Eliminar Socio</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
