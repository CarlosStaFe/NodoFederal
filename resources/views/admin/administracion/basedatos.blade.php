@extends('layouts.admin')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="row">
    <h1>Administración de Base de Datos</h1>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill"></i>
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="col-md-12">
    <!-- Sección Backup -->
    <div class="card card-outline card-success mb-4">
        <div class="card-header">
            <h3 class="card-title">
                <i class="bi bi-download"></i>
                Backup de Base de Datos
            </h3>
        </div>

        <div class="card-body">
            <p class="text-muted">Generar una copia de seguridad de la base de datos actual.</p>
            <form action="{{url('admin/administracion/basedatos/backup')}}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="backup_name">Nombre del Backup (opcional)</label>
                            <input type="text" class="form-control" id="backup_name" name="backup_name" 
                                   placeholder="backup_{{date('Y-m-d_H-i-s')}}" 
                                   value="backup_{{date('Y-m-d_H-i-s')}}">
                            <small class="form-text text-muted">Si no especifica un nombre, se generará automáticamente.</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="backup_tables">Incluir Tablas</label>
                            <select class="form-control" id="backup_tables" name="backup_tables">
                                <option value="all" selected>Todas las tablas</option>
                                <option value="structure_only">Solo estructura</option>
                                <option value="data_only">Solo datos</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-download"></i>
                        Generar Backup
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Sección Restore -->
    <div class="card card-outline card-warning mb-4">
        <div class="card-header">
            <h3 class="card-title">
                <i class="bi bi-upload"></i>
                Restaurar Base de Datos
            </h3>
        </div>

        <div class="card-body">
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i>
                <strong>¡ATENCIÓN!</strong> Esta operación reemplazará todos los datos actuales de la base de datos. 
                Se recomienda hacer un backup antes de proceder.
            </div>
            <p class="text-muted">Restaurar la base de datos desde un archivo de backup SQL.</p>
            <form action="{{url('admin/administracion/basedatos/restore')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="backup_file">Archivo de Backup</label>
                            <input type="file" class="form-control-file" id="backup_file" name="backup_file" 
                                   accept=".sql,.zip" required>
                            <small class="form-text text-muted">Archivos permitidos: .sql, .zip (máximo 50MB)</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="restore_mode">Modo de Restauración</label>
                            <select class="form-control" id="restore_mode" name="restore_mode">
                                <option value="replace">Reemplazar completamente</option>
                                <option value="merge">Fusionar datos</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="confirm_restore" name="confirm_restore" required>
                        <label class="form-check-label" for="confirm_restore">
                            Confirmo que he realizado un backup de la base de datos actual y entiendo 
                            que esta operación puede eliminar datos existentes.
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-upload"></i>
                        Restaurar Base de Datos
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Sección Lista de Backups -->
    <div class="card card-outline card-info">
        <div class="card-header">
            <h3 class="card-title">
                <i class="bi bi-list-ul"></i>
                Backups Disponibles
            </h3>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="backupsTable">
                    <thead>
                        <tr>
                            <th>Nombre del Archivo</th>
                            <th>Fecha de Creación</th>
                            <th>Tamaño</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Los datos se cargarán vía AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Inicializar DataTable
    var table = $('#backupsTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
        },
        "order": [[ 1, "desc" ]], // Ordenar por fecha de creación descendente
        "pageLength": 10,
        "processing": true,
        "serverSide": false,
        "ajax": {
            "url": "{{url('admin/administracion/basedatos/backups')}}",
            "type": "GET"
        },
        "columns": [
            { "data": "name" },
            { "data": "created_at" },
            { "data": "size" },
            { 
                "data": null,
                "orderable": false,
                "render": function(data, type, row) {
                    var downloadUrl = "{{url('admin/administracion/basedatos/download')}}/" + encodeURIComponent(row.name);
                    var deleteUrl = "{{url('admin/administracion/basedatos')}}/" + encodeURIComponent(row.name);
                    
                    return `
                        <a href="${downloadUrl}" class="btn btn-sm btn-info" title="Descargar">
                            <i class="fas fa-download"></i>
                        </a>
                        <button class="btn btn-sm btn-danger delete-backup" 
                                data-filename="${row.name}" 
                                data-url="${deleteUrl}" 
                                title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    `;
                }
            }
        ]
    });

    // Recargar tabla después de generar backup
    $('form[action*="backup"]').on('submit', function() {
        setTimeout(function() {
            table.ajax.reload();
        }, 2000);
    });

    // Manejar eliminación de backups
    $('#backupsTable').on('click', '.delete-backup', function(e) {
        e.preventDefault();
        
        var filename = $(this).data('filename');
        var url = $(this).data('url');
        
        console.log('Eliminando backup:', filename, 'URL:', url); // Debug
        
        if (confirm('¿Está seguro que desea eliminar el backup "' + filename + '"?')) {
            // Verificar que tenemos el token CSRF
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            if (!csrfToken) {
                console.error('Token CSRF no encontrado');
                alert('Error: Token de seguridad no encontrado. Recargue la página e intente nuevamente.');
                return;
            }

            $.ajax({
                url: url,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                beforeSend: function() {
                    // Mostrar indicador de carga
                    $('button[data-filename="' + filename + '"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
                },
                success: function(response) {
                    console.log('Respuesta exitosa:', response); // Debug
                    table.ajax.reload();
                    if (typeof toastr !== 'undefined') {
                        toastr.success('Backup eliminado correctamente');
                    } else {
                        alert('Backup eliminado correctamente');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al eliminar:', xhr, status, error); // Debug
                    console.error('Response text:', xhr.responseText); // Debug adicional
                    
                    var errorMsg = 'Error al eliminar el backup';
                    
                    if (xhr.status === 403) {
                        errorMsg = 'No tiene permisos suficientes para eliminar backups. Contacte al administrador.';
                    } else if (xhr.status === 404) {
                        errorMsg = 'El archivo backup no fue encontrado.';
                    } else if (xhr.status === 419) {
                        errorMsg = 'Sesión expirada. Recargue la página e intente nuevamente.';
                    } else if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMsg += ': ' + xhr.responseJSON.error;
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg += ': ' + xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        errorMsg += ': ' + xhr.responseText;
                    }
                    
                    if (typeof toastr !== 'undefined') {
                        toastr.error(errorMsg);
                    } else {
                        alert(errorMsg);
                    }
                },
                complete: function() {
                    // Restaurar botón
                    $('button[data-filename="' + filename + '"]').prop('disabled', false).html('<i class="fas fa-trash"></i>');
                }
            });
        }
    });

    // Confirmación antes de restaurar
    $('form[action*="restore"]').on('submit', function(e) {
        if (!confirm('¿Está seguro que desea restaurar la base de datos? Esta acción no se puede deshacer.')) {
            e.preventDefault();
            return false;
        }
    });

    // Validación del archivo
    $('#backup_file').on('change', function() {
        const file = this.files[0];
        if (file) {
            const maxSize = 50 * 1024 * 1024; // 50MB
            if (file.size > maxSize) {
                alert('El archivo es demasiado grande. Máximo permitido: 50MB');
                this.value = '';
                return false;
            }

            const allowedTypes = ['.sql', '.zip'];
            const fileName = file.name.toLowerCase();
            const isValidType = allowedTypes.some(type => fileName.endsWith(type));
            
            if (!isValidType) {
                alert('Tipo de archivo no permitido. Solo se permiten archivos .sql y .zip');
                this.value = '';
                return false;
            }
        }
    });
});
</script>

@endsection