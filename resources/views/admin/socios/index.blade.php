@extends('layouts.admin')

@section('content')
    <h1>Listado de Socios</h1>

    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Socios Registrados</h3>
            
                <div class="card-tools">
                    <a href="{{url('/admin/socios/create')}}" class="btn btn-primary">
                        Registrar Socio
                    </a>
                </div>
            </div>
        
            <div class="card-body">
                <table id="example1" class="table table-striped table-bordered table-hover table-sm">
                    <thead style="background-color:rgb(14, 107, 169); color: white;">
                        <tr>
                            <th style="text-align: center; width: 5%;">#</th>
                            <th style="text-align: center; width: 8%;">NRO.</th>
                            <th style="text-align: center; width: 25%;">NOMBRE</th>
                            <th style="text-align: center; width: 11%;">CLASE</th>
                            <th style="text-align: center; width: 20%;">NODO</th>
                            <th style="text-align: center; width: 11%;">TELÉFONO</th>
                            <th style="text-align: center; width: 9%;">ESTADO</th>
                            <th style="text-align: center; width: 11%;">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $linea = 1; ?>
                        @foreach($socios as $socio)
                        <tr>
                            <td style="text-align: right;">{{ $linea++ }}</td>
                            <td style="text-align: right;">{{ $socio->numero }}</td>
                            <td>{{ $socio->razon_social }}</td>
                            <td>{{ $socio->clase }}</td>
                            <td>
                                @if($socio->nodo)
                                    {{ $socio->nodo->numero }} - {{ $socio->nodo->nombre }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $socio->telefono }}</td>
                            <td>{{ $socio->estado }}</td>
                            <td>
                                <a href="{{url('admin/socios/'.$socio->id)}}" type="button" class="btn btn-success btn-sm"><i class="bi bi-eye"></i></a>
                                <a href="{{url('admin/socios/'.$socio->id.'/edit')}}" type="button" class="btn btn-info btn-sm"><i class="bi bi-pencil"></i></a>
                                <a href="{{url('admin/socios/'.$socio->id.'/confirm-delete')}}" type="button" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></a>
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
                    { "orderable": false, "targets": [0, 7] }
                ]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection