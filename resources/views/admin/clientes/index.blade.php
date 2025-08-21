@extends('layouts.admin')

@section('content')
    <h1>Listado de Clientes</h1>

    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Clientes Registrados</h3>
            
                <div class="card-tools">
                    <a href="{{url('/admin/clientes/create')}}" class="btn btn-primary">
                        Registrar Cliente
                    </a>
                </div>
            </div>
        
            <div class="card-body">
                <table id="example1" class="table table-striped table-bordered table-hover table-sm">
                    <thead style="background-color:rgb(14, 107, 169); color: white;">
                        <tr>
                            <th style="text-align: center; width: 5%;">#</th>
                            <th style="text-align: center; width: 6%;">TIPO</th>
                            <th style="text-align: center; width: 8%;">DOCUM.</th>
                            <th style="text-align: center; width: 4%;">S.</th>
                            <th style="text-align: center; width: 28%;">APELLIDO Y NOMBRES</th>
                            <th style="text-align: center; width: 10%;">C.U.I.T.</th>
                            <th style="text-align: center; width: 18%;">ESTADO</th>
                            <th style="text-align: center; width: 10%;">FECHA EST.</th>
                            <th style="text-align: center; width: 11%;">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $linea = 1; ?>
                        @foreach($clientes as $cliente)
                        <tr>
                            <td style="text-align: right;">{{ $linea++ }}</td>
                            <td>{{ $cliente->tipodoc }}</td>
                            <td>{{ $cliente->documento }}</td>
                            <td style="text-align: center;">{{ $cliente->sexo }}</td>
                            <td>{{ $cliente->apelnombres }}</td>
                            <td>{{ $cliente->cuit }}</td>
                            <td>{{ $cliente->estado }}</td>
                            <td>{{ \Carbon\Carbon::parse($cliente->fechaestado)->format('d-m-Y') }}</td>
                            <td>
                                <a href="{{url('admin/clientes/'.$cliente->id)}}" type="button" class="btn btn-success btn-sm"><i class="bi bi-eye"></i></a>
                                <a href="{{url('admin/clientes/'.$cliente->id.'/edit')}}" type="button" class="btn btn-info btn-sm"><i class="bi bi-pencil"></i></a>
                                <a href="{{url('admin/clientes/'.$cliente->id.'/confirm-delete')}}" type="button" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></a>
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
                    { "orderable": false, "targets": [0,8] }
                ]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection