@extends('layouts.admin')

@section('content')

<div class="row">
    <h1>Listado de Operaciones</h1>
</div>

<div class="col-md-12">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Filtrar Operaciones</h3>
        </div>
        <div class="card-body">
            <form id="operacionesForm" method="GET">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nodo_id">Nodo</label>
                            <select class="form-control" id="nodo_id" name="nodo_id">
                                <option value="">Todos los nodos</option>
                                @foreach ($nodos->sortBy('nombre') as $nodo)
                                    <option value="{{ $nodo->id }}"
                                        @if(auth()->user()->hasRole('nodo') && auth()->user()->nodo_id == $nodo->id) selected @endif
                                        @if(auth()->user()->hasRole('socio') && auth()->user()->socio && auth()->user()->socio->nodo_id == $nodo->id) selected @endif
                                    >{{ $nodo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="socio_id">Socio</label>
                            <select class="form-control" id="socio_id" name="socio_id">
                                <option value="">Todos los socios</option>
                                @if(auth()->user()->hasRole('socio'))
                                    {{-- Si es socio, mostrar su propio socio preseleccionado --}}
                                    @foreach ($socios as $socio)
                                        <option value="{{ $socio->id }}" selected>{{ $socio->razon_social }}</option>
                                    @endforeach
                                @else
                                    {{-- Para otros roles, se cargará dinámicamente --}}
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="desde_fecha">Desde Fecha</label>
                            <input id="desde_fecha" name="desde_fecha" type="date" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="hasta_fecha">Hasta Fecha</label>
                            <input id="hasta_fecha" name="hasta_fecha" type="date" class="form-control">
                        </div>
                    </div>
                </div>
                <br>
                <div>
                    <button type="button" id="limpiar" class="btn btn-primary me-2">Limpiar</button>
                    <button type="submit" class="btn btn-success me-2">Buscar</button>
                    <a href="{{ url('admin') }}" class="btn btn-warning">Panel Principal</a>
                </div>
            </form>
            <br>
            
            <div id="contadorResultados" style="display: none;" class="alert alert-info">
                <strong>Datos cargados desde la base de datos</strong>
            </div>

            <div class="card-body">
                <table id="operacionesTable" class="table table-striped table-bordered table-hover table-sm" style="font-size: 0.9em; width: 100%;">
                    <thead style="background-color:rgb(14, 107, 169); color: white;">
                        <tr>
                            <th class="text-center">N° Op.</th>
                            <th class="text-center">Cliente (CUIT)</th>
                            <th class="text-center">Apellido y Nombres</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Tipo</th>
                            <th class="text-center">Fecha Op.</th>
                            <th class="text-center">Fecha Estado</th>
                            <th class="text-center">Nodo</th>
                            <th class="text-center">Socio</th>
                            <th class="text-center">Total</th>
                            <th class="text-center" style="width: 80px;">Acciones</th>
                        </tr>
                    </thead>
                </table>
                
                <div id="sinResultados" style="display: none;" class="alert alert-warning text-center">
                    <strong>No se encontraron operaciones con los criterios seleccionados.</strong><br>
                    <small>Intente modificar los filtros o ampliar el rango de fechas.</small>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let dataTable = null;

    // Cargar socios automáticamente al iniciar si hay un nodo preseleccionado
    @if(auth()->user()->hasRole('nodo'))
        // Si es usuario tipo nodo, cargar sus socios automáticamente
        const nodoIdInicial = $('#nodo_id').val();
        if (nodoIdInicial) {
            cargarSociosPorNodo(nodoIdInicial);
        }
    @endif

    // Función para cargar socios por nodo
    function cargarSociosPorNodo(nodoId) {
        const $socioSelect = $('#socio_id');
        
        if (nodoId) {
            $.get(`/admin/operaciones/listar/socios/${nodoId}`)
                .done(function(socios) {
                    // Limpiar opciones actuales excepto la primera
                    $socioSelect.html('<option value="">Todos los socios</option>');
                    
                    socios.forEach(function(socio) {
                        $socioSelect.append(`<option value="${socio.id}">${socio.razon_social}</option>`);
                    });
                })
                .fail(function(xhr) {
                    if (xhr.status === 403) {
                        alert('No tiene permisos para ver los socios de este nodo');
                    }
                });
        } else {
            $socioSelect.html('<option value="">Todos los socios</option>');
        }
    }

    // Cambio dinámico de socios según el nodo seleccionado
    $('#nodo_id').change(function() {
        const nodoId = $(this).val();
        cargarSociosPorNodo(nodoId);
        
        // Recargar tabla si ya está inicializada
        if (dataTable) {
            dataTable.ajax.reload();
        }
    });

    // Inicializar DataTable
    function initDataTable() {
        if (dataTable) {
            dataTable.destroy();
        }

        dataTable = $('#operacionesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("admin.operaciones.listar") }}',
                type: 'GET',
                data: function(d) {
                    d.nodo_id = $('#nodo_id').val();
                    d.socio_id = $('#socio_id').val();
                    d.desde_fecha = $('#desde_fecha').val();
                    d.hasta_fecha = $('#hasta_fecha').val();
                },
                error: function(xhr, error, code) {
                    console.error('Error en DataTables:', xhr, error, code);
                    if (xhr.status === 403) {
                        alert('No tiene permisos para realizar esta consulta');
                    } else {
                        alert('Error al cargar las operaciones');
                    }
                }
            },
            columns: [
                { data: 'numero', name: 'numero', orderable: true, searchable: false },
                { data: 'cuit', name: 'cuit', orderable: true, searchable: true },
                { data: 'apellidos', name: 'apellidos', orderable: true, searchable: true },
                { data: 'estado', name: 'estado_actual', orderable: true, searchable: false },
                { data: 'tipo', name: 'tipo', orderable: true, searchable: false },
                { data: 'fecha_operacion', name: 'fecha_operacion', orderable: true, searchable: false },
                { data: 'fecha_estado', name: 'fecha_estado', orderable: true, searchable: false },
                { data: 'nodo', name: 'nodo', orderable: true, searchable: true },
                { data: 'socio', name: 'socio', orderable: true, searchable: true },
                { data: 'total', name: 'total', orderable: true, searchable: false },
                { data: 'acciones', name: 'acciones', orderable: false, searchable: false }
            ],
            order: [[4, 'desc']], // Ordenar por fecha de operación descendente
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
            searching: true, // Habilitar búsqueda
            language: {
                processing: "Procesando...",
                search: "Buscar:",
                lengthMenu: "Mostrar _MENU_ registros",
                info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                infoFiltered: "(filtrado de un total de _MAX_ registros)",
                infoPostFix: "",
                loadingRecords: "Cargando...",
                zeroRecords: "No se encontraron resultados",
                emptyTable: "Ningún dato disponible en esta tabla",
                paginate: {
                    first: "Primero",
                    previous: "Anterior",
                    next: "Siguiente",
                    last: "Último"
                },
                aria: {
                    sortAscending: ": Activar para ordenar la columna de manera ascendente",
                    sortDescending: ": Activar para ordenar la columna de manera descendente"
                }
            },
            responsive: true,
            autoWidth: false,
            stateSave: false,
            deferRender: true,
            dom: '<"row"<"col-sm-6"l><"col-sm-6"f>>' +
                 '<"row"<"col-sm-12"tr>>' +
                 '<"row"<"col-sm-5"i><"col-sm-7"p>>',
            drawCallback: function(settings) {
                const api = this.api();
                const recordsTotal = api.page.info().recordsTotal;
                if (recordsTotal > 0) {
                    $('#contadorResultados').show();
                    $('#sinResultados').hide();
                } else {
                    $('#contadorResultados').hide();
                    $('#sinResultados').show();
                }
            }
        });
    }

    // Envío del formulario
    $('#operacionesForm').submit(function(e) {
        e.preventDefault();
        
        if (!dataTable) {
            initDataTable();
        } else {
            dataTable.ajax.reload();
        }
    });

    // Botón limpiar
    $('#limpiar').click(function() {
        $('#operacionesForm')[0].reset();
        $('#socio_id').html('<option value="">Todos los socios</option>');
        
        if (dataTable) {
            dataTable.destroy();
            dataTable = null;
            $('#contadorResultados, #sinResultados').hide();
        }
    });

    // Eventos para filtros dinámicos
    $('#socio_id, #desde_fecha, #hasta_fecha').change(function() {
        if (dataTable) {
            dataTable.ajax.reload();
        }
    });

    // Cargar datos automáticamente al iniciar si no hay filtros específicos
    @if(!auth()->user()->hasRole('socio'))
        // Solo auto-cargar para usuarios que no sean socio
        setTimeout(function() {
            if (!dataTable) {
                initDataTable();
            }
        }, 500);
    @endif
});
</script>
@endsection