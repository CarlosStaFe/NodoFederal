@extends('layouts.admin')

@section('content')

<div class="row">
    <h1>Consulta de antecedentes: {{ auth()->user()->name }}</h1>
</div>

<div class="col-md-12">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Consultar</h3>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.operaciones.consultar.api') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-lg-2 col-md-3 position-relative">
                        <label for="tipo" class="form-label">Tipo Doc.</label>
                        <select id="tipo" name="tipo" class="form-select" required>
                            <option value="" disabled selected>Tipo</option>
                            <option value="DNI">DNI</option>
                            <option value="CUIL">CUIL</option>
                        </select>
                        @error('tipo')
                            <small style="color: red">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="col-lg-2 col-md-2 position-relative">
                        <label for="sexo" class="form-label">Sexo</label>
                        <select id="sexo" name="sexo" class="form-select" required>
                            <option value="" disabled selected>Sexo</option>
                            <option value="M">M</option>
                            <option value="F">F</option>
                        </select>
                        @error('sexo')
                            <small style="color: red">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="col-lg-2 col-md-2 position-relative">
                        <label for="documento" class="form-label">Número</label>
                        <input id="documento" name="documento" type="text" value="" class="form-control" placeholder="Ingrese un número">
                        @error('documento')
                            <small style="color: red">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="col-lg-2 col-md-2 position-relative">
                        <label for="cuit" class="form-label">C.U.I.T.</label>
                        <input id="cuit" name="cuit" type="text" value="" class="form-control" placeholder="Ingrese un CUIT" >
                        @error('cuit')
                            <small style="color: red">{{$message}}</small>
                        @enderror
                    </div>
                    <br>
                </div>
                <br>

                <div>
                    <button type="button" id="limpiar" class="btn btn-primary me-5">Limpiar</button>
                    <button type="submit" class="btn btn-success me-5">Consultar</button>
                    <a href="{{ url('admin') }}" class="btn btn-info">Salir</a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- @isset($datos) --}}
    {{-- @php $data = $datos['data'] ?? null; @endphp --}}
    {{-- @php $p = $datos['data'][0] ?? null; @endphp --}}
    {{-- @if($p) --}}
        {{-- <div class="alert alert-info mt-4"> --}}
            {{-- <h5>Datos obtenidos de la API:</h5> --}}
            {{-- <a href="{{ url('admin/operaciones/pdf') }}" class="btn btn-danger"><i class="bi bi-printer-fill"></i> Imprimir</a> --}}
        {{-- </div> --}}
    {{-- @endif --}}
{{-- @endisset --}}

<script>
    function calcularCuit(dni, sexo) {
        // Basado en reglas AFIP
        let prefijo = (sexo === 'F') ? '27' : '20';
        if (sexo === 'X') prefijo = '23';
        let cuitBase = prefijo + dni;
        let mult = [5,4,3,2,7,6,5,4,3,2];
        let suma = 0;
        for(let i=0; i<10; i++) suma += parseInt(cuitBase[i]) * mult[i];
        let resto = suma % 11;
        let digito = 11 - resto;
        if(digito === 11) digito = 0;
        if(digito === 10) {
            if(prefijo === '20') prefijo = '23';
            else if(prefijo === '27') prefijo = '23';
            cuitBase = prefijo + dni;
            suma = 0;
            for(let i=0; i<10; i++) suma += parseInt(cuitBase[i]) * mult[i];
            resto = suma % 11;
            digito = 11 - resto;
            if(digito === 11) digito = 0;
            if(digito === 10) digito = 9;
        }
        return prefijo + dni + digito;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const tipo = document.getElementById('tipo');
        const documento = document.getElementById('documento');
        const sexo = document.getElementById('sexo');
        const cuit = document.getElementById('cuit');

        function actualizarCuit() {
            if(tipo.value === 'DNI') {
                documento.disabled = false;
                documento.setAttribute('required', 'required');
                if(documento.value.length >= 7 && (sexo.value === 'M' || sexo.value === 'F')) {
                    cuit.value = calcularCuit(documento.value.padStart(8, '0'), sexo.value);
                }
            } else if(tipo.value === 'CUIT') {
                documento.value = '';
                documento.disabled = true;
                documento.removeAttribute('required');
                cuit.value = '';
            }
        }

        documento.addEventListener('input', actualizarCuit);
        tipo.addEventListener('change', actualizarCuit);
        sexo.addEventListener('change', actualizarCuit);

        // Inicializar estado al cargar
        actualizarCuit();

        // Botón Limpiar
        document.getElementById('limpiar').addEventListener('click', function() {
            tipo.selectedIndex = 0;
            sexo.selectedIndex = 0;
            documento.value = '';
            documento.disabled = false;
            documento.setAttribute('required', 'required');
            cuit.value = '';
        });
    });
</script>

@endsection