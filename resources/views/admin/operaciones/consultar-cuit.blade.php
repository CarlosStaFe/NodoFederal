@extends('layouts.admin')

@section('content')

<div class="row">
    <h1>Consulta de empresas por CUIT</h1>
</div>

<div class="col-md-12">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Consultar empresa</h3>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.operaciones.consultar-cuit.api') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-lg-3 col-md-4 position-relative">
                        <label for="cuit" class="form-label">C.U.I.T.</label>
                        <input
                            id="cuit"
                            name="cuit"
                            type="text"
                            value=""
                            class="form-control"
                            placeholder="Ingrese el CUIT de la empresa"
                            minlength="11"
                            maxlength="11"
                            pattern="\d{11}"
                            title="El CUIT debe tener exactamente 11 dígitos"
                            required>
                        @error('cuit')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                    </div>

                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('nodo'))
                    <div class="col-lg-3 col-md-4 position-relative">
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
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-lg-3 col-md-4 position-relative">
                        <label for="socio_id" class="form-label">Socio</label>
                        <select id="socio_id" name="socio_id" class="form-select">
                            <option value="" selected>Todos los socios</option>
                            @foreach($socios as $socio)
                                <option value="{{ $socio->id }}">{{ $socio->razon_social ?? $socio->nombre }}</option>
                            @endforeach
                        </select>
                        @error('socio_id')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                    </div>
                    @endif
                </div>

                <br>
                <p><small class="text-info">* Ingrese únicamente el CUIT de la empresa (11 dígitos). Al consultar será redirigido automáticamente al informe.</small></p>
                <br>

                <div>
                    <button type="button" id="limpiar" class="btn btn-primary me-5">Limpiar</button>
                    <button type="submit" class="btn btn-success me-5" id="btnConsultar">
                        <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        <span id="btnText">Consultar</span>
                    </button>
                    <a href="{{ url('admin') }}" class="btn btn-info" id="btnSalir">Salir</a>
                </div>
            </form>
        </div>
    </div>
</div>

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
            <div id="resultadosContent"></div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        const cuit = document.getElementById('cuit');

        function validarCuit() {
            if (cuit.value.length > 0) {
                if (!/^\d+$/.test(cuit.value)) {
                    cuit.setCustomValidity('El CUIT solo puede contener números');
                } else if (cuit.value.length !== 11) {
                    cuit.setCustomValidity('El CUIT debe tener exactamente 11 dígitos');
                } else {
                    cuit.setCustomValidity('');
                }
            } else {
                cuit.setCustomValidity('El CUIT es obligatorio');
            }
        }

        cuit.addEventListener('input', validarCuit);

        const nodoSelect = document.getElementById('nodo_id');
        const socioSelect = document.getElementById('socio_id');

        if (nodoSelect && socioSelect) {
            function cargarSociosPorNodo() {
                const nodoId = nodoSelect.value;
                socioSelect.innerHTML = '<option value="" selected>Cargando...</option>';

                if (nodoId) {
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
                        .catch(() => {
                            socioSelect.innerHTML = '<option value="" selected>Error al cargar socios</option>';
                        });
                } else {
                    socioSelect.innerHTML = '<option value="" selected>Todos los socios</option>';
                    @foreach($socios as $socio)
                        socioSelect.innerHTML += '<option value="{{ $socio->id }}">{{ $socio->razon_social ?? $socio->nombre }}</option>';
                    @endforeach
                }
            }

            nodoSelect.addEventListener('change', cargarSociosPorNodo);

            @if(auth()->user()->hasRole('nodo') && auth()->user()->nodo_id)
                setTimeout(cargarSociosPorNodo, 100);
            @endif
        }

        $('form').on('submit', function(e) {
            e.preventDefault();

            if (!cuit.value) {
                alert('Debe ingresar el CUIT de la empresa.');
                return false;
            }

            if (cuit.value.length !== 11 || !/^\d+$/.test(cuit.value)) {
                alert('El CUIT debe tener exactamente 11 dígitos numéricos.');
                return false;
            }

            const btnConsultar = $('#btnConsultar');
            const btnLimpiar = $('#limpiar');
            const btnSalir = $('#btnSalir');
            const spinner = $('#spinner');
            const btnText = $('#btnText');

            btnConsultar.prop('disabled', true);
            btnLimpiar.prop('disabled', true);
            btnSalir.addClass('disabled').css('pointer-events', 'none');
            spinner.removeClass('d-none');
            btnText.text('Consultando...');

            $.post({
                url: '{{ route("admin.operaciones.consultar-cuit.api") }}',
                data: $(this).serialize(),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        btnText.text('Redirigiendo al informe...');
                        const redirectUrl = response.redirect_url || '{{ route("admin.operaciones.informe-cuit") }}';
                        setTimeout(function() {
                            window.location.href = redirectUrl;
                        }, 500);
                    } else {
                        mostrarResultados(response);
                    }
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseText);
                },
                complete: function() {
                    btnConsultar.prop('disabled', false);
                    btnLimpiar.prop('disabled', false);
                    btnSalir.removeClass('disabled').css('pointer-events', '');
                    spinner.addClass('d-none');
                    btnText.text('Consultar');
                }
            });
        });

        function mostrarResultados(data) {
            const resultadosContainer = document.getElementById('resultadosContainer');
            const resultadosContent = document.getElementById('resultadosContent');

            let errorMsg = data.error || 'No se encontraron datos para la consulta realizada.';
            resultadosContent.innerHTML = `<div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> ${errorMsg}</div>`;

            resultadosContainer.style.display = 'block';
            resultadosContainer.scrollIntoView({ behavior: 'smooth' });
        }

        window.cerrarResultados = function() {
            document.getElementById('resultadosContainer').style.display = 'none';
        }

        $('#limpiar').on('click', function() {
            $('form')[0].reset();
            cuit.value = '';
            cuit.setCustomValidity('');

            const nodoField = document.getElementById('nodo_id');
            const socioField = document.getElementById('socio_id');
            if (nodoField) nodoField.value = '';
            if (socioField) socioField.value = '';

            const resultadosContainer = document.getElementById('resultadosContainer');
            if (resultadosContainer) {
                resultadosContainer.style.display = 'none';
            }
        });

        history.pushState(null, null, location.href);
        window.addEventListener('popstate', function() {
            history.go(1);
        });
    });
</script>

@endsection
