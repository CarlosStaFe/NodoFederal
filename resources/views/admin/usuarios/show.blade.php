@extends('layouts.admin')

@section('content')

<div class="row">
    <h1>Usuario: {{$usuario->name}}</h1>
</div>

<div class="col-md-12">
    <div class="card card-outline card-success">
        <div class="card-header">
            <h3 class="card-title">Datos registrados</h3>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6 col-sm-12 position-relative">
                    <div class="form group">
                        <label for="apel_nombres">Nodo</label>
                        <p>{{ $usuario->nodo ? $usuario->nodo->nombre : 'Sin asignar' }}</p>
                    </div>
                </div>
                <div class="col-md-6 col-sm-4 position-relative">
                    <div class="form group">
                        <label for="nacimiento">Socio</label>
                        <p>{{ $usuario->socio ? $usuario->socio->razon_social : 'Sin asignar' }}</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 position-relative">
                    <div class="form-group">
                        <label for="sexo">Nombre del Usuario</label>
                        <p>{{$usuario->name}}</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 position-relative">
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <p>{{$usuario->email}}</p>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 position-relative">
                    <div class="form-group">
                        <label for="rol">Rol</label>
                        <p>{{ $usuario->getRoleNames()->first() ?? 'Sin asignar' }}</p>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="form group">
            <a href="{{url('admin/usuarios')}}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Registrar Usuario</button>
        </div>
    </div>
</div>

@endsection