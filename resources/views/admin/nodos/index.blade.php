@extends('layouts.admin')

@section('content')
    <h1>Listado de Nodos</h1>

    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Nodos Registrados</h3>
            
                <div class="card-tools">
                    <a href="{{url('/admin/nodos/create')}}" class="btn btn-primary">
                        Registrar Nodo
                    </a>
                </div>
            </div>
        
            <div class="card-body">
                <table id="example1" class="table table-striped table-bordered table-hover table-sm">
                    <thead style="background-color:rgb(14, 107, 169); color: white;">
                        <tr>
                            <th style="text-align: center; width: 5%;">#</th>
                            <th style="text-align: center; width: 6%;">NRO.</th>
                            <th style="text-align: center; width: 6%;">FACT.</th>
                            <th style="text-align: center; width: 37%;">NOMBRE</th>
                            <th style="text-align: center; width: 15%;">TELÉFONO</th>
                            <th style="text-align: center; width: 8%;">ESTADO</th>
                            <th style="text-align: center; width: 11%;">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $linea = 1; ?>
                        @foreach($nodos as $nodo)
                        <tr>
                            <td style="text-align: right;">{{ $linea++ }}</td>
                            <td style="text-align: right;">{{ $nodo->numero }}</td>
                            <td style="text-align: right;">{{ $nodo->factura }}</td>
                            <td>{{ $nodo->nombre }}</td>
                            <td>{{ $nodo->telefono }}</td>
                            <td>{{ $nodo->estado }}</td>
                            <td>
                                <a href="{{url('admin/nodos/'.$nodo->id)}}" type="button" class="btn btn-success btn-sm" title="Ver nodo"><i class="bi bi-eye"></i></a>
                                <a href="{{url('admin/nodos/'.$nodo->id.'/edit')}}" type="button" class="btn btn-info btn-sm" title="Editar nodo"><i class="bi bi-pencil"></i></a>
                                @if(auth()->user()->roles->first()->name === 'admin')
                                    <a href="{{url('admin/nodos/'.$nodo->id.'/confirm-delete')}}" type="button" class="btn btn-danger btn-sm" title="Eliminar nodo"><i class="bi bi-trash"></i></a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>                
            </div>
        </div>
    </div>

    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
                "pageLength": 10,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                "language": {
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "zeroRecords": "No se encontraron resultados",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "search": "Buscar:",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    },
                },
                "columnDefs": [
                    { "orderable": false, "targets": [0, 6] }
                ]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection