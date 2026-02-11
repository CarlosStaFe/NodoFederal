@extends('layouts.admin')

@section('content')

<div class="row">
    <h1>Consulta de antecedentes</h1>
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
                    <div class="col-lg-1 col-md-3 position-relative">
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
                    <div class="col-lg-1 col-md-2 position-relative">
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
                        <input id="documento" name="documento" type="text" value="" class="form-control" placeholder="Ingrese un número" minlength="7" maxlength="8" pattern="\d{7,8}" title="El documento debe tener entre 7 y 8 dígitos">
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
                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('nodo'))
                    <div class="col-lg-3 col-md-3 position-relative">
                        <label for="nodo_id" class="form-label">Nodo</label>
                        <select id="nodo_id" name="nodo_id" class="form-select">
                            @if(auth()->user()->hasRole('admin'))
                                <option value="" selected>Todos los nodos</option>
                                @foreach($nodos as $nodo)
                                    <option value="{{ $nodo->id }}">{{ $nodo->nombre }}</option>
                                @endforeach
                            @elseif(auth()->user()->hasRole('nodo') && auth()->user()->nodo_id)
                                @php
                                    $userNodo = $nodos->where('id', auth()->user()->nodo_id)->first();
                                @endphp
                                @if($userNodo)
                                    <option value="{{ $userNodo->id }}" selected>{{ $userNodo->nombre }}</option>
                                @else
                                    <option value="" selected>Sin nodo asignado</option>
                                @endif
                            @else
                                <option value="" selected>Sin nodo asignado</option>
                            @endif
                        </select>
                        @error('nodo_id')
                            <small style="color: red">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="col-lg-3 col-md-3 position-relative">
                        <label for="socio_id" class="form-label">Socio</label>
                        <select id="socio_id" name="socio_id" class="form-select">
                            <option value="" selected>Todos los socios</option>
                            @foreach($socios as $socio)
                                <option value="{{ $socio->id }}">{{ $socio->razon_social ?? $socio->nombre }}</option>
                            @endforeach
                        </select>
                        @error('socio_id')
                            <small style="color: red">{{$message}}</small>
                        @enderror
                    </div>
                    @endif
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
                
                // Validar que tenga al menos 7 dígitos antes de calcular CUIT
                if(documento.value.length >= 7 && documento.value.length <= 8 && (sexo.value === 'M' || sexo.value === 'F')) {
                    // Solo permitir números
                    if(/^\d+$/.test(documento.value)) {
                        cuit.value = calcularCuit(documento.value.padStart(8, '0'), sexo.value);
                    } else {
                        cuit.value = '';
                    }
                } else {
                    cuit.value = '';
                }
                
                // Mostrar mensaje de error si no cumple con la longitud mínima
                if(documento.value.length > 0 && documento.value.length < 7) {
                    documento.setCustomValidity('El documento debe tener al menos 7 dígitos');
                } else if(documento.value.length > 8) {
                    documento.setCustomValidity('El documento no puede tener más de 8 dígitos');
                } else if(documento.value.length > 0 && !/^\d+$/.test(documento.value)) {
                    documento.setCustomValidity('El documento solo puede contener números');
                } else {
                    documento.setCustomValidity('');
                }
            } else if(tipo.value === 'CUIT') {
                documento.value = '';
                documento.disabled = true;
                documento.removeAttribute('required');
                documento.setCustomValidity('');
                cuit.value = '';
            }
        }

        documento.addEventListener('input', actualizarCuit);
        tipo.addEventListener('change', actualizarCuit);
        sexo.addEventListener('change', actualizarCuit);

        // Inicializar estado al cargar
        actualizarCuit();

        // Funcionalidad para filtrar socios por nodo
        const nodoSelect = document.getElementById('nodo_id');
        const socioSelect = document.getElementById('socio_id');
        
        if (nodoSelect && socioSelect) {
            function cargarSociosPorNodo() {
                const nodoId = nodoSelect.value;
                
                // Limpiar opciones actuales de socios
                socioSelect.innerHTML = '<option value="" selected>Cargando...</option>';
                
                if (nodoId) {
                    // Hacer petición AJAX para obtener socios del nodo
                    fetch(`{{ url('admin/operaciones/socios') }}/${nodoId}`)
                        .then(response => response.json())
                        .then(data => {
                            socioSelect.innerHTML = '<option value="" selected>Todos los socios</option>';
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
                    // Si no hay nodo seleccionado, mostrar todos los socios
                    socioSelect.innerHTML = '<option value="" selected>Todos los socios</option>';
                    @foreach($socios as $socio)
                        socioSelect.innerHTML += '<option value="{{ $socio->id }}">{{ $socio->razon_social ?? $socio->nombre }}</option>';
                    @endforeach
                }
            }

            nodoSelect.addEventListener('change', cargarSociosPorNodo);
            
            // Si el usuario es nodo y tiene un nodo preseleccionado, cargar sus socios automáticamente
            @if(auth()->user()->hasRole('nodo') && auth()->user()->nodo_id)
                // Cargar socios del nodo del usuario al cargar la página
                setTimeout(cargarSociosPorNodo, 100);
            @endif
        }

        // Botón Limpiar
        document.getElementById('limpiar').addEventListener('click', function() {
            tipo.selectedIndex = 0;
            sexo.selectedIndex = 0;
            documento.value = '';
            documento.disabled = false;
            documento.setAttribute('required', 'required');
            cuit.value = '';
            
            // Limpiar filtros de nodo y socio si existen
            if (nodoSelect) {
                nodoSelect.selectedIndex = 0;
            }
            if (socioSelect) {
                socioSelect.innerHTML = '<option value="" selected>Todos los socios</option>';
                @foreach($socios as $socio)
                    socioSelect.innerHTML += '<option value="{{ $socio->id }}">{{ $socio->razon_social ?? $socio->nombre }}</option>';
                @endforeach
            }
        });
    });
</script>

@endsection