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
                            <th style="text-align: center; width: 4%;">#</th>
                            <th style="text-align: center; width: 5%;">TIPO</th>
                            <th style="text-align: center; width: 7%;">DOCUM.</th>
                            <th style="text-align: center; width: 3%;">S.</th>
                            <th style="text-align: center; width: 30%;">APELLIDO Y NOMBRES</th>
                            <th style="text-align: center; width: 10%;">C.U.I.T.</th>
                            <th style="text-align: center; width: 10%;">FECHA NAC.</th>
                            <th style="text-align: center; width: 5%;">EDAD</th>
                            <th style="text-align: center; width: 10%;">PROVINCIA</th>
                            <th style="text-align: center; width: 16%;">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Se carga dinámicamente via AJAX -->
                    </tbody>
                </table>                
            </div>
        </div>
    </div>

    <script>
        $(function() {
            $("#example1").DataTable({
                "processing": true,
                "serverSide": true,
                "searching": true,
                "searchDelay": 500,
                "ajax": {
                    "url": "{{ route('admin.clientes.data') }}",
                    "type": "GET",
                    "data": function(d) {
                        // Asegurar que solo se envíe la búsqueda del servidor
                        console.log('Búsqueda enviada:', d.search.value);
                        return d;
                    },
                    "error": function(xhr, error, thrown) {
                        console.log("Error en AJAX:", xhr, error, thrown);
                        alert("Error al cargar datos: " + error);
                    }
                },
                "columns": [
                    { "data": 0, "name": "numero", "orderable": false, "searchable": false },
                    { "data": 1, "name": "tipodoc", "searchable": true },
                    { "data": 2, "name": "documento", "searchable": true },
                    { "data": 3, "name": "sexo", "searchable": true },
                    { "data": 4, "name": "apelnombres", "searchable": true },
                    { "data": 5, "name": "cuit", "searchable": true },
                    { "data": 6, "name": "nacimiento", "searchable": false },
                    { "data": 7, "name": "edad", "orderable": false, "searchable": false },
                    { "data": 8, "name": "provincia", "searchable": true },
                    { "data": 9, "name": "acciones", "orderable": false, "searchable": false }
                ],
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
                "pageLength": 10,
                "language": {
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "zeroRecords": "No se encontraron resultados. Escriba algo en el campo de búsqueda para mostrar datos.",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "Escriba en el campo de búsqueda para mostrar resultados",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "search": "Buscar:",
                    "processing": "Procesando...",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "previous": "Anterior",
                        "next": "Siguiente"
                    },
                },
                "columnDefs": [
                    { "orderable": false, "targets": [0,7,9] },
                    { "className": "text-right", "targets": [0] }
                ],
                "drawCallback": function(settings) {
                    console.log('DataTable redibujado con', settings.json.recordsFiltered, 'registros');
                }
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection