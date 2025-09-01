@extends('layouts.admin')

@section('content')

<div class="row">
    <h1>Afectar una Operación</h1>
</div>

<div class="col-md-12">
    <div class="card card-outline card-danger">
        <div class="card-header">
            <h3 class="card-title">Completar los datos</h3>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.operaciones.show') }}" method="POST" id="formOperacion">
                @csrf
                <div class="row">
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
                <br>
                <div class="form group">
                    <a href="{{url('admin')}}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Afectar Operación</button>
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

@endsection