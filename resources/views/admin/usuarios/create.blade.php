@extends('layouts.admin')

@section('content')

<div class="row">
    <h1>Registrar Usuarios</h1>
</div>

@if(session('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" id="successMessage">
        <strong>¡Éxito!</strong> {{ session('message') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>¡Error!</strong> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="col-md-12">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Completar los datos</h3>
        </div>

        <div class="card-body">
            <form id="usuarioForm" action="{{url('/admin/usuarios/create')}}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nodo_id">Nodo</label><b>*</b>
                            @if(auth()->user()->hasRole('nodo'))
                                {{-- Si el usuario es nodo, solo mostrar su nodo y deshabilitarlo --}}
                                <select class="form-control" id="nodo_id" name="nodo_id" disabled>
                                    @foreach($nodos as $nodo)
                                        @if($nodo->id == auth()->user()->nodo_id)
                                            <option value="{{$nodo->id}}" selected>{{$nodo->nombre}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                {{-- Campo hidden para enviar el valor --}}
                                <input type="hidden" name="nodo_id" value="{{auth()->user()->nodo_id}}">
                            @else
                                {{-- Para otros roles, mostrar todos los nodos --}}
                                <select class="form-control" id="nodo_id" name="nodo_id" onchange="filtrarSocios()">
                                    <option selected disabled>Seleccione un Nodo</option>
                                    @foreach($nodos as $nodo)
                                        <option value="{{$nodo->id}}" {{old('nodo_id') == $nodo->id ? 'selected' : ''}}>{{$nodo->nombre}}</option>
                                    @endforeach
                                </select>
                            @endif
                            @error('nodo_id')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="socio_id">Socio</label><b>*</b>
                            @if(auth()->user()->hasRole('nodo'))
                                <select class="form-control" id="socio_id" name="socio_id" required>
                                    <option value="" disabled selected>Debe seleccionar un Socio</option>
                                    @foreach($socios as $socio)
                                        <option value="{{$socio->id}}" data-nodo-id="{{$socio->nodo_id}}" {{old('socio_id') == $socio->id ? 'selected' : ''}}>{{$socio->razon_social}}</option>
                                    @endforeach
                                </select>
                                <small class="text-info">Debe seleccionar un socio para crear el usuario</small>
                            @else
                                <select class="form-control" id="socio_id" name="socio_id">
                                    <option selected disabled>Seleccione un Socio</option>
                                    @foreach($socios as $socio)
                                        <option value="{{$socio->id}}" data-nodo-id="{{$socio->nodo_id}}" {{old('socio_id') == $socio->id ? 'selected' : ''}}>{{$socio->razon_social}}</option>
                                    @endforeach
                                </select>
                            @endif
                            @error('socio_id')
                                <small style="color: red">{{$message}}</small>
                            @enderror
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
                        <button type="button" class="btn btn-warning" onclick="blanquearFormulario()">
                            <i class="fas fa-broom"></i> Limpiar Formulario
                        </button>
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

// Función para blanquear todos los datos del formulario
function blanquearFormulario() {
    // Limpiar campos de texto
    document.getElementById('name').value = '';
    document.getElementById('email').value = '';
    document.getElementById('password').value = '';
    document.getElementById('password_confirmation').value = '';
    
    // Resetear selects a su opción por defecto
    const nodoSelect = document.getElementById('nodo_id');
    const socioSelect = document.getElementById('socio_id');
    const rolSelect = document.getElementById('rol');
    
    // Solo resetear nodo si el usuario NO es de rol 'nodo'
    @if(!auth()->user()->hasRole('nodo'))
        // Resetear nodo a la primera opción (placeholder)
        nodoSelect.selectedIndex = 0;
    @endif
    
    // Limpiar socio y resetear opciones completas
    socioSelect.innerHTML = '<option selected disabled>Seleccione un Socio</option>';
    todasLasOpciones.forEach(opcion => {
        socioSelect.appendChild(opcion.cloneNode(true));
    });
    
    // Filtrar socios si hay un nodo seleccionado (para usuarios con rol nodo)
    @if(auth()->user()->hasRole('nodo'))
        filtrarSocios();
    @endif
    
    // Resetear rol a la primera opción (placeholder)
    rolSelect.selectedIndex = 0;
    
    // Rehabilitar botón de envío si estaba deshabilitado
    const submitButton = document.querySelector('button[type="submit"]');
    if (submitButton) {
        submitButton.innerHTML = 'Registrar Usuario';
        submitButton.disabled = false;
    }
    
    // Mostrar confirmación visual temporal
    //mostrarNotificacionLimpieza();
}

// Función para mostrar notificación de limpieza
function mostrarNotificacionLimpieza() {
    // Crear elemento de notificación
    const notification = document.createElement('div');
    notification.className = 'alert alert-info alert-dismissible fade show';
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.style.minWidth = '300px';
    notification.innerHTML = `
        <i class="fas fa-broom"></i> <strong>Formulario limpiado</strong> - Todos los campos han sido blanqueados
        <button type="button" class="close" onclick="this.parentElement.remove()">
            <span aria-hidden="true">&times;</span>
        </button>
    `;
    
    // Agregar al body
    document.body.appendChild(notification);
    
    // Auto-remover después de 3 segundos
    setTimeout(() => {
        if (notification && notification.parentElement) {
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        }
    }, 3000);
}

// Ejecutar cuando se carga la página
document.addEventListener('DOMContentLoaded', function() {
    const socioSelect = document.getElementById('socio_id');
    const nodoSelect = document.getElementById('nodo_id');
    const form = document.getElementById('usuarioForm');
    
    // Guardar todas las opciones originales (excepto la primera que es el placeholder)
    const opciones = socioSelect.querySelectorAll('option[data-nodo-id]');
    todasLasOpciones = Array.from(opciones);
    
    // Verificar si hay mensaje de éxito y blanquear formulario automáticamente
    const successMessage = document.getElementById('successMessage');
    if (successMessage) {
        // Blanquear formulario después de grabar exitosamente
        blanquearFormulario();
        console.log('Formulario blanqueado después de grabación exitosa');
        
        // Auto-cerrar mensaje de éxito después de 5 segundos
        setTimeout(function() {
            if (successMessage) {
                successMessage.style.opacity = '0';
                setTimeout(() => successMessage.remove(), 300);
            }
        }, 5000);
    } else {
        // Blanquear todos los campos al cargar la página (solo si no hay mensaje de éxito)
        blanquearFormulario();
        console.log('Formulario blanqueado - Todos los campos han sido limpiados');
    }
    
    // Si el usuario tiene rol nodo, filtrar socios automáticamente al cargar
    @if(auth()->user()->hasRole('nodo'))
        // Esperar un momento para que el DOM esté completamente cargado
        setTimeout(() => {
            filtrarSocios();
        }, 100);
    @endif
    
    // Agregar manejador de envío del formulario
    if (form) {
        form.addEventListener('submit', function(e) {
            // Validación especial para usuarios con rol nodo
            @if(auth()->user()->hasRole('nodo'))
                const socioSelect = document.getElementById('socio_id');
                if (!socioSelect.value || socioSelect.value === '') {
                    e.preventDefault();
                    alert('Debe seleccionar un socio para crear el usuario.');
                    socioSelect.focus();
                    return false;
                }
            @endif
            
            // Mostrar indicador de carga
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Grabando...';
                submitButton.disabled = true;
            }
        });
    }
});
</script>

@endsection