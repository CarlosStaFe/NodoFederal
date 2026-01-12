
@section('head')
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/ICONO_LF.ico') }}" />
@endsection

@extends('layouts.admin')

@section('content')

<div class="row">
    <h1>Buscar Operación</h1>
</div>

<div class="col-md-12">
    <div class="card card-outline card-warning">
        <div class="card-header">
            <h3 class="card-title">Datos del cliente</h3>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.operaciones.show') }}" method="GET" id="formOperacion">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="cuit">C.U.I.T.</label><b>*</b>
                            <input type="text" class="form-control" value="{{ old('cuit', isset($cuit) ? $cuit : '') }}" id="cuit" name="cuit" placeholder="C.U.I.T." required autocomplete="off" autofocus>
                            @error('cuit')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-1 col-sm-4 position-relative">
                        <div class="form-group">
                            <label for="tipodoc">Tipo Doc.</label>
                            <input type="text" class="form-control" id="tipodoc" name="tipodoc" value="{{ old('tipodoc', isset($cliente) ? $cliente->tipodoc : '') }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-1 col-sm-4 position-relative">
                        <div class="form-group">
                            <label for="sexo">Sexo</label>
                            <input type="text" class="form-control" id="sexo" name="sexo" value="{{ old('sexo', isset($cliente) ? $cliente->sexo : '') }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-4 position-relative">
                        <div class="form-group">
                            <label for="documento">Documento</label>
                            <input type="text" class="form-control" id="documento" name="documento" value="{{ old('documento', isset($cliente) ? $cliente->documento : '') }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 position-relative">
                        <div class="form-group">
                            <label for="apelnombres">Apellido y Nombres</label>
                            <input type="text" class="form-control" id="apelnombres" name="apelnombres" value="{{ old('apelnombres', isset($cliente) ? $cliente->apelnombres : '') }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-4 position-relative">
                        <div class="form-group">
                            <label for="nacimiento">Fecha Nac.</label>
                            <input type="date" class="form-control" id="nacimiento" name="nacimiento" value="{{ old('nacimiento', (isset($cliente) && isset($cliente->nacimiento)) ? \Carbon\Carbon::parse($cliente->nacimiento)->format('Y-m-d') : '') }}" readonly>
                        </div>
                    </div>
                  
                </div>
                <br>
                <div class="form group">
                    <a href="{{url('admin')}}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Buscar Operaciones</button>
                </div>
            </form>
        </div>

        <div class="card-body">
            @if(isset($cliente) && $cliente)
                <h4>Operaciones donde es titular</h4>

                <div class="card-body">
                    <table id="example1" class="table table-striped table-bordered table-hover table-sm">
                        <thead style="background-color:rgb(14, 107, 169); color: white;">
                            <tr>
                                <th class="text-align: center; width: 50px;">NRO.</th>
                                <th class="text-align: center; width: 120px;">FECHA</th>
                                <th class="text-align: center; width: 200px;">CLASE</th>
                                <th class="text-align: center; width: 150px;">VALOR CUOTA</th>
                                <th class="text-align: center; width: 150px;">CANT. CUOTAS</th>
                                <th class="text-align: center; width: 150px;">TOTAL</th>
                                <th class="text-align: center; width: 150px;">ESTADO</th>
                                <th class="text-align: center; width: 150px;">FECHA ESTADO</th>
                                <th class="text-align: center; width: 150px;">AFECTAR</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($operaciones as $op)
                                @php
                                $estado = strtoupper($op->estado_actual);
                                    if ($estado == 'ACTIVO') {
                                        $color = 'white';
                                        $bg = 'green';
                                    } elseif ($estado == 'ATRASADO') {
                                        $color = 'black';
                                        $bg = 'yellow';
                                    } elseif (in_array($estado, ['REGULARIZADO', 'CANCELADO', 'EN CONVENIO'])) {
                                        $color = 'white';
                                        $bg = 'orange';
                                    } elseif ($estado == 'CANCELADO CON ATRASO') {
                                        $color = 'white';
                                        $bg = 'red';
                                    }
                                @endphp
                                <tr>
                                    <td class="text-right">{{ $op->numero ?? '' }}</td>
                                    <td class="text-center">{{ isset($op->fecha_operacion) ? \Carbon\Carbon::parse($op->fecha_operacion)->format('d-m-Y') : '' }}</td>
                                    <td>{{ $op->clase ?? '' }}</td>
                                    <td class="text-right">{{ $op->valor_cuota ?? '' }}</td>
                                    <td class="text-right">{{ $op->cant_cuotas ?? '' }}</td>
                                    <td class="text-right">{{ $op->total ?? '' }}</td>
                                    <td class="text-center" style="color: {{ $color }};@if(isset($bg)) background-color: {{ $bg }};@endif">
                                        {{ $op->estado_actual ?? '' }}
                                    </td>
                                    <td class="text-center">{{ isset($op->fecha_estado) ? \Carbon\Carbon::parse($op->fecha_estado)->format('d-m-Y') : '' }}</td>
                                    <td class="text-center">
                                        @if(isset($op->id))
                                            <a href="{{ route('admin.operaciones.afectar', ['id' => $op->id]) }}" class="btn btn-sm btn-danger bi bi-fire"></a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="9">No tiene operaciones como titular.</td></tr>
                            @endforelse
                        </tbody>
                    </table>                
                </div>

                <h4>Operaciones donde es garante</h4>
                <div class="card-body">
                        <table id="example2" class="table table-striped table-bordered table-hover table-sm">
                        <thead style="background-color:rgb(14, 107, 169); color: white;">
                            <tr>
                                <th class="text-align: center; width: 50px;">NRO.</th>
                                <th class="text-align: center; width: 120px;">FECHA</th>
                                <th class="text-align: center; width: 200px;">CLASE</th>
                                <th class="text-align: center; width: 150px;">VALOR CUOTA</th>
                                <th class="text-align: center; width: 150px;">CANT. CUOTAS</th>
                                <th class="text-align: center; width: 150px;">TOTAL</th>
                                <th class="text-align: center; width: 150px;">ESTADO</th>
                                <th class="text-align: center; width: 150px;">FECHA ESTADO</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($operacionesComoGarante as $g)
                                @php
                                $estado = strtoupper($g->estado_actual);
                                    if ($estado == 'ACTIVO') {
                                        $color = 'white';
                                        $bg = 'green';
                                    } elseif ($estado == 'ATRASADO') {
                                        $color = 'black';
                                        $bg = 'yellow';
                                    } elseif (in_array($estado, ['REGULARIZADO', 'CANCELADO', 'EN CONVENIO'])) {
                                        $color = 'white';
                                        $bg = 'orange';
                                    } elseif ($estado == 'CANCELADO CON ATRASO') {
                                        $color = 'white';
                                        $bg = 'red';
                                    }
                                @endphp
                                <tr>
                                    <td class="text-right">{{ $g->numero ?? '' }}</td>
                                    <td class="text-center">{{ isset($g->fecha_operacion) ? \Carbon\Carbon::parse($g->fecha_operacion)->format('d-m-Y') : '' }}</td>
                                    <td>{{ $g->clase ?? '' }}</td>
                                    <td class="text-right">{{ $g->valor_cuota ?? '' }}</td>
                                    <td class="text-right">{{ $g->cant_cuotas ?? '' }}</td>
                                    <td class="text-right">{{ $g->total ?? '' }}</td>
                                    <td class="text-center" style="color: {{ $color }};@if(isset($bg)) background-color: {{ $bg }};@endif">
                                        {{ $g->estado_actual ?? '' }}
                                    </td>
                                    <td class="text-center">{{ isset($g->fecha_estado) ? \Carbon\Carbon::parse($g->fecha_estado)->format('d-m-Y') : '' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var cuit = document.getElementById('cuit').value.trim();
                        if (cuit !== '') {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Sin resultados',
                                text: 'No se encontraron datos para el CUIT ingresado o hubo un error en la búsqueda.',
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    });
                </script>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cuitInput = document.getElementById('cuit');
        const mensajeNoExiste = document.getElementById('mensajeNoExiste');

        // Utilidad para setear valor solo si el input existe
        function setInputValue(id, value) {
            const el = document.getElementById(id);
            if (el) {
                el.value = value;
            } else {
                console.warn('No se encontró el input con id:', id);
            }
        }

        function buscarClientePorCuit() {
            const cuit = cuitInput.value.trim();
            if (cuit.length === 11) {
                fetch(`/admin/clientes/buscar-por-cuit/${cuit}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const cliente = data.cliente;
                            console.log('Cliente recibido:', cliente);
                            setInputValue('tipodoc', cliente.tipodoc || '');
                            setInputValue('sexo', cliente.sexo || '');
                            setInputValue('documento', cliente.documento || '');
                            setInputValue('apelnombres', cliente.apelnombres || '');
                            setInputValue('nacimiento', cliente.nacimiento ? cliente.nacimiento.substring(0,10) : '');
                            setInputValue('estado', cliente.estado || '');
                            setInputValue('fechaestado', cliente.fechaestado ? cliente.fechaestado.substring(0,10) : '');
                        } else {
                            mensajeNoExiste.style.display = 'block';
                            // Limpiar campos
                            setInputValue('tipodoc', '');
                            setInputValue('sexo', '');
                            setInputValue('documento', '');
                            setInputValue('apelnombres', '');
                            setInputValue('nacimiento', '');
                            setInputValue('estado', '');
                            setInputValue('fechaestado', '');
                        }
                    });
            }
        }
        cuitInput.addEventListener('blur', buscarClientePorCuit);
        cuitInput.addEventListener('change', buscarClientePorCuit);
    });
</script>

<script>
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
            "pageLength": 10,
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
                { "orderable": false, "targets": [8] }
            ]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>

<script>
    $(function() {
        $("#example2").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
            "pageLength": 10,
            //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "language": {
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "zeroRecords": "No figura como garante en ninguna operación",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoEmpty": "No figura como garante en ninguna operación",
                "infoFiltered": "(filtrado de _MAX_ registros totales)",
                "search": "Buscar:",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            "columnDefs": [
                { "orderable": false, "targets": [] }
            ]
        }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
    });
</script>

@endsection