@extends('layouts.admin')

@section('content')

<div class="row">
    <h1>Consulta de consumos</h1>
</div>

<div class="col-md-12">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Consultar los consumos</h3>
        </div>
        <div class="card-body">
            <form id="consultaForm" action="{{ route('admin.administracion.consultar') }}" method="GET">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nodo_id">Nodo</label><b>*</b>
                            <select class="form-control" id="nodo_id" name="nodo_id">
                                @if(auth()->user()->hasRole('nodo'))
                                    {{-- Si el usuario tiene rol nodo, solo mostrar su nodo --}}
                                    @foreach ($nodos->sortBy('nombre') as $nodo)
                                        @if($nodo->id == auth()->user()->nodo_id)
                                            <option value="{{ $nodo->id }}" selected>{{ $nodo->nombre }}</option>
                                        @endif
                                    @endforeach
                                @else
                                    {{-- Si no tiene rol nodo, mostrar todas las opciones --}}
                                    <option selected disabled>Seleccione un Nodo</option>
                                    <option value="">TODOS</option>
                                    @foreach ($nodos->sortBy('nombre') as $nodo)
                                        <option value="{{ $nodo->id }}"
                                            {{ old('nodo_id') == $nodo->id ? 'selected' : '' }}>{{ $nodo->nombre }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="socio_id">Socio</label><b>*</b>
                            <select class="form-control" id="socio_id" name="socio_id">
                                <option selected disabled>Seleccione un Socio</option>
                                <option value="">TODOS</option>
                                @foreach ($socios->sortBy('razon_social') as $socio)
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
                    <button type="button" id="generarPdf" class="btn btn-secondary me-5" disabled><i class="bi bi-printer-fill"></i> Generar PDF</button>
                    <a href="{{ url('admin') }}" class="btn btn-info me-5">Salir</a>
                </div>
            </form>
            <br>
            <h4>Consumos realizados</h4>
            <div class="card-body">
                <table id="example1" class="table table-striped table-bordered table-hover table-sm" style="font-size: 0.85em;">
                    <thead style="background-color:rgb(14, 107, 169); color: white;">
                        <tr>
                            <th class="text-center" style="width: 50px;">NRO.</th>
                            <th class="text-center" style="width: 50px;">FECHA</th>
                            <th class="text-center" style="width: 30px;">HORA</th>
                            <th class="text-center" style="width: 50px;">TIPO</th>
                            <th class="text-center" style="width: 60px;">CUIT</th>
                            <th class="text-center" style="width: 150px;">APELLIDO</th>
                            <th class="text-center" style="width: 150px;">NODO</th>
                            <th class="text-center" style="width: 150px;">SOCIO</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // Datos de socios para filtrado dinámico
    const sociosData = @json($socios->toArray());
    
    // Validación y envío del formulario via AJAX
    document.getElementById('consultaForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevenir el envío normal del formulario
        
        const desdeFecha = document.getElementById('desde_fecha').value;
        const hastaFecha = document.getElementById('hasta_fecha').value;
        
        // Validar que ambas fechas estén presentes
        if (!desdeFecha || !hastaFecha) {
            alert('Debe seleccionar tanto la fecha "Desde" como la fecha "Hasta".');
            return false;
        }
        
        // Validar que Hasta Fecha sea igual o mayor que Desde Fecha
        if (new Date(hastaFecha) < new Date(desdeFecha)) {
            alert('La fecha "Hasta" debe ser igual o mayor que la fecha "Desde".');
            document.getElementById('hasta_fecha').focus();
            return false;
        }
        
        // Realizar consulta AJAX
        consultarDatos();
    });
    
    // Función para realizar la consulta AJAX
    function consultarDatos() {
        const formData = new FormData(document.getElementById('consultaForm'));
        const searchParams = new URLSearchParams(formData);
        
        console.log('Parámetros enviados:', searchParams.toString());
        
        // Realizar petición AJAX
        fetch('{{ route("admin.administracion.consultar") }}?' + searchParams.toString(), {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Datos recibidos:', data);
            
            // Preparar datos para DataTables con subtotales por nodo y socio
            let rowsData = [];
            
            if (data.consultas && data.consultas.length > 0) {
                let nodoActual = '';
                let socioActual = '';
                let contadorNodo = 0;
                let contadorSocio = 0;
                let totalGeneral = data.consultas.length;
                
                data.consultas.forEach((item, index) => {
                    const fechaObj = item.fecha ? new Date(item.fecha) : null;
                    const fecha = fechaObj ? fechaObj.toLocaleDateString('es-ES', {
                        day: '2-digit',
                        month: '2-digit', 
                        year: 'numeric'
                    }) : '';
                    const hora = fechaObj ? fechaObj.toLocaleTimeString('es-ES', {
                        hour: '2-digit',
                        minute: '2-digit'
                    }) : '';
                    
                    const nombreNodo = item.nodo ? item.nodo.nombre : '';
                    const nombreSocio = item.socio ? item.socio.razon_social : '';
                    
                    // Si cambió el nodo
                    if (nodoActual !== '' && nodoActual !== nombreNodo) {
                        // Agregar subtotal del socio anterior si existe
                        if (socioActual !== '') {
                            rowsData.push([
                                '',
                                '',
                                '',
                                '',
                                '',
                                `<strong style="color: brown;">Subtotal ${socioActual}: ${contadorSocio} consultas</strong>`,
                                '',
                                ''
                            ]);
                        }
                        // Agregar subtotal del nodo anterior
                        rowsData.push([
                            '',
                            '',
                            '',
                            '',
                            '',
                            '',
                            `<strong style="color: green;">Subtotal Nodo ${nodoActual}: ${contadorNodo} consultas</strong>`,
                            ''
                        ]);
                        contadorNodo = 0;
                        contadorSocio = 0;
                        socioActual = '';
                    }
                    // Si cambió el socio pero no el nodo
                    else if (socioActual !== '' && socioActual !== nombreSocio) {
                        rowsData.push([
                            '',
                            '',
                            '',
                            '',
                            '',
                            `<strong style="color: #28a745;">Subtotal ${socioActual}: ${contadorSocio} consultas</strong>`,
                            '',
                            ''
                        ]);
                        contadorSocio = 0;
                    }
                    
                    // Agregar la fila de datos
                    rowsData.push([
                        item.numero || '',
                        fecha,
                        hora,
                        item.tipo || '',
                        item.cuit || '',
                        item.apelynombres || '',
                        nombreNodo,
                        nombreSocio
                    ]);
                    
                    nodoActual = nombreNodo;
                    socioActual = nombreSocio;
                    contadorNodo++;
                    contadorSocio++;
                    
                    // Si es el último elemento, agregar subtotales finales
                    if (index === data.consultas.length - 1) {
                        // Subtotal del último socio
                        rowsData.push([
                            '',
                            '',
                            '',
                            '',
                            '',
                            `<strong style="color: orange;">Subtotal ${socioActual}: ${contadorSocio} consultas</strong>`,
                            '',
                            ''
                        ]);
                        // Subtotal del último nodo
                        rowsData.push([
                            '',
                            '',
                            '',
                            '',
                            '',
                            '',
                            `<strong style="color: green;">Subtotal Nodo ${nodoActual}: ${contadorNodo} consultas</strong>`,
                            ''
                        ]);
                    }
                });
                
                // Agregar total general
                rowsData.push([
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    `<strong style="color: blue;">TOTAL GENERAL: ${totalGeneral} consultas</strong>`
                ]);
            }
            
            // Actualizar DataTable con nuevos datos
            dataTable.clear();
            dataTable.rows.add(rowsData);
            dataTable.draw();
            
            // Activar botón PDF si hay datos
            const generarPdfBtn = document.getElementById('generarPdf');
            if (rowsData.length > 0) {
                generarPdfBtn.disabled = false;
                generarPdfBtn.classList.remove('btn-secondary');
                generarPdfBtn.classList.add('btn-danger');
            } else {
                generarPdfBtn.disabled = true;
                generarPdfBtn.classList.remove('btn-danger');
                generarPdfBtn.classList.add('btn-secondary');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // En caso de error, mostrar tabla vacía
            dataTable.clear();
            dataTable.draw();
            
            // Desactivar botón PDF
            const generarPdfBtn = document.getElementById('generarPdf');
            generarPdfBtn.disabled = true;
            generarPdfBtn.classList.remove('btn-danger');
            generarPdfBtn.classList.add('btn-secondary');
            
            alert('Error al consultar los datos: ' + error.message);
        });
    }
    
    document.getElementById('nodo_id').addEventListener('change', function() {
        const nodoId = this.value;
        const socioSelect = document.getElementById('socio_id');
        
        // Limpiar opciones actuales excepto las primeras dos
        socioSelect.innerHTML = '<option selected disabled>Seleccione un Socio</option><option value="">TODOS</option>';
        
        // Si se selecciona un nodo específico, filtrar socios
        if (nodoId && nodoId !== '') {
            const sociosFiltrados = sociosData.filter(socio => socio.nodo_id == nodoId)
                .sort((a, b) => a.razon_social.localeCompare(b.razon_social));
            
            sociosFiltrados.forEach(socio => {
                const option = document.createElement('option');
                option.value = socio.id;
                option.textContent = socio.razon_social;
                socioSelect.appendChild(option);
            });
        } else {
            // Si se selecciona TODOS los nodos, mostrar todos los socios ordenados
            const sociosOrdenados = sociosData.sort((a, b) => a.razon_social.localeCompare(b.razon_social));
            
            sociosOrdenados.forEach(socio => {
                const option = document.createElement('option');
                option.value = socio.id;
                option.textContent = socio.razon_social;
                socioSelect.appendChild(option);
            });
        }
    });

    document.getElementById('hasta_fecha').addEventListener('change', function() {
        const desde = document.getElementById('desde_fecha').value;
        const hasta = this.value;
        if (desde && hasta && hasta < desde) {
            alert('La fecha "Hasta" no puede ser menor que la fecha "Desde".');
            this.value = '';
            this.focus();
        }
    });

    // Event listener para el botón Generar PDF
    document.getElementById('generarPdf').addEventListener('click', function() {
        if (dataTable && dataTable.data().length > 0) {
            // Obtener los parámetros de la consulta actual
            const formData = new FormData(document.getElementById('consultaForm'));
            const searchParams = new URLSearchParams(formData);
            
            // Redirigir a la ruta de generación de PDF con los mismos parámetros
            window.open('{{ route("admin.administracion.consultar.pdf") }}?' + searchParams.toString(), '_blank');
        } else {
            alert('No hay datos para generar el PDF. Realice primero una consulta.');
        }
    });

    // Event listener para el botón Limpiar
    document.getElementById('limpiar').addEventListener('click', function() {
        // Limpiar formulario
        document.getElementById('consultaForm').reset();
        
        // Limpiar tabla
        if (dataTable) {
            dataTable.clear();
            dataTable.draw();
        }
        
        // Desactivar botón PDF
        const generarPdfBtn = document.getElementById('generarPdf');
        generarPdfBtn.disabled = true;
        generarPdfBtn.classList.remove('btn-danger');
        generarPdfBtn.classList.add('btn-secondary');
        
        // Restaurar opciones de socios si no es usuario con rol nodo
        @if(!auth()->user()->hasRole('nodo'))
        const socioSelect = document.getElementById('socio_id');
        socioSelect.innerHTML = '<option selected disabled>Seleccione un Socio</option><option value="">TODOS</option>';
        const sociosOrdenados = sociosData.sort((a, b) => a.razon_social.localeCompare(b.razon_social));
        sociosOrdenados.forEach(socio => {
            const option = document.createElement('option');
            option.value = socio.id;
            option.textContent = socio.razon_social;
            socioSelect.appendChild(option);
        });
        @endif
    });
</script>

<script>
    let dataTable = null;

    $(function() {
        // Inicializar DataTable con tbody vacío
        dataTable = $("#example1").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "lengthMenu": [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, "Todos"]
            ],
            "pageLength": 10,
            "language": {
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "zeroRecords": "Use los filtros y presione 'Consultar' para ver los datos",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoEmpty": "Use los filtros y presione 'Consultar' para ver los datos",
                "infoFiltered": "(filtrado de _MAX_ registros totales)",
                "search": "Buscar:",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            "ordering": true, // Habilitar ordenamiento
            "order": [], // Sin orden inicial, respetar orden del servidor
            "createdRow": function(row, data, dataIndex) {
                // Aplicar estilo especial a filas de subtotales
                if (data[5] && (data[5].includes('Subtotal') || data[5].includes('TOTAL GENERAL'))) {
                    $(row).addClass('subtotal-row');
                    $(row).css({
                        'background-color': '#f8f9fa',
                        'font-weight': 'bold',
                        'border-top': '2px solid #dee2e6'
                    });
                }
            },
            "columnDefs": [{
                "orderable": true, // Permitir ordenamiento en todas las columnas
                "targets": "_all"
            }, {
                "type": "date-euro", // Configurar tipo de dato para la columna de fecha
                "targets": 1
            }, {
                "type": "string", // Configurar tipo de dato para la columna de hora
                "targets": 2
            }],
            "data": [] // Inicializar con datos vacíos
        });
    });
</script>

@endsection