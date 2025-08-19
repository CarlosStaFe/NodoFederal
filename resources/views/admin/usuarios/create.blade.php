@extends('layouts.admin')

@section('content')

<div class="row">
    <h1>Registrar Usuarios</h1>
</div>

<div class="col-md-6">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Completar los datos</h3>
        </div>

        <div class="card-body">
            <form action="{{url('/admin/usuarios/create')}}" method="POST">
                @csrf
                <div class="form group">
                    <label for="name">Nombre del Usuario</label><b>*</b>
                    <input type="text" class="form-control" value="{{old('name')}}" id="name" name="name" placeholder="Nombre del Usuario" required>
                    @error('name')
                        <small style="color: red">{{$message}}</small>
                    @enderror
                </div>
                <br>
                <div class="form group">
                    <label for="email">E-mail</label><b>*</b>
                    <input type="email" class="form-control" value="{{old('email')}}" id="email" name="email" placeholder="Email" required>
                    @error('email')
                        <small style="color: red">{{$message}}</small>
                    @enderror
                </div>
                <br>
                <div class="form group">
                    <label for="password">Contraseña</label><b>*</b>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
                    @error('password')
                        <small style="color: red">{{$message}}</small>
                    @enderror
                </div>
                <br>
                <div class="form group">
                    <label for="password_confirmation">Verificar Contraseña</label><b>*</b>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Verificar Contraseña" required>
                </div>
                    @error('password_verify')
                            <small style="color: red">{{$message}}</small>
                    @enderror
                <br>
                <div class="form group">
                    <a href="{{url('admin/usuarios')}}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Registrar Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection