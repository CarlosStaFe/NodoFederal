@extends('layouts.admin')

@section('content')

<div class="row">
    <h1>Registrar Usuarios</h1>
</div>

<div class="col-md-12">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Completar los datos</h3>
        </div>

        <div class="card-body">
            <form action="{{url('/admin/usuarios/create')}}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nodo_id">Nodo</label><b>*</b>
                            <select class="form-control" id="nodo_id" name="nodo_id" onchange="filtrarSocios()">
                                <option selected disabled>Seleccione un Nodo</option>
                                @foreach($nodos as $nodo)
                                    <option value="{{$nodo->id}}" {{old('nodo_id') == $nodo->id ? 'selected' : ''}}>{{$nodo->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="socio_id">Socio</label><b>*</b>
                            <select class="form-control" id="socio_id" name="socio_id">
                                <option selected disabled>Seleccione un Socio</option>
                                @foreach($socios as $socio)
                                    <option value="{{$socio->id}}" data-nodo-id="{{$socio->nodo_id}}" {{old('socio_id') == $socio->id ? 'selected' : ''}}>{{$socio->razon_social}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="name">Nombre del Usuario</label><b>*</b>
                            <input type="text" class="form-control" value="{{old('name')}}" id="name" name="name" placeholder="Nombre del Usuario" required>
                            @error('name')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="email">E-mail</label><b>*</b>
                            <input type="email" class="form-control" value="{{old('email')}}" id="email" name="email" placeholder="Email" required>
                            @error('email')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="rol">Rol</label><b>*</b>
                            <select class="form-control" id="rol" name="rol" required>
                                <option selected disabled>Seleccione un Rol</option>
                                @if(auth()->user()->hasRole('nodo'))
                                    <option value="socio" {{old('rol') == 'socio' ? 'selected' : ''}}>Socio</option>
                                @else
                                    <option value="admin" {{old('rol') == 'admin' ? 'selected' : ''}}>Administrador</option>
                                    <option value="secretaria" {{old('rol') == 'secretaria' ? 'selected' : ''}}>Secretaria</option>
                                    <option value="nodo" {{old('rol') == 'nodo' ? 'selected' : ''}}>Nodo</option>
                                    <option value="socio" {{old('rol') == 'socio' ? 'selected' : ''}}>Socio</option>
                                @endif
                            </select>
                            @error('rol')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <br>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password">Contraseña</label><b>*</b>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" onclick="generarPassword()">Generar</button>
                                </div>
                            </div>
                            @error('password')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                        <script>
                            function generarPassword() {
                                const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
                                let password = "";
                                for (let i = 0; i < 10; i++) {
                                    password += chars.charAt(Math.floor(Math.random() * chars.length));
                                }
                                document.getElementById('password').value = password;
                                document.getElementById('password_confirmation').value = password;
                            }
                        </script>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password_confirmation">Verificar Contraseña</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Verificar Contraseña" required readonly>
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

<script>
// Almacenar todas las opciones originales al cargar la página
let todasLasOpciones = [];

function filtrarSocios() {
    const nodoSelect = document.getElementById('nodo_id');
    const socioSelect = document.getElementById('socio_id');
    const nodoId = nodoSelect.value;
    
    // Limpiar el select de socios
    socioSelect.innerHTML = '<option selected disabled>Seleccione un Socio</option>';
    
    // Si no hay nodo seleccionado, no mostrar socios
    if (!nodoId) {
        return;
    }
    
    // Filtrar y mostrar solo socios del nodo seleccionado
    todasLasOpciones.forEach(opcion => {
        if (opcion.getAttribute('data-nodo-id') === nodoId) {
            socioSelect.appendChild(opcion.cloneNode(true));
        }
    });
}

// Ejecutar cuando se carga la página
document.addEventListener('DOMContentLoaded', function() {
    const socioSelect = document.getElementById('socio_id');
    const nodoSelect = document.getElementById('nodo_id');
    
    // Limpiar los campos al cargar la página
    document.getElementById('email').value = '';
    document.getElementById('name').value = '';
    
    // Guardar todas las opciones originales (excepto la primera que es el placeholder)
    const opciones = socioSelect.querySelectorAll('option[data-nodo-id]');
    todasLasOpciones = Array.from(opciones);
    
    // Si hay un nodo preseleccionado, filtrar
    if (nodoSelect.value && nodoSelect.value !== '') {
        filtrarSocios();
    }
    
    // Si solo hay un nodo disponible, seleccionarlo automáticamente
    const nodoOpciones = nodoSelect.querySelectorAll('option[value]');
    if (nodoOpciones.length === 1) {
        nodoSelect.value = nodoOpciones[0].value;
        filtrarSocios();
    }
});
</script>

@endsection