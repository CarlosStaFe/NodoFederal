@extends('layouts.admin')

@section('content')

<div class="row">
    <h1>Cliente: {{$cliente->apelnombres}}</h1>
</div>

<div class="col-md-12">
    <div class="card card-danger">
        <div class="card-header">
            <h3 class="card-title">¿Desea eliminar este registro?</h3>
        </div>

        <div class="card-body">
            <div class="row">
                <div>
                    <input id="nombrelocal" name="nombrelocal" type="hidden">
                    <input id="nombreprov" name="nombreprov" type="hidden">
                </div>
                <div class="col-md-1 col-sm-12 position-relative">
                    <div class="form group">
                        <label for="tipodoc">Tipo Doc</label>
                        <p>{{$cliente->tipodoc}}</p>
                    </div>
                </div>
                <div class="col-md-1 col-sm-12 position-relative">
                    <div class="form group">
                        <label for="sexo">Sexo</label>
                        <p>{{$cliente->sexo}}</p>
                    </div>
                </div>
                <div class="col-md-2 col-sm-12 position-relative">
                    <div class="form group">
                        <label for="documento">Documento</label>
                        <p>{{$cliente->documento}}</p>
                    </div>
                </div>
                <div class="col-md-2 col-sm-12 position-relative">
                    <div class="form group">
                        <label for="cuit">C.U.I.T.</label>
                        <p>{{$cliente->cuit}}</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12 position-relative">
                    <div class="form group">
                        <label for="apelnombres">Apellido y Nombres</label>
                        <p>{{$cliente->apelnombres}}</p>
                    </div>
                </div>
                <div class="col-md-2 col-sm-12 position-relative">
                    <div class="form group">
                        <label for="nacimiento">Fecha Nac.</label>
                        <p>{{ \Carbon\Carbon::parse($cliente->nacimiento)->format('d-m-Y') }}</p>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-4 col-sm-12 position-relative">
                    <div class="form group">
                        <label for="domicilio">Domicilio</label>
                        <p>{{$cliente->domicilio}}</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12 position-relative">
                    <div class="form-group">
                        <label for="provincia">Provincia</label>
                        <p>{{strtoupper($cliente->localidad->provincia ?? 'N/A')}}</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12 position-relative">
                    <div class="form-group">
                        <label for="localidad">Localidad</label>
                        <p>{{strtoupper($cliente->localidad->localidad ?? 'N/A')}}</p>
                    </div>
                </div>
                <div class="col-md-2 col-sm-12 position-relative">
                    <div class="form-group">
                        <label for="cod_postal">Cod.Postal</label>
                        <p>{{$cliente->localidad->cod_postal ?? 'N/A'}}</p>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-3 col-sm-12 position-relative">
                    <div class="form group">
                        <label for="telefono">Teléfono</label>
                        <p>{{$cliente->telefono}}</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12 position-relative">
                    <div class="form group">
                        <label for="email">E-mail</label>
                        <p>{{$cliente->email}}</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12 position-relative">
                    <div class="form group">
                        <label for="estado">Estado</label>
                        <p>{{$cliente->estado}}</p>
                    </div>
                </div>
                <div class="col-md-2 col-sm-12 position-relative">
                    <div class="form group">
                        <label for="fechaestado">Fecha Estado</label>
                        <p>{{ \Carbon\Carbon::parse($cliente->fechaestado)->format('d-m-Y') }}</p>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12 position-relative">
                    <div class="form-group">
                        <label for="observacion">Observación</label>
                        <p>{{$cliente->observacion}}</p>
                    </div>
                </div>
            </div>
            <br>
            <div class="form group">
                <a href="{{url('admin/clientes')}}" class="btn btn-secondary">Volver</a>
                <button type="submit" class="btn btn-danger">Eliminar Cliente</button>
            </div>
        </div>
    </div>
</div>

@endsection