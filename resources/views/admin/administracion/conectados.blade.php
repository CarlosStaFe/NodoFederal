@extends('layouts.admin')

@section('content')

@section('content_header')
    <h1>Usuarios Conectados al Sistema</h1>
@stop

@section('content')
<div class="row">
    <!-- Estadísticas -->
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Usuarios Conectados</span>
                <span class="info-box-number" id="total-conectados">{{ $usuariosConectados->count() }}</span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-server"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Sesiones Activas</span>
                <span class="info-box-number">{{ $estadisticas['sesiones_activas'] }}</span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Tiempo Sesión</span>
                <span class="info-box-number">{{ $estadisticas['tiempo_sesion'] ?? 120 }} min</span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-danger"><i class="fas fa-user-friends"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Usuarios</span>
                <span class="info-box-number">{{ $estadisticas['total_usuarios'] ?? 0 }}</span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-users mr-2"></i>
                    Lista de Usuarios Conectados
                </h2>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" onclick="actualizarDatos()">
                        <i class="fas fa-sync-alt"></i> Actualizar
                    </button>
                    <button type="button" class="btn btn-info btn-sm" onclick="toggleAutoRefresh()">
                        <i class="fas fa-clock" id="auto-refresh-icon"></i>
                        <span id="auto-refresh-text">Auto: OFF</span>
                    </button>
                    <button type="button" class="btn btn-warning btn-sm" onclick="testFunction()" title="Test jQuery">
                        <i class="fas fa-bug"></i> Test
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="usuarios-conectados-table" class="table table-bordered table-striped table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center"><i class="fas fa-user"></i></th>
                                <th class="text-center"><i class="fas fa-envelope"></i></th>
                                <th class="text-center"><i class="fas fa-user-tag"></i></th>
                                <th class="text-center"><i class="fas fa-sitemap"></i></th>
                                <th class="text-center"><i class="fas fa-handshake"></i></th>
                                <th class="text-center"><i class="fas fa-clock"></i></th>
                                <th class="text-center"><i class="fas fa-hourglass-half"></i></th>
                                <th class="text-center"><i class="fas fa-map-marker-alt"></i></th>
                                <th class="text-center"><i class="bi bi-globe2"></i></th>
                                <th class="text-center"><i class="fas fa-cogs"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($usuariosConectados as $usuario)
                            <tr>
                                <td>
                                    <strong class="text-primary">{{ $usuario->name }}</strong>
                                </td>
                                <td>{{ $usuario->email }}</td>
                                <td>
                                    @php
                                        // Acceder al rol directamente desde las propiedades del objeto
                                        $rol = $usuario->rol ?? '';
                                        $rolColor = 'secondary';
                                        if($rol === 'admin') $rolColor = 'danger';
                                        elseif($rol === 'nodo') $rolColor = 'primary';
                                        elseif($rol === 'socio') $rolColor = 'info';
                                        elseif($rol === 'secretaria') $rolColor = 'warning';
                                        
                                        // Mapear nombres de roles para display
                                        $rolDisplay = match($rol) {
                                            'admin' => 'Administrador',
                                            'nodo' => 'Nodo',
                                            'socio' => 'Socio', 
                                            'secretaria' => 'Secretaria',
                                            default => 'Sin rol'
                                        };
                                    @endphp
                                    <span class="badge badge-{{ $rolColor }}">{{ $rolDisplay }}</span>
                                </td>
                                <td>
                                    @if($usuario->nodo_nombre)
                                        <span class="badge badge-primary">{{ $usuario->nodo_nombre }}</span>
                                    @else
                                        <span class="badge badge-secondary">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($usuario->socio_nombre)
                                        <span class="badge badge-info">{{ $usuario->socio_nombre }}</span>
                                    @else
                                        <span class="badge badge-secondary">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $usuario->last_activity_human }}</small>
                                </td>
                                <td>
                                    @php
                                        $badgeClass = 'success';
                                        if($usuario->tiempo_inactivo > 60) $badgeClass = 'danger';
                                        elseif($usuario->tiempo_inactivo > 30) $badgeClass = 'warning';
                                    @endphp
                                    <span class="badge badge-{{ $badgeClass }}">
                                        {{ $usuario->tiempo_inactivo }} min
                                    </span>
                                </td>
                                <td>
                                    <code class="text-sm">{{ $usuario->ip_address }}</code>
                                </td>
                                <td>
                                    @php
                                        $browser = '';
                                        $userAgent = $usuario->user_agent ?? '';
                                        if (str_contains($userAgent, 'Chrome')) $browser = '<i class="fab fa-chrome text-warning"></i> Chrome';
                                        elseif (str_contains($userAgent, 'Firefox')) $browser = '<i class="fab fa-firefox text-orange"></i> Firefox';
                                        elseif (str_contains($userAgent, 'Safari')) $browser = '<i class="fab fa-safari text-info"></i> Safari';
                                        elseif (str_contains($userAgent, 'Edge')) $browser = '<i class="fab fa-edge text-primary"></i> Edge';
                                        else $browser = '<i class="fas fa-browser"></i> Otro';
                                    @endphp
                                    <span title="{{ $userAgent }}">{!! $browser !!}</span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-danger" 
                                            onclick="desconectarUsuario({{ $usuario->id }}, '{{ $usuario->session_id }}', '{{ $usuario->name }}')"
                                            title="Desconectar usuario">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center">
                                    <div class="py-4">
                                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No hay usuarios conectados actualmente</h5>
                                        <p class="text-muted">Los usuarios aparecerán aquí cuando inicien sesión</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// FUNCIONES DIRECTAS E INLINE - DISPONIBLES INMEDIATAMENTE
function toggleAutoRefresh() {
    var textElement = document.getElementById('auto-refresh-text');
    var iconElement = document.getElementById('auto-refresh-icon');
    
    if (!textElement || !iconElement) return;
    
    var currentText = textElement.innerHTML || textElement.textContent || '';
    
    if (currentText.indexOf('OFF') !== -1) {
        textElement.innerHTML = 'Auto: ON';
        iconElement.className = 'fas fa-sync-alt fa-spin';
        
        // Crear intervalo global
        window.autoRefreshInterval = setInterval(function() {
            actualizarDatos();
        }, 30000);
        
        console.log('Auto-refresh activado');
    } else {
        textElement.innerHTML = 'Auto: OFF';
        iconElement.className = 'fas fa-clock';
        
        if (window.autoRefreshInterval) {
            clearInterval(window.autoRefreshInterval);
            window.autoRefreshInterval = null;
        }
        
        console.log('Auto-refresh desactivado');
    }
}

function actualizarDatos() {
    // Actualizar timestamp
    var timestampElement = document.getElementById('ultima-actualizacion');
    if (timestampElement) {
        var now = new Date();
        var timeString = now.getHours() + ':' + 
                        (now.getMinutes() < 10 ? '0' : '') + now.getMinutes() + ':' + 
                        (now.getSeconds() < 10 ? '0' : '') + now.getSeconds();
        timestampElement.innerHTML = timeString;
    }
    
    // Actualizar contador como demo
    var contadorElement = document.getElementById('total-conectados');
    if (contadorElement) {
        var currentValue = parseInt(contadorElement.innerHTML || '0');
        contadorElement.innerHTML = Math.max(currentValue, 0) + Math.floor(Math.random() * 3);
    }
    
    console.log('Datos actualizados:', new Date().toLocaleTimeString());
}

function testFunction() {
    console.log('=== TEST EJECUTADO ===');
    console.log('Auto-refresh activo:', window.autoRefreshInterval ? 'SÍ' : 'NO');
    console.log('Función ejecutada correctamente');
}

// Función para desconectar usuario específico
function desconectarUsuario(userId, sessionId, userName) {
    if (!confirm('¿Está seguro de desconectar al usuario "' + userName + '"?')) {
        return;
    }
    
    if (typeof $ !== 'undefined') {
        $.ajax({
            url: '{{ route("admin.administracion.conectados.desconectar") }}',
            method: 'POST',
            data: {
                user_id: userId,
                session_id: sessionId,
                _token: '{{ csrf_token() }}'
            },
            beforeSend: function() {
                if (typeof toastr !== 'undefined') {
                    toastr.info('Desconectando usuario...');
                }
            },
            success: function(data) {
                if (data.success) {
                    if (typeof toastr !== 'undefined') {
                        toastr.success(data.message);
                    }
                    // Actualizar la página o recargar la tabla
                    window.location.reload();
                } else {
                    if (typeof toastr !== 'undefined') {
                        toastr.warning(data.message);
                    }
                }
                console.log('Usuario desconectado:', data);
            },
            error: function(xhr, status, error) {
                console.error('Error al desconectar usuario:', error);
                if (typeof toastr !== 'undefined') {
                    toastr.error('Error al desconectar el usuario');
                }
            }
        });
    } else {
        alert('Error: jQuery no está disponible');
    }
}

// Función para desconectar todos los usuarios
function desconectarTodos() {
    if (!confirm('¿Está seguro de desconectar a TODOS los usuarios conectados?\n\nEsta acción no se puede deshacer.')) {
        return;
    }
    
    if (typeof $ !== 'undefined') {
        $.ajax({
            url: '{{ route("admin.administracion.conectados.desconectar-todos") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            beforeSend: function() {
                if (typeof toastr !== 'undefined') {
                    toastr.info('Desconectando todos los usuarios...');
                }
            },
            success: function(data) {
                if (data.success) {
                    if (typeof toastr !== 'undefined') {
                        toastr.success(data.message);
                    }
                    // Actualizar la página
                    window.location.reload();
                } else {
                    if (typeof toastr !== 'undefined') {
                        toastr.warning(data.message);
                    }
                }
                console.log('Usuarios desconectados:', data);
            },
            error: function(xhr, status, error) {
                console.error('Error al desconectar usuarios:', error);
                if (typeof toastr !== 'undefined') {
                    toastr.error('Error al desconectar los usuarios');
                }
            }
        });
    } else {
        alert('Error: jQuery no está disponible');
    }
}

// Verificar que las funciones están disponibles
console.log('Funciones inline cargadas:', {
    toggleAutoRefresh: typeof toggleAutoRefresh,
    actualizarDatos: typeof actualizarDatos,
    testFunction: typeof testFunction,
    desconectarUsuario: typeof desconectarUsuario,
    desconectarTodos: typeof desconectarTodos
});
</script>

@stop

@section('css')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<style>
.info-box-number {
    font-size: 1.8rem;
    font-weight: bold;
}

.table td, .table th {
    vertical-align: middle;
}

.badge {
    font-size: 0.85em;
}

code {
    font-size: 0.9em;
    color: #e83e8c;
    background-color: #f8f9fa;
    padding: 2px 4px;
    border-radius: 3px;
}

.card-title i {
    margin-right: 5px;
}

.table-hover tbody tr:hover {
    background-color: #f5f5f5;
}

/* DataTables custom styling */
.dataTables_wrapper .dataTables_length select {
    width: 60px;
}

.dataTables_wrapper .dataTables_filter input {
    border-radius: 4px;
}

.dt-buttons {
    margin-bottom: 15px;
}

.dt-button {
    margin-right: 5px;
}

/* Auto refresh animation */
.fa-spin {
    animation: fa-spin 2s infinite linear;
}

@keyframes fa-spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Cabecera con color claro personalizado */
.thead-light th {
    background-color: #25bcd6 !important;
    border-color: #090216;
    color: #054b91;
}
</style>
@stop

@section('js')
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>
<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
// Variables globales
var autoRefreshInterval = null;
var table = null;

// Las funciones están definidas inline en el HTML

// Función auxiliar para obtener iconos de navegador
function getBrowserIcon(userAgent) {
    if (!userAgent) return '<i class="fas fa-browser"></i> Desconocido';
    
    if (userAgent.indexOf('Chrome') !== -1) return '<i class="fab fa-chrome text-warning"></i> Chrome';
    if (userAgent.indexOf('Firefox') !== -1) return '<i class="fab fa-firefox text-orange"></i> Firefox';
    if (userAgent.indexOf('Safari') !== -1) return '<i class="fab fa-safari text-info"></i> Safari';
    if (userAgent.indexOf('Edge') !== -1) return '<i class="fab fa-edge text-primary"></i> Edge';
    return '<i class="fas fa-browser"></i> Otro';
}

console.log('Variables globales inicializadas');

function getBrowserIcon(userAgent) {
    if (!userAgent) return '<i class="fas fa-browser"></i> Desconocido';
    
    if (userAgent.includes('Chrome')) return '<i class="fab fa-chrome text-warning"></i> Chrome';
    if (userAgent.includes('Firefox')) return '<i class="fab fa-firefox text-orange"></i> Firefox';
    if (userAgent.includes('Safari')) return '<i class="fab fa-safari text-info"></i> Safari';
    if (userAgent.includes('Edge')) return '<i class="fab fa-edge text-primary"></i> Edge';
    return '<i class="fas fa-browser"></i> Otro';
}

function addCustomFilters() {
    if (typeof $ !== 'undefined' && $('#usuarios-conectados-table_wrapper').length) {
        $('#usuarios-conectados-table_wrapper').prepend(\`
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="filter-tiempo">Filtrar por actividad:</label>
                    <select id="filter-tiempo" class="form-control form-control-sm">
                        <option value="">Todos</option>
                        <option value="activo">Muy Activos (0-15 min)</option>
                        <option value="moderado">Moderados (15-30 min)</option>
                        <option value="inactivo">Inactivos (30+ min)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filter-nodo">Filtrar por nodo:</label>
                    <select id="filter-nodo" class="form-control form-control-sm">
                        <option value="">Todos los nodos</option>
                        @if(isset($usuariosConectados))
                            @foreach($usuariosConectados->pluck('nodo_nombre')->unique()->filter() as $nodo)
                                <option value="{{ $nodo }}">{{ $nodo }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filter-rol">Filtrar por rol:</label>
                    <select id="filter-rol" class="form-control form-control-sm">
                        <option value="">Todos los roles</option>
                        <option value="admin">Administrador</option>
                        <option value="secretaria">Secretaria</option>
                        <option value="nodo">Nodo</option>
                        <option value="socio">Socio</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>&nbsp;</label>
                    <div>
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i>
                            Última actualización: <span id="ultima-actualizacion">{{ now()->format('H:i:s') }}</span>
                        </small>
                    </div>
                </div>
            </div>
        \`);

        $('#filter-tiempo').on('change', function() {
            if (table) {
                var value = this.value;
                if (value === 'activo') {
                    table.column(6).search('(0|1[0-5])\\\\s*min', true, false).draw();
                } else if (value === 'moderado') {
                    table.column(6).search('(1[6-9]|2[0-9]|30)\\\\s*min', true, false).draw();
                } else if (value === 'inactivo') {
                    table.column(6).search('([3-9][0-9]|[0-9]{3,})\\\\s*min', true, false).draw();
                } else {
                    table.column(6).search('').draw();
                }
            }
        });

        $('#filter-nodo').on('change', function() {
            if (table) {
                table.column(3).search(this.value).draw();
            }
        });

        $('#filter-rol').on('change', function() {
            if (table) {
                table.column(2).search(this.value).draw();
            }
        });
    }
}

function getBrowserIcon(userAgent) {
    if (!userAgent) return '<i class="fas fa-browser"></i> Desconocido';
    
    if (userAgent.includes('Chrome')) return '<i class="fab fa-chrome text-warning"></i> Chrome';
    if (userAgent.includes('Firefox')) return '<i class="fab fa-firefox text-orange"></i> Firefox';
    if (userAgent.includes('Safari')) return '<i class="fab fa-safari text-info"></i> Safari';
    if (userAgent.includes('Edge')) return '<i class="fab fa-edge text-primary"></i> Edge';
    return '<i class="fas fa-browser"></i> Otro';
}

function addCustomFilters() {
    // Filtro por tiempo de inactividad
    $('#usuarios-conectados-table_wrapper').prepend(`
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="filter-tiempo">Filtrar por actividad:</label>
                <select id="filter-tiempo" class="form-control form-control-sm">
                    <option value="">Todos</option>
                    <option value="activo">Muy Activos (0-15 min)</option>
                    <option value="moderado">Moderados (15-30 min)</option>
                    <option value="inactivo">Inactivos (30+ min)</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="filter-nodo">Filtrar por nodo:</label>
                <select id="filter-nodo" class="form-control form-control-sm">
                    <option value="">Todos los nodos</option>
                    @if(isset($usuariosConectados))
                        @foreach($usuariosConectados->pluck('nodo_nombre')->unique()->filter() as $nodo)
                            <option value="{{ $nodo }}">{{ $nodo }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-3">
                <label for="filter-rol">Filtrar por rol:</label>
                <select id="filter-rol" class="form-control form-control-sm">
                    <option value="">Todos los roles</option>
                    <option value="admin">Administrador</option>
                    <option value="secretaria">Secretaria</option>
                    <option value="nodo">Nodo</option>
                    <option value="socio">Socio</option>
                </select>
            </div>
            <div class="col-md-3">
                <label>&nbsp;</label>
                <div>
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i>
                        Última actualización: <span id="ultima-actualizacion">{{ now()->format('H:i:s') }}</span>
                    </small>
                </div>
            </div>
        </div>
    `);

    // Eventos para filtros personalizados
    $('#filter-tiempo').on('change', function() {
        var value = this.value;
        if (value === 'activo') {
            table.column(6).search('(0|1[0-5])\\s*min', true, false).draw();
        } else if (value === 'moderado') {
            table.column(6).search('(1[6-9]|2[0-9]|30)\\s*min', true, false).draw();
        } else if (value === 'inactivo') {
            table.column(6).search('([3-9][0-9]|[0-9]{3,})\\s*min', true, false).draw();
        } else {
            table.column(6).search('').draw();
        }
    });

    $('#filter-nodo').on('change', function() {
        table.column(3).search(this.value).draw();
    });

    $('#filter-rol').on('change', function() {
        table.column(2).search(this.value).draw();
    });

    $('#filter-rol').on('change', function() {
        table.column(2).search(this.value).draw();
    });
}

// Inicialización cuando el documento esté listo
$(document).ready(function() {
    console.log('=== INICIALIZANDO PÁGINA ===');
    console.log('jQuery disponible:', typeof $ !== 'undefined');
    console.log('Funciones disponibles:', {
        toggleAutoRefresh: typeof window.toggleAutoRefresh,
        testFunction: typeof window.testFunction,
        actualizarDatos: typeof window.actualizarDatos
    });
    
    // Configurar toastr
    if (typeof toastr !== 'undefined') {
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "3000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
        console.log('Toastr configurado');
    } else {
        console.log('Toastr no disponible');
    }
    
    // Inicializar DataTable
    table = $('#usuarios-conectados-table').DataTable({
        "responsive": true,
        "processing": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
        },
        "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
               "<'row'<'col-sm-12 col-md-12'B>>" +
               "<'row'<'col-sm-12'tr>>" +
               "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        "buttons": [
            {
                extend: 'copy',
                text: '<i class="fas fa-copy"></i> Copiar',
                className: 'btn btn-secondary btn-sm'
            },
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-success btn-sm',
                title: 'Usuarios Conectados - ' + new Date().toLocaleDateString()
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn btn-danger btn-sm',
                title: 'Usuarios Conectados',
                orientation: 'landscape',
                pageSize: 'A4'
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Imprimir',
                className: 'btn btn-info btn-sm',
                title: 'Usuarios Conectados al Sistema'
            }
        ],
        "pageLength": 25,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
        "order": [[5, "desc"]], // Ordenar por última actividad descendente
        "columnDefs": [
            {
                "targets": [0], // Usuario
                "width": "160px"
            },
            {
                "targets": [1], // Email
                "width": "180px"
            },
            {
                "targets": [2], // Rol
                "width": "100px",
                "orderable": false
            },
            {
                "targets": [3], // Nodo
                "width": "100px",
                "orderable": false
            },
            {
                "targets": [4], // Socio
                "width": "100px",
                "orderable": false
            },
            {
                "targets": [5], // Última actividad
                "width": "100px"
            },
            {
                "targets": [6], // Tiempo inactivo
                "width": "100px",
                "orderable": false
            },
            {
                "targets": [7], // IP
                "width": "100px"
            },
            {
                "targets": [8], // Navegador
                "width": "180px"
            },
            {
                "targets": [9], // Acciones
                "width": "80px",
                "orderable": false,
                "className": "text-center"
            }
        ],
        "initComplete": function() {
            console.log('DataTable inicializada');
            // Agregar filtros personalizados
            addCustomFilters();
        }
    });
    
    console.log('=== INICIALIZACIÓN COMPLETADA ===');
});

// Verificar funciones finales
console.log('ESTADO FINAL DE FUNCIONES:', {
    toggleAutoRefresh: typeof toggleAutoRefresh,
    testFunction: typeof testFunction,
    actualizarDatos: typeof actualizarDatos,
    getBrowserIcon: typeof getBrowserIcon,
    addCustomFilters: typeof addCustomFilters
});
</script>
@stop