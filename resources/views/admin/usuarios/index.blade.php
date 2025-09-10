@extends('layouts.admin')

@section('content')
    <h1>Listado de Usuarios</h1>

    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Usuarios Registrados</h3>
            
                <div class="card-tools">
                    <a href="{{url('/admin/usuarios/create')}}" class="btn btn-primary">
                        Registrar Usuario
                    </a>
                </div>
            </div>
        
            <div class="card-body">
                <table id="example1" class="table table-striped table-bordered table-hover table-sm">
                    <thead style="background-color:rgb(14, 107, 169); color: white;">
                        <tr>
                            <th style="text-align: center; width: 5%;">#</th>
                            <th style="text-align: center; width: 13%;">USUARIO</th>
                            <th style="text-align: center; width: 10%;">ROL</th>
                            <th style="text-align: center; width: 20%;">E-MAIL</th>
                            <th style="text-align: center; width: 21%;">NODO</th>
                            <th style="text-align: center; width: 20%;">SOCIO</th>
                            <th style="text-align: center; width: 11%;">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $linea = 1; ?>
                        @foreach($usuarios as $usuario)
                        <tr>
                            <td style="text-align: right;">{{ $linea++ }}</td>
                            <td>{{ $usuario->name }}</td>
                            <td>{{ $usuario->roles->first()->name ?? '' }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>{{ $usuario->nodo->nombre ?? '' }}</td>
                            <td>{{ $usuario->socio->razon_social ?? '' }}</td>
                            <td>
                                <a href="{{url('admin/usuarios/'.$usuario->id)}}" type="button" class="btn btn-success btn-sm" title="Ver usuario"><i class="bi bi-eye"></i></a>
                                <a href="{{url('admin/usuarios/'.$usuario->id.'/edit')}}" type="button" class="btn btn-info btn-sm" title="Editar usuario"><i class="bi bi-pencil"></i></a>
                                @if(auth()->user()->roles->first()->name === 'admin')
                                    <a href="{{url('admin/usuarios/'.$usuario->id.'/confirm-delete')}}" type="button" class="btn btn-danger btn-sm" title="Eliminar usuario"><i class="bi bi-trash"></i></a>
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
                    { "orderable": false, "targets": [0,5] }
                ]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection