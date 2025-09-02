@extends('layouts.admin')

@section('content')

<div class="row">
    <h1>Registrar Operación</h1>
</div>

<div class="col-md-12">
    <div class="card card-outline card-warning">
        <div class="card-header">
            <h3 class="card-title">Completar los datos</h3>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.operaciones.store') }}" method="POST" id="formOperacion">
                @csrf
                <input type="hidden" id="garantes_json" name="garantes_json" value="[]">
                <div class="row">
                    <div>
                        <input id="id_cliente_garante" name="id_cliente_garante" type="hidden">
                    </div>

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
                <div class="row">
                    <div class="col-md-3 col-sm-6 position-relative">
                        <div class="form-group">
                            <label for="estado">Estado</label>
                            <input type="text" class="form-control" id="estado" name="estado" value="{{ old('estado', isset($cliente) ? $cliente->estado : '') }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6 position-relative">
                        <div class="form-group">
                            <label for="fechaestado">Fecha Estado</label>
                            <input type="date" class="form-control" id="fechaestado" name="fechaestado" value="{{ old('fechaestado', (isset($cliente) && isset($cliente->fechaestado)) ? \Carbon\Carbon::parse($cliente->fechaestado)->format('Y-m-d') : '') }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-2 position-relative">
                        <div class="form-group">
                            <label for="numero_socio">Nro. Socio</label><b>*</b>
                            <input type="number" class="form-control" id="numero_socio" name="numero_socio" value="{{ old('numero_socio', isset($socio) ? $socio->numero : '') }}" placeholder="Ingrese número" required autofocus>
                            @error('numero_socio')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-5 col-sm-6 position-relative">
                        <div class="form-group">
                            <label for="nombre_socio">Nombre del Socio</label>
                            <input type="text" class="form-control" id="nombre_socio" name="nombre_socio" value="{{ old('nombre_socio', isset($socio) ? $socio->nombre : '') }}" readonly>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-3 col-sm-6 position-relative">
                        <div class="form-group">
                            <label for="valor">Valor Cuota</label><b>*</b>
                            <input type="number" class="form-control" value="{{ old('valor') }}" id="valor" name="valor" placeholder="Valor Cuota" required step="0.01" min="0" pattern="^\d+(\.\d{1,2})?$" inputmode="decimal">
                            @error('valor')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6 position-relative">
                        <div class="form-group">
                            <label for="cuotas">Cantidad</label><b>*</b>
                            <input type="number" class="form-control" value="{{ old('cuotas') }}" id="cuotas" name="cuotas" placeholder="Cantidad de Cuotas" required step="1" min="1">
                            @error('cuotas')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6 position-relative">
                        <div class="form-group">
                            <label for="total">Total Operación</label>
                            <input type="number" class="form-control" value="{{ old('total') }}" id="total" name="total" placeholder="Total Operación" required step="0.01" min="0" pattern="^\d+(\.\d{1,2})?$" inputmode="decimal" readonly>
                            @error('total')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6 position-relative">
                        <div class="form-group">
                            <label for="vencimiento" class="form-label">Vto. 1er. Cuota</label><b>*</b>
                            <input id="vencimiento" name="vencimiento" type="date" class="form-control" required>
                            @error('vencimiento')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-4 position-relative">
                        <div class="form group">
                            <label for="operacion">Tipo Operación</label><b>*</b>
                            <select type="text" class="form-control" value="{{old('operacion')}}" id="operacion" name="operacion" placeholder="Tipo Operación" required>
                                <option selected disabled>Elige tipo de operación...</option>
                                <option value="Comercial">Comercial</option>
                                <option value="Financiera">Financiera</option>
                                <option value="Inmobiliaria">Inmobiliaria</option>
                                <option value="Legal">Legal</option>
                            </select>
                            @error('operacion')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="cuit_garante">CUIT Garante</label><b>*</b>
                            <input type="text" class="form-control" value="{{ old('cuit_garante', isset($cuit) ? $cuit : '') }}" id="cuit_garante" name="cuit_garante" placeholder="C.U.I.T." autocomplete="off">
                            @error('cuit_garante')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-1 col-sm-4 position-relative">
                        <div class="form-group">
                            <label for="tipodoc_garante">Tipo Doc.</label>
                            <input type="text" class="form-control" id="tipodoc_garante" name="tipodoc_garante" value="{{ old('tipodoc_garante', isset($cliente) ? $cliente->tipodoc : '') }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-1 col-sm-4 position-relative">
                        <div class="form-group">
                            <label for="sexo_garante">Sexo</label>
                            <input type="text" class="form-control" id="sexo_garante" name="sexo_garante" value="{{ old('sexo_garante', isset($cliente) ? $cliente->sexo : '') }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-4 position-relative">
                        <div class="form-group">
                            <label for="documento_garante">Documento</label>
                            <input type="text" class="form-control" id="documento_garante" name="documento_garante" value="{{ old('documento_garante', isset($cliente) ? $cliente->documento : '') }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 position-relative">
                        <div class="form-group">
                            <label for="apelnombres_garante">Apellido y Nombres</label>
                            <input type="text" class="form-control" id="apelnombres_garante" name="apelnombres_garante" value="{{ old('apelnombres_garante', isset($cliente) ? $cliente->apelnombres : '') }}" readonly>
                        </div>
                    </div>

                    <div class="col-lg-2 p-4 col-md-12">
                        <button type="button" class="btn btn-success btn-sm" id="agregarGarante">Agregar Garante</button>
                    </div>
                    <div class="col-lg-12 mt-4">
                        <h5 class="text-primary">Lista de Garantes</h5>
                        <table class="table table-bordered table-striped">
                            <thead class="table-primary text-center">
                                <tr>
                                    <th>#</th>
                                    <th class="d-none">id</th>
                                    <th>C.U.I.T.</th>
                                    <th>Tipo Doc.</th>
                                    <th>Sexo</th>
                                    <th>Documento</th>
                                    <th>Apellido y Nombres</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tablaGarantes">
                                <!-- Las filas se agregarán dinámicamente aquí -->
                            </tbody>
                        </table>
                        <!-- <button type="button" class="btn btn-success btn-sm" id="agregarGarante">Agregar Garante</button> -->
                    </div>
                    
                </div>
                <br>
                <div class="form group">
                    <a href="{{url('admin')}}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Registrar Operación</button>
                </div>
            </form>
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

        cuitInput.addEventListener('blur', function() {
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
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function calcularTotal() {
            const valor = parseFloat(document.getElementById('valor').value) || 0;
            const cuotas = parseInt(document.getElementById('cuotas').value) || 0;
            document.getElementById('total').value = (valor * cuotas).toFixed(2);
        }
        document.getElementById('valor').addEventListener('input', calcularTotal);
        document.getElementById('cuotas').addEventListener('input', calcularTotal);
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ...existing code...
        const cuitGaranteInput = document.getElementById('cuit_garante');
        // Prevenir Enter en CUIT Garante
        cuitGaranteInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                return false;
            }
        });
        // Utilidad para setear valor solo si el input existe
        function setInputValue(id, value) {
            const el = document.getElementById(id);
            if (el) {
                el.value = value;
            } else {
                console.warn('No se encontró el input con id:', id);
            }
        }
        cuitGaranteInput.addEventListener('blur', function() {
            const cuit = cuitGaranteInput.value.trim();
            if (cuit.length === 11) {
                fetch(`/admin/clientes/buscar-por-cuit/${cuit}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const cliente = data.cliente;
                            setInputValue('id_cliente_garante', cliente.id || '');
                            setInputValue('tipodoc_garante', cliente.tipodoc || '');
                            setInputValue('sexo_garante', cliente.sexo || '');
                            setInputValue('documento_garante', cliente.documento || '');
                            setInputValue('apelnombres_garante', cliente.apelnombres || '');
                        } else {
                            setInputValue('id_cliente_garante', '');
                            setInputValue('tipodoc_garante', '');
                            setInputValue('sexo_garante', '');
                            setInputValue('documento_garante', '');
                            setInputValue('apelnombres_garante', '');
                        }
                    });
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ...existing code...
        const numeroSocioInput = document.getElementById('numero_socio');
        numeroSocioInput.addEventListener('blur', function() {
            const numero = numeroSocioInput.value.trim();
            if (numero.length > 0) {
                fetch(`/admin/socios/buscar-por-numero/${numero}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('nombre_socio').value = data.socio.razon_social || '';
                        } else {
                            document.getElementById('nombre_socio').value = '';
                        }
                    });
            }
        });
    });
</script>

<script>
    // Referencias a los elementos del formulario
    const idInput = document.getElementById('id_cliente_garante');
    const cuitInput = document.getElementById('cuit_garante');
    const tipoInput = document.getElementById('tipodoc_garante');
    const sexoInput = document.getElementById('sexo_garante');
    const documentoInput = document.getElementById('documento_garante');
    const apelnombresInput = document.getElementById('apelnombres_garante');
    const agregarGaranteBtn = document.getElementById('agregarGarante');

    // Contador para las filas
    let contadorGarantes = 0;

    // Función para actualizar la numeración de la tabla
    function actualizarNumeracion() {
        const filas = tablaGarantes.querySelectorAll('tr');
        filas.forEach((fila, idx) => {
            fila.querySelector('td').innerText = idx + 1;
        });
        contadorGarantes = filas.length;
    }
    // Función para agregar un garante a la tabla
    agregarGaranteBtn.addEventListener('click', () => {
        const id = idInput.value;
        const cuit = cuitInput.value;
        const tipoDoc = tipoInput.value;
        const sexo = sexoInput.value;
        const nroDoc = documentoInput.value;
        const apeNom = apelnombresInput.value;
        // Validar que los campos no estén vacíos
        if (!cuit) {
            alert('Por favor, ingrese el C.U.I.T. del garante antes de agregarlo.');
            return;
        }

        // Validar que no se repita el CUIT
        const filas = tablaGarantes.querySelectorAll('tr');
        for (let fila of filas) {
            const cuitExistente = fila.querySelectorAll('td')[2].innerText.trim();
            if (cuitExistente === cuit) {
                alert('Ya existe un garante con ese C.U.I.T. en la tabla.');
                return;
            }
        }

        // Crear una nueva fila
        const fila = document.createElement('tr');
        fila.innerHTML = `
            <td></td>
            <td class="d-none">${id}</td>
            <td>${cuit}</td>
            <td>${tipoDoc}</td>
            <td>${sexo}</td>
            <td>${nroDoc}</td>
            <td>${apeNom}</td>
            <td class="text-center">
                <button type="button" class="btn btn-info btn-sm bi bi-pencil editarGarante"></button>
                <button type="button" class="btn btn-danger btn-sm bi bi-trash eliminarGarante"></button>
            </td>
        `;

        // Agregar la fila a la tabla
        tablaGarantes.appendChild(fila);
        actualizarNumeracion();

        // Limpiar los campos del formulario
        idInput.value = '';
        cuitInput.value = '';
        tipoInput.value = '';
        sexoInput.value = '';
        documentoInput.value = '';
        apelnombresInput.value = '';

        // Agregar funcionalidad al botón "Eliminar"
        fila.querySelector('.eliminarGarante').addEventListener('click', () => {
            fila.remove();
            actualizarNumeracion();
        });

        // Agregar funcionalidad al botón "Editar"
        fila.querySelector('.editarGarante').addEventListener('click', () => {
            idInput.value = id;
            cuitInput.value = cuit;
            tipoInput.value = tipoDoc;
            sexoInput.value = sexo;
            documentoInput.value = nroDoc;
            apelnombresInput.value = apeNom;
            fila.remove();
            actualizarNumeracion();
        });
    });
</script>

<script>
// Serializar garantes antes de enviar el formulario
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('formOperacion');
        const tablaGarantes = document.getElementById('tablaGarantes');
        form.addEventListener('submit', function(e) {
            const filas = tablaGarantes.querySelectorAll('tr');
            const garantes = [];
            filas.forEach(fila => {
                const celdas = fila.querySelectorAll('td');
                // celdas: 0=#, 1=id (oculto), 2=cuit, 3=tipodoc, 4=sexo, 5=documento, 6=apelnombres
                if (celdas.length >= 7) {
                    garantes.push({
                        id: celdas[1].innerText.trim(),
                        cuit: celdas[2].innerText.trim(),
                        tipodoc: celdas[3].innerText.trim(),
                        sexo: celdas[4].innerText.trim(),
                        documento: celdas[5].innerText.trim(),
                        apelnombres: celdas[6].innerText.trim(),
                    });
                }
            });
            document.getElementById('garantes_json').value = JSON.stringify(garantes);
        });
    });
</script>

@endsection