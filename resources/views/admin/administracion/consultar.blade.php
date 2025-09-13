@extends('layouts.admin')

@section('content')

<div class="row">
    <h1>Consulta de consumos: {{ auth()->user()->name }}</h1>
</div>

<div class="col-md-12">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Consultar los consumos</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.administracion.consultar') }}" method="GET">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nodo_id">Nodo</label><b>*</b>
                            <select class="form-control" id="nodo_id" name="nodo_id">
                                <option selected disabled>Seleccione un Nodo</option>
                                @foreach ($nodos as $nodo)
                                    <option value="{{ $nodo->id }}"
                                        {{ old('nodo_id') == $nodo->id ? 'selected' : '' }}>{{ $nodo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="socio_id">Socio</label><b>*</b>
                            <select class="form-control" id="socio_id" name="socio_id">
                                <option selected disabled>Seleccione un Socio</option>
                                @foreach ($socios as $socio)
                                    <option value="{{ $socio->id }}"
                                        {{ old('socio_id') == $socio->id ? 'selected' : '' }}>{{ $socio->razon_social }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 position-relative">
                        <label for="desde_fecha" class="form-label">Desde Fecha</label>
                        <input id="desde_fecha" name="desde_fecha" type="date"
                            value="{{ old('desde_fecha') ? \Carbon\Carbon::parse(old('desde_fecha'))->format('Y-m-d') : '' }}"
                            class="form-control" required>
                        @error('desde_fecha')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-lg-2 col-md-2 position-relative">
                        <label for="hasta_fecha" class="form-label">Hasta Fecha</label>
                        <input id="hasta_fecha" name="hasta_fecha" type="date"
                            value="{{ old('hasta_fecha') ? \Carbon\Carbon::parse(old('hasta_fecha'))->format('Y-m-d') : '' }}"
                            class="form-control" required>
                        @error('hasta_fecha')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <br>
                <div>
                    <button type="button" id="limpiar" class="btn btn-primary me-5">Limpiar</button>
                    <button type="submit" class="btn btn-success me-5">Consultar</button>
                    <a href="{{ url('admin') }}" class="btn btn-info">Salir</a>
                </div>
            </form>
            <br>
            @if (isset($consulta) && $consulta)
                <h4>Consumos realizados</h4>

                <div class="card-body">
                    <table id="example1" class="table table-striped table-bordered table-hover table-sm" style="font-size: 0.85em;">
                        <thead style="background-color:rgb(14, 107, 169); color: white;">
                            <tr>
                                <th class="text-center" style="width: 50px;">NRO.</th>
                                <th class="text-center" style="width: 50px;">FECHA</th>
                                <th class="text-center" style="width: 100px;">TIPO</th>
                                <th class="text-center" style="width: 100px;">CUIT</th>
                                <th class="text-center" style="width: 150px;">APELLIDO</th>
                                <th class="text-center" style="width: 150px;">NODO</th>
                                <th class="text-center" style="width: 150px;">SOCIO</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($consulta as $item)
                                <tr>
                                    <td class="text-center">{{ $item->numero }}</td>
                                    <td class="text-center">
                                        {{ isset($item->fecha) ? \Carbon\Carbon::parse($item->fecha)->format('d-m-Y') : '' }}
                                    </td>
                                    <td>{{ $item->tipo ?? '' }}</td>
                                    <td>{{ $item->cuit ?? '' }}</td>
                                    <td>{{ $item->apelynombres ?? '' }}</td>
                                    <td>{{ $item->nodo->nombre ?? '' }}</td>
                                    <td>{{ $item->socio->razon_social ?? '' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">No hay consumos registrados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.getElementById('hasta_fecha').addEventListener('change', function() {
        const desde = document.getElementById('desde_fecha').value;
        const hasta = this.value;
        if (desde && hasta && hasta < desde) {
            alert('La fecha "Hasta" no puede ser menor que la fecha "Desde".');
            this.value = '';
            this.focus();
        }
    });
</script>

<script>
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "lengthMenu": [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, "Todos"]
            ],
            "pageLength": 10,
            //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
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
            "columnDefs": [{
                "orderable": false,
                "targets": []
            }]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>

@endsection