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
                            <option value="CUIT">CUIT</option>
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
                        <input id="cuit" name="cuit" type="text" value="" class="form-control" placeholder="Ingrese un CUIT" disabled>
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
                <p>><small class="text-info">* Puede consultar por DNI (completando documento y sexo, se calculará el CUIT automáticamente) o por CUIT ( deberá completar el valor). Al consultar será redirigido automáticamente al informe.</small></p>
                <br>
                <div>                    <button type="button" id="limpiar" class="btn btn-primary me-5">Limpiar</button>
                    <button type="submit" class="btn btn-success me-5" id="btnConsultar">
                        <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        <span id="btnText">Consultar</span>
                    </button>
                    <a href="{{ url('admin') }}" class="btn btn-info">Salir</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Contenedor para mostrar resultados -->
<div class="col-md-12 mt-3" id="resultadosContainer" style="display: none;">
    <div class="card card-outline card-success">
        <div class="card-header">
            <h3 class="card-title">Resultados de la Consulta</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" onclick="cerrarResultados()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div id="resultadosContent">
                <!-- Aquí se mostrarán los resultados -->
            </div>
        </div>
    </div>
</div>

<script>
    function calcularCuit(dni, sexo) {
        console.log('Calculando CUIT para DNI:', dni, 'Sexo:', sexo);
        
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
        
        const cuitCalculado = prefijo + dni + digito;
        console.log('CUIT calculado:', cuitCalculado);
        return cuitCalculado;
    }

    $(document).ready(function() {
        console.log('Document ready (jQuery)');
        
        const tipo = document.getElementById('tipo');
        const documento = document.getElementById('documento');
        const sexo = document.getElementById('sexo');
        const cuit = document.getElementById('cuit');
        
        console.log('Elements found:', { tipo, documento, sexo, cuit });

        function actualizarCuit() {
            if(tipo.value === 'DNI') {
                // Habilitar campos necesarios para DNI
                sexo.disabled = false;
                sexo.setAttribute('required', 'required');
                documento.disabled = false;
                documento.setAttribute('required', 'required');
                cuit.disabled = true;
                cuit.removeAttribute('required');
                cuit.placeholder = 'Se calculará automáticamente';
                
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
                // Para CUIT, deshabilitar sexo y número, habilitar CUIT
                sexo.value = '';
                sexo.disabled = true;
                sexo.removeAttribute('required');
                documento.value = '';
                documento.disabled = true;
                documento.removeAttribute('required');
                documento.setCustomValidity('');
                cuit.value = '';
                cuit.disabled = false;
                cuit.setAttribute('required', 'required');
                cuit.placeholder = 'Ingrese el CUIT completo (11 dígitos)';
                cuit.setCustomValidity('');
            }
        }
        
        // Función para validar CUIT
        function validarCuit() {
            if(tipo.value === 'CUIT' && cuit.value.length > 0) {
                // Verificar que sea numérico
                if(!/^\d+$/.test(cuit.value)) {
                    cuit.setCustomValidity('El CUIT solo puede contener números');
                } else if(cuit.value.length !== 11) {
                    cuit.setCustomValidity('El CUIT debe tener exactamente 11 dígitos');
                } else {
                    cuit.setCustomValidity('');
                }
            } else if(tipo.value === 'CUIT' && cuit.value.length === 0) {
                cuit.setCustomValidity('El CUIT es obligatorio');
            } else {
                cuit.setCustomValidity('');
            }
        }

        documento.addEventListener('input', actualizarCuit);
        tipo.addEventListener('change', actualizarCuit);
        sexo.addEventListener('change', actualizarCuit);
        cuit.addEventListener('input', validarCuit);

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

        // Interceptar envío del formulario
        $('form').on('submit', function(e) {
            console.log('Form submit intercepted');
            e.preventDefault();
            
            // Validaciones básicas
            if(!tipo.value) {
                alert('Debe seleccionar un tipo de documento.');
                return false;
            }
            
            if(tipo.value === 'DNI') {
                if(!documento.value || !sexo.value) {
                    alert('Para consultas por DNI debe completar el número de documento y el sexo.');
                    return false;
                }
                if(documento.value.length < 7 || documento.value.length > 8 || !/^\d+$/.test(documento.value)) {
                    alert('El número de documento debe tener entre 7 y 8 dígitos numéricos.');
                    return false;
                }
                // Asegurar que el CUIT esté calculado antes de enviar
                if(!cuit.value) {
                    cuit.value = calcularCuit(documento.value.padStart(8, '0'), sexo.value);
                }
            } else if(tipo.value === 'CUIT') {
                if(!cuit.value) {
                    alert('Para consultas por CUIT debe ingresar el número de CUIT.');
                    return false;
                }
                if(cuit.value.length !== 11 || !/^\d+$/.test(cuit.value)) {
                    alert('El CUIT debe tener exactamente 11 dígitos numéricos.');
                    return false;
                }
            }
            
            // Hacer petición AJAX
            const btnConsultar = $('#btnConsultar');
            const spinner = $('#spinner');
            const btnText = $('#btnText');
            
            btnConsultar.prop('disabled', true);
            spinner.removeClass('d-none');
            btnText.text('Consultando...');
            
            $.post({
                url: '{{ route("admin.operaciones.consultar.api") }}',
                data: $(this).serialize(),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Success response:', response);
                    
                    if(response.success) {
                        // Cambiar el texto del botón para indicar redirección
                        btnText.text('Redirigiendo al informe...');
                        
                        // Usar la URL proporcionada por el servidor o fallback a la ruta fija
                        const redirectUrl = response.redirect_url || '{{ route("admin.operaciones.informe") }}';
                        
                        // Redireccionar después de un breve delay
                        setTimeout(function() {
                            window.location.href = redirectUrl;
                        }, 500);
                    } else {
                        // Si no fue exitosa, mostrar los resultados como error
                        mostrarResultados(response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', xhr.responseText);
                    alert('Error: ' + xhr.responseText);
                },
                complete: function() {
                    btnConsultar.prop('disabled', false);
                    spinner.addClass('d-none');
                    btnText.text('Consultar');
                }
            });
        });
        
        // Función para mostrar resultados
        function mostrarResultados(data) {
            const resultadosContainer = document.getElementById('resultadosContainer');
            const resultadosContent = document.getElementById('resultadosContent');
            
            if(data.success && data.data && data.data.length > 0) {
                let html = '<div class="table-responsive"><table class="table table-striped table-bordered">';
                html += '<thead><tr><th>Campo</th><th>Valor</th></tr></thead><tbody>';
                
                data.data.forEach(item => {
                    for(let key in item) {
                        if(item.hasOwnProperty(key)) {
                            html += `<tr><td><strong>${key}</strong></td><td>${item[key] || '-'}</td></tr>`;
                        }
                    }
                });
                
                html += '</tbody></table></div>';
                resultadosContent.innerHTML = html;
            } else {
                let errorMsg = data.error || 'No se encontraron datos para la consulta realizada.';
                if(data.debug) {
                    console.log('Debug info:', data.debug);
                }
                resultadosContent.innerHTML = `<div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> ${errorMsg}</div>`;
            }
            
            resultadosContainer.style.display = 'block';
            resultadosContainer.scrollIntoView({ behavior: 'smooth' });
        }
        
        // Función para cerrar resultados
        window.cerrarResultados = function() {
            document.getElementById('resultadosContainer').style.display = 'none';
        }

        // Botón Limpiar
        $('#limpiar').on('click', function() {
            $('form')[0].reset();
            actualizarCuit();
        });
    });
</script>

@endsection