@extends('layouts.admin')

@section('content')

<div class="row">
    <h1>Modificar usuario: {{$usuario->name}}</h1>
</div>

<div class="col-md-12">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Actualizar los datos</h3>
        </div>

        <div class="card-body">
            <form action="{{url('/admin/usuarios/'.$usuario->id)}}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nodo_id">Nodo</label>
                            <select class="form-control" id="nodo_id" name="nodo_id" required>
                            <option value="">Seleccione un Nodo</option>
                                @foreach($nodos as $nodo)
                                    <option value="{{$nodo->id}}" 
                                        {{ (old('nodo_id', $usuario->nodo_id) == $nodo->id) ? 'selected' : '' }}>
                                        {{$nodo->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="socio_id">Socio</label>
                            <select class="form-control" id="socio_id" name="socio_id" required>
                                <option value="">Seleccione un Socio</option>
                                @foreach($socios as $socio)
                                    <option value="{{$socio->id}}" 
                                        {{ (old('socio_id', $usuario->socio_id) == $socio->id) ? 'selected' : '' }}>
                                        {{$socio->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Nombre del Usuario</label>
                            <input type="text" class="form-control" value="{{ old('name', $usuario->name) }}" id="name" name="name" placeholder="Nombre del Usuario" required>
                            @error('name')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" class="form-control" value="{{ old('email', $usuario->email) }}" id="email" name="email" placeholder="Email" required>
                            @error('email')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <br>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
                            @error('password')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password_confirmation">Verificar Contraseña</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Verificar Contraseña" required>
                            @error('password_confirmation')
                                    <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <br>
                    <div class="form group">
                        <a href="{{url('admin/usuarios')}}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Registrar Usuario</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection