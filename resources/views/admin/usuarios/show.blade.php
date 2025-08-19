@extends('layouts.admin')

@section('content')

<div class="row">
    <h1>Usuario: {{$usuario->name}}</h1>
</div>

<div class="col-md-6">
    <div class="card card-outline card-success">
        <div class="card-header">
            <h3 class="card-title">Datos Registrados</h3>
        </div>

        <div class="card-body">
            <div class="form group">
                <label for="name">Nombre del Usuario</label>
                <p>{{$usuario->name}}</p>
            </div>
            <br>
            <div class="form group">
                <label for="email">E-mail</label>
                <p>{{$usuario->email}}</p>
            </div>
            <br>
            <div class="form group">
                <a href="{{url('admin/usuarios')}}" class="btn btn-success">Volver</a>
            </div>
        </div>
    </div>
</div>

@endsection