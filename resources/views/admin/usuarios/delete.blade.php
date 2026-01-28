@extends('layouts.admin')

@section('content')

<div class="row">
    <h1>Usuario: {{$usuario->name}}</h1>
</div>

<div class="col-md-6">
    <div class="card card-danger">
        <div class="card-header">
            <h3 class="card-title">¿Desea eliminar este registro?</h3>
        </div>

        <div class="card-body">
            <form action="{{url('admin/usuarios/'.$usuario->id)}}" method="POST">
                @csrf
                @method('DELETE')
                <div class="form group">
                    <label for="name">Nodo</label>
                    <p>{{$usuario->nodo->nombre ?? ''}}</p>
                </div>
                <div class="form group">
                    <label for="name">Socio</label>
                    <p>{{ optional($usuario->socio)->razon_social ?? 'Sin socio asignado' }}</p>
                </div>
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
                    <label for="rol">Rol</label>
                    <p>{{$usuario->roles->first()->name ?? ''}}</p>
                </div>
                <br>
                <div class="form group">
                    <a href="{{url('admin/usuarios')}}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-danger">Eliminar Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection