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
                            @if(auth()->user()->hasRole('nodo'))
                                <input type="text" class="form-control" value="{{auth()->user()->nodo->nombre ?? 'Sin asignar'}}" readonly>
                                <input type="hidden" name="nodo_id" value="{{auth()->user()->nodo_id}}">
                            @else
                                <select class="form-control" id="nodo_id" name="nodo_id" required>
                                    <option value="">Seleccione un Nodo</option>
                                    @foreach($nodos as $nodo)
                                        <option value="{{$nodo->id}}" 
                                            {{ (old('nodo_id', $usuario->nodo_id) == $nodo->id) ? 'selected' : '' }}>
                                            {{$nodo->nombre}}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="socio_id">Socio</label>
                            <select class="form-control" id="socio_id" name="socio_id" required>
                                <option value="">Seleccione un Socio</option>
                                @if(auth()->user()->hasRole('nodo'))
                                    @foreach($socios->where('nodo_id', auth()->user()->nodo_id)->sortBy('razon_social') as $socio)
                                        <option value="{{$socio->id}}" 
                                            {{ (old('socio_id', $usuario->socio_id) == $socio->id) ? 'selected' : '' }}>
                                            {{$socio->razon_social}}
                                        </option>
                                    @endforeach
                                @else
                                    @foreach($socios->sortBy('razon_social') as $socio)
                                        <option value="{{$socio->id}}" 
                                            data-nodo="{{$socio->nodo_id}}"
                                            {{ (old('socio_id', $usuario->socio_id) == $socio->id) ? 'selected' : '' }}>
                                            {{$socio->razon_social}}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="name">Nombre del Usuario</label>
                            <input type="text" class="form-control" value="{{ old('name', $usuario->name) }}" id="name" name="name" placeholder="Nombre del Usuario" required>
                            @error('name')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" class="form-control" value="{{ old('email', $usuario->email) }}" id="email" name="email" placeholder="Email" required>
                            @error('email')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="rol">Rol</label><b>*</b>
                        <select class="form-control" id="rol" name="rol" required>
                            <option value="" disabled>Seleccione un Rol</option>
                            @if(auth()->user()->hasRole('nodo'))
                                <option value="socio" {{ (old('rol', $usuario->roles->first()?->name) == 'socio') ? 'selected' : '' }}>Socio</option>
                            @else
                                <option value="admin" {{ (old('rol', $usuario->roles->first()?->name) == 'admin') ? 'selected' : '' }}>Administrador</option>
                                <option value="secretaria" {{ (old('rol', $usuario->roles->first()?->name) == 'secretaria') ? 'selected' : '' }}>Secretaria</option>
                                <option value="nodo" {{ (old('rol', $usuario->roles->first()?->name) == 'nodo') ? 'selected' : '' }}>Nodo</option>
                                <option value="socio" {{ (old('rol', $usuario->roles->first()?->name) == 'socio') ? 'selected' : '' }}>Socio</option>
                            @endif
                        </select>
                        @error('rol')
                            <small style="color: red">{{$message}}</small>
                        @enderror
                    </div>

                    <br>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" onclick="generarPassword()">Generar</button>
                                </div>
                            </div>
                            @error('password')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                            <script>
                                function generarPassword() {
                                    const longitud = 10;
                                    const caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()';
                                    let password = '';
                                    for (let i = 0; i < longitud; i++) {
                                        password += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
                                    }
                                    document.getElementById('password').value = password;
                                    document.getElementById('password_confirmation').value = password;
                                }

                                // Filtrar socios por nodo seleccionado
                                @if(!auth()->user()->hasRole('nodo'))
                                document.getElementById('nodo_id').addEventListener('change', function() {
                                    const nodoId = this.value;
                                    const socioSelect = document.getElementById('socio_id');
                                    const opciones = socioSelect.querySelectorAll('option');
                                    
                                    // Limpiar selección actual
                                    socioSelect.value = '';
                                    
                                    opciones.forEach(function(opcion) {
                                        if (opcion.value === '') {
                                            opcion.style.display = 'block';
                                        } else if (opcion.getAttribute('data-nodo') === nodoId) {
                                            opcion.style.display = 'block';
                                        } else {
                                            opcion.style.display = 'none';
                                        }
                                    });
                                });

                                // Ejecutar el filtro al cargar la página si ya hay un nodo seleccionado
                                document.addEventListener('DOMContentLoaded', function() {
                                    const nodoSelect = document.getElementById('nodo_id');
                                    if (nodoSelect.value) {
                                        nodoSelect.dispatchEvent(new Event('change'));
                                    }
                                });
                                @endif
                            </script>
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