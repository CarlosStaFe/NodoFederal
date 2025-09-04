@extends('layouts.admin')

@section('content')

<div class="row">
    <h1>Nodo: {{$nodo->nombre}}</h1>
</div>

<div class="col-md-12">
    <div class="card card-danger">
        <div class="card-header">
            <h3 class="card-title">¿Desea eliminar este registro?</h3>
        </div>

        <div class="card-body">
            <form action="{{url('admin/nodos/'.$nodo->id)}}" method="POST">
                @csrf
                @method('DELETE')
                <div class="card-body">
                    <div class="row">
                        <div>
                            <input id="nombrelocal" name="nombrelocal" type="hidden">
                            <input id="nombreprov" name="nombreprov" type="hidden">
                        </div>
                        <div class="col-md-1 col-sm-12 position-relative">
                            <div class="form group">
                                <label for="numero">Número</label>
                                <p>{{$nodo->numero}}</p>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12 position-relative">
                            <div class="form group">
                                <label for="factura">Factura</label>
                                <p>{{$nodo->factura}}</p>
                            </div>
                        </div>
                        <div class="col-md-5 col-sm-12 position-relative">
                            <div class="form group">
                                <label for="nombre">Nombre</label>
                                <p>{{$nodo->nombre}}</p>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12 position-relative">
                            <div class="form group">
                                <label for="cuit">C.U.I.T.</label>
                                <p>{{$nodo->cuit}}</p>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12 position-relative">
                            <div class="form group">
                                <label for="tipo">Tipo I.V.A.</label>
                                <p>{{$nodo->tipo}}</p>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4 col-sm-12 position-relative">
                            <div class="form group">
                                <label for="domicilio">Domicilio</label>
                                <p>{{$nodo->domicilio}}</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 position-relative">
                            <div class="form-group">
                                <label for="provincia">Provincia</label>
                                <p>{{strtoupper($nodo->localidad->provincia ?? 'N/A')}}</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 position-relative">
                            <div class="form-group">
                                <label for="localidad">Localidad</label>
                                <p>{{strtoupper($nodo->localidad->localidad ?? 'N/A')}}</p>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12 position-relative">
                            <div class="form-group">
                                <label for="cod_postal">Cod.Postal</label>
                                <p>{{$nodo->localidad->cod_postal ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4 col-sm-12 position-relative">
                            <div class="form group">
                                <label for="telefono">Teléfono</label>
                                <p>{{$nodo->telefono}}</p>
                            </div>
                        </div>
                        <div class="col-md-5 col-sm-12 position-relative">
                            <div class="form group">
                                <label for="email">E-mail</label>
                                <p>{{$nodo->email}}</p>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12 position-relative">
                            <div class="form group">
                                <label for="estado">Estado</label>
                                <p>{{$nodo->estado}}</p>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-3 position-relative">
                            <div class="form group">
                                <label for="valor_consulta">Valor por Consulta</label>
                                <p>{{$nodo->valor_consulta}}</p>
                            </div>
                        </div>
                        <div class="col-md-3 position-relative">
                            <div class="form group">
                                <label for="valor_afectar">Valor por Afectar</label>
                                <p>{{$nodo->valor_afectar}}</p>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12 position-relative">
                            <div class="form-group">
                                <label for="observacion">Observación</label>
                                <p>{{$nodo->observacion}}</p>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="form group">
                        <a href="{{url('admin/nodos')}}" class="btn btn-secondary">Volver</a>
                        <button type="submit" class="btn btn-danger">Eliminar Nodo</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection