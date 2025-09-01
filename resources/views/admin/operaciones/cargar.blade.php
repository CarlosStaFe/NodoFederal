
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
            <form action="{{url('/admin/operaciones/cargar')}}" method="POST">
                <div id="mensajeNoExiste" class="alert alert-warning" style="display:none;">Cliente no encontrado.</div>
                @csrf
                <div class="row">
                    <div>
                        <input id="nombrelocal" name="nombrelocal" type="hidden">
                        <input id="nombreprov" name="nombreprov" type="hidden">
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="cuit">C.U.I.T.</label><b>*</b>
                            <input type="text" class="form-control" value="{{ old('cuit', isset($cuit) ? $cuit : '') }}" id="cuit" name="cuit" placeholder="C.U.I.T." required autocomplete="off">
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
                            <input type="number" class="form-control" id="numero_socio" name="numero_socio" value="{{ old('numero_socio', isset($socio) ? $socio->numero : '') }}" placeholder="Ingrese número" required>
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
                            <input type="text" class="form-control" value="{{ old('cuit_garante', isset($cuit) ? $cuit : '') }}" id="cuit_garante" name="cuit_garante" placeholder="C.U.I.T." required autocomplete="off">
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
                            setInputValue('tipodoc_garante', cliente.tipodoc || '');
                            setInputValue('sexo_garante', cliente.sexo || '');
                            setInputValue('documento_garante', cliente.documento || '');
                            setInputValue('apelnombres_garante', cliente.apelnombres || '');
                        } else {
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

@endsection