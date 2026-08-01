@extends('layouts.admin')

@section('content')

<div class="row">
    <h1>Procesar Afectaciones/Desafectaciones masivas</h1>
</div>

<div class="col-md-12">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Procesamiento de Afectaciones/Desafectaciones masivas</h3>
        </div>
        <div class="card-body">
            <form id="consultaForm" action="{{ route('admin.operaciones.procesar') }}" method="GET">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nodo_id">Nodo</label><b>*</b>
                            <select class="form-control" id="nodo_id" name="nodo_id">
                                @if(auth()->user()->hasRole('nodo') || auth()->user()->hasRole('socio'))
                                    {{-- Si el usuario tiene rol nodo o socio, solo mostrar su nodo --}}
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
                                @if(auth()->user()->hasRole('socio'))
                                    {{-- Si el usuario tiene rol socio, solo mostrar su socio --}}
                                    @foreach ($socios->sortBy('razon_social') as $socio)
                                        @if($socio->id == auth()->user()->socio_id)
                                            <option value="{{ $socio->id }}" selected>{{ $socio->razon_social }}</option>
                                        @endif
                                    @endforeach
                                @else
                                    <option selected disabled>Seleccione un Socio</option>
                                    <option value="">TODOS</option>
                                    @foreach ($socios->sortBy('razon_social') as $socio)
                                        <option value="{{ $socio->id }}"
                                            {{ old('socio_id') == $socio->id ? 'selected' : '' }}>{{ $socio->razon_social }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>                    
                </div>
                <br>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="archivo_excel">Subir archivo Excel</label>
                            <input type="file" class="form-control" id="archivo_excel" name="archivo_excel" accept=".xls,.xlsx">
                        </div>
                    </div>
                </div>
                <div>
                    <button type="button" id="limpiar" class="btn btn-primary me-5">Limpiar</button>
                    <button type="submit" id="revisarProcesar" class="btn btn-success me-5">Revisar y Procesar</button>
                    <a href="{{ url('admin') }}" class="btn btn-warning me-5">Salir</a>
                </div>
            </form>
            <br>
            <h4>Datos contenidos en el archivo</h4>
            <div class="card-body">
                <table id="example1" class="table table-striped table-bordered table-hover table-sm" style="font-size: 0.8em;">
                    <thead style="background-color:rgb(14, 107, 169); color: white;">
                        <tr>
                            <th class="text-center" style="width: 40px;">FECHA</th>
                            <th class="text-center" style="width: 60px;">CUIL</th>
                            <th class="text-center" style="width: 150px;">APELLIDO Y NOMBRE</th>
                            <th class="text-center" style="width: 60px;">CONDICIÓN</th>
                            <th class="text-center" style="width: 60px;">ESTADO</th>
                            <th class="text-center" style="width: 100px;">REFERENCIA</th>
                            <th class="text-center" style="width: 60px;">OPERACION</th>
                            <th class="text-center" style="width: 20px;">CTAS</th>
                            <th class="text-center" style="width: 60px;">IMPORTE</th>
                            <th class="text-center" style="width: 60px;">TOTAL</th>
                            <th class="text-center" style="width: 30px;">SOCIO</th>
                            <th class="text-center" style="width: 30px;">NODO</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script>
    let dataTable = null;

    function normalizarTexto(valor) {
        return String(valor || '').trim().toLowerCase();
    }

    function esFilaCabecera(fila) {
        if (!Array.isArray(fila) || fila.length === 0) {
            return false;
        }

        const valores = fila.map(normalizarTexto);
        return valores.includes('fecha') && (valores.includes('cuil') || valores.includes('apellido y nombre'));
    }

    function esFilaVacia(fila) {
        return !fila || fila.every(function(celda) {
            return String(celda || '').trim() === '';
        });
    }

    function obtenerNombreSeleccionado(selectId) {
        const select = document.getElementById(selectId);
        if (!select || select.selectedIndex < 0) {
            return '';
        }

        const opcion = select.options[select.selectedIndex];
        return opcion && opcion.value ? opcion.text : '';
    }

    function construirRegistrosProcesamiento(filasDataTable, nodoId, socioId) {
        const registros = [];

        function limpiarNumero(valor) {
            const texto = String(valor ?? '').trim().replace(',', '.');
            const numero = parseFloat(texto.replace(/[^\d.\-]/g, ''));
            return Number.isFinite(numero) ? numero : 0;
        }

        filasDataTable.forEach(function(fila, indice) {
            const cuotas = limpiarNumero(fila[7] ?? '0');
            const importe = limpiarNumero(fila[8] ?? '0');
            const total = limpiarNumero(fila[9] ?? (cuotas * importe));

            const registro = {
                'ID': (fila[6] && String(fila[6]).trim() !== '') ? String(fila[6]).trim() : String(indice + 1),
                'CUIL': String(fila[1] ?? '').trim(),
                'DNI': '',
                'TITULAR': String(fila[2] ?? '').trim(),
                'FECHA DEUDA': String(fila[0] ?? '').trim(),
                'DEUDA TOTAL': String(total).trim(),
                'CUOTAS': String(cuotas).trim(),
                'IMPORTE': String(importe).trim(),
                'OPERACION': String(fila[6] ?? '').trim(),
                'TIPO DEUDOR': String(fila[3] ?? 'SOLICITANTE').trim(),
                'CODIGO DE ATRASO': String(fila[4] ?? 'PENDIENTE').trim(),
                'REFERENCIA': String(fila[5] ?? '').trim(),
                'id_nodo': nodoId ? Number(nodoId) : null,
                'id_socio': socioId ? Number(socioId) : null
            };

            if (registro.CUIL !== '' || registro.TITULAR !== '') {
                registros.push(registro);
            }
        });

        return registros;
    }

    function enviarRegistrosParaProcesar(payload) {
        return fetch('{{ route("admin.operaciones.procesar.archivo") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        }).then(async function(response) {
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'No se pudo procesar el archivo.');
            }
            return data;
        });
    }

    function cargarExcelEnTabla(archivo) {
        return new Promise(function(resolve, reject) {
            const lector = new FileReader();

            lector.onload = function(evento) {
                try {
                    const datos = new Uint8Array(evento.target.result);
                    const workbook = XLSX.read(datos, { type: 'array' });

                    if (!workbook.SheetNames || workbook.SheetNames.length === 0) {
                        reject(new Error('El archivo no contiene hojas para procesar.'));
                        return;
                    }

                    const primeraHoja = workbook.Sheets[workbook.SheetNames[0]];
                    const filasHoja = XLSX.utils.sheet_to_json(primeraHoja, {
                        header: 1,
                        raw: false,
                        defval: ''
                    });

                    const nombreSocio = obtenerNombreSeleccionado('socio_id');
                    const nombreNodo = obtenerNombreSeleccionado('nodo_id');

                    const filasDataTable = [];

                    filasHoja.forEach(function(fila, indice) {
                        if (esFilaVacia(fila)) {
                            return;
                        }

                        if (indice === 0 && esFilaCabecera(fila)) {
                            return;
                        }

                        const filaNormalizada = [
                            fila[0] ?? '',
                            fila[1] ?? '',
                            fila[2] ?? '',
                            fila[3] ?? '',
                            fila[4] ?? '',
                            fila[5] ?? '',
                            fila[6] ?? '',
                            fila[7] ?? '',
                            fila[8] ?? '',
                            fila[9] ?? '',
                            fila[10] ?? nombreSocio,
                            fila[11] ?? nombreNodo
                        ];

                        filasDataTable.push(filaNormalizada.map(function(celda) {
                            return String(celda ?? '').trim();
                        }));
                    });

                    dataTable.clear();
                    dataTable.rows.add(filasDataTable);
                    dataTable.draw();

                    if (filasDataTable.length === 0) {
                        reject(new Error('No se encontraron filas de datos para mostrar.'));
                        return;
                    }

                    resolve({ filasDataTable: filasDataTable });
                } catch (error) {
                    reject(error);
                }
            };

            lector.onerror = function() {
                reject(new Error('No se pudo leer el archivo seleccionado.'));
            };

            lector.readAsArrayBuffer(archivo);
        });
    }

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

        const nodoSelect = document.getElementById('nodo_id');
        const socioSelect = document.getElementById('socio_id');

        @if(!auth()->user()->hasRole('socio'))
        if (nodoSelect && socioSelect) {
            function cargarSociosPorNodo() {
                const nodoId = nodoSelect.value;
                socioSelect.innerHTML = '<option value="" selected>Cargando...</option>';

                if (nodoId) {
                    fetch(`{{ route('admin.operaciones.procesar.socios-por-nodo', ['nodoId' => '__NODO__']) }}`.replace('__NODO__', nodoId))
                        .then(response => response.json())
                        .then(data => {
                            socioSelect.innerHTML = '<option value="" selected>TODOS</option>';
                            data.forEach(socio => {
                                const option = document.createElement('option');
                                option.value = socio.id;
                                option.textContent = socio.razon_social || socio.nombre;
                                socioSelect.appendChild(option);
                            });
                        })
                        .catch(error => {
                            console.error('Error al cargar socios:', error);
                            socioSelect.innerHTML = '<option value="" selected>Error al cargar socios</option>';
                        });
                } else {
                    socioSelect.innerHTML = '<option value="" selected>TODOS</option>';
                    @foreach ($socios->sortBy('razon_social') as $socio)
                        socioSelect.innerHTML += '<option value="{{ $socio->id }}">{{ $socio->razon_social }}</option>';
                    @endforeach
                }
            }

            nodoSelect.addEventListener('change', cargarSociosPorNodo);

            @if(auth()->user()->hasRole('nodo') && auth()->user()->nodo_id)
                setTimeout(cargarSociosPorNodo, 100);
            @endif
        }
        @endif

        document.getElementById('consultaForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const archivoInput = document.getElementById('archivo_excel');
            const botonProcesar = document.getElementById('revisarProcesar');
            if (!archivoInput || !archivoInput.files || archivoInput.files.length === 0) {
                alert('Seleccione un archivo Excel para revisar y procesar.');
                return;
            }

            const nodoId = document.getElementById('nodo_id') ? document.getElementById('nodo_id').value : '';
            const socioId = document.getElementById('socio_id') ? document.getElementById('socio_id').value : '';

            botonProcesar.disabled = true;
            botonProcesar.textContent = 'Procesando...';

            cargarExcelEnTabla(archivoInput.files[0])
                .then(function(resultado) {
                    const registros = construirRegistrosProcesamiento(resultado.filasDataTable, nodoId, socioId);

                    if (registros.length === 0) {
                        throw new Error('No hay registros validos para procesar.');
                    }

                    return enviarRegistrosParaProcesar({
                        archivo_nombre: archivoInput.files[0].name,
                        contenido_json: JSON.stringify(registros),
                        nodo_id: nodoId || null,
                        socio_id: socioId || null
                    }).then(function(respuesta) {
                        return {
                            respuesta: respuesta,
                            cantidad: registros.length
                        };
                    });
                })
                .then(function(resultado) {
                    const mensaje = [
                        'Archivo convertido a JSON y procesado correctamente.',
                        'Registros revisados: ' + resultado.cantidad,
                        'JSON generado: ' + resultado.respuesta.archivo_json
                    ];
                    alert(mensaje.join('\n'));
                })
                .catch(function(error) {
                    console.error('Error procesando archivo:', error);
                    alert('Error: ' + error.message);
                })
                .finally(function() {
                    botonProcesar.disabled = false;
                    botonProcesar.textContent = 'Revisar y Procesar';
                });
        });

        document.getElementById('limpiar').addEventListener('click', function() {
            const archivoInput = document.getElementById('archivo_excel');
            if (archivoInput) {
                archivoInput.value = '';
            }

            if (dataTable) {
                dataTable.clear();
                dataTable.draw();
            }
        });
    });
</script>

@endsection