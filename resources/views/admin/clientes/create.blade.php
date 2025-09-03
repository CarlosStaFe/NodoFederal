@extends('layouts.admin')

@section('content')

<div class="row">
    <h1>Registrar Clientes</h1>
</div>

<div class="col-md-12">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Completar los datos</h3>
        </div>

        <div class="card-body">
            <form action="{{url('/admin/clientes/create')}}" method="POST">
                @csrf
                <div class="row">
                    <div>
                        <input id="nombrelocal" name="nombrelocal" type="hidden">
                        <input id="nombreprov" name="nombreprov" type="hidden">
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="tipodoc" class="form-label">Tipo Doc.</label><b>*</b>
                            <select id="tipodoc" name="tipodoc" class="form-select" required>
                                <option selected disabled>Tipo...</option>
                                <option value="DNI">DNI</option>
                                <option value="LC">LC</option>
                                <option value="LE">LE</option>
                                <option value="CI">CI</option>
                                <option value="PAS">PAS</option>
                            </select>
                            @error('tipodoc')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-4">
                        <div class="form group">
                            <label for="sexo">Sexo</label><b>*</b>
                            <select type="text" class="form-control" value="{{old('sexo')}}" id="sexo" name="sexo" placeholder="Sexo" required>
                                <option selected disabled>Elige sexo...</option>
                                <option value="M">M</option>
                                <option value="F">F</option>
                                <option value="S">S</option>
                            </select>
                            @error('sexo')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="documento" class="form-label">Documento</label><b>*</b>
                            <input id="documento" name="documento" type="text" class="form-control" placeholder="Documento..." required>
                            @error('documento')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="cuit">C.U.I.T.</label><b>*</b>
                            <input type="text" class="form-control" value="{{ old('cuit', isset($cuit) ? $cuit : '') }}" id="cuit" name="cuit" placeholder="C.U.I.T." required readonly>
                            @error('cuit')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="apelnombres" class="form-label">Apellido y Nombres</label><b>*</b>
                            <input id="apelnombres" name="apelnombres" type="text" class="form-control" placeholder="Apellido y Nombres" required>
                            @error('apelnombres')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="nacimiento" class="form-label">Fecha Nac.</label><b>*</b>
                            <input id="nacimiento" name="nacimiento" type="date" class="form-control" required>
                            @error('nacimiento')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="edad" class="form-label">Edad</label>
                            <input id="edad" name="edad" type="number" class="form-control" required readonly>
                            @error('edad')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nacionalidad" class="form-label">Nacionalidad</label><b>*</b>
                            <select id="nacionalidad" name="nacionalidad" class="form-control" required>
                                <option selected disabled>Elige nacionalidad...</option>
                                <option value="Argentina">Argentina</option>
                                <option value="Boliviana">Boliviana</option>
                                <option value="Brasileña">Brasileña</option>
                                <option value="Chilena">Chilena</option>
                                <option value="Colombiana">Colombiana</option>
                                <option value="Ecuatoriana">Ecuatoriana</option>
                                <option value="Paraguaya">Paraguaya</option>
                                <option value="Peruana">Peruana</option>
                                <option value="Uruguaya">Uruguaya</option>
                                <option value="Venezolana">Venezolana</option>
                                <option value="Otra">Otra</option>
                            </select>
                            @error('nacionalidad')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="domicilio">Domicilio</label><b>*</b>
                            <input type="text" class="form-control" value="{{old('domicilio')}}" id="domicilio" name="domicilio" placeholder="Domicilio" required>
                            @error('domicilio')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <label for="provincia">Provincia</label><b>*</b>
                            <select type="text" class="form-control" value="{{old('provincia')}}" id="provincia" name="provincia" placeholder="Provincia">
                                <option selected disabled>Elige provincia...</option>
                                <option value="6">BUENOS AIRES</option>
                                <option value="2">CABA</option>
                                <option value="10">CATAMARCA</option>
                                <option value="22">CHACO</option>
                                <option value="26">CHUBUT</option>
                                <option value="18">CORRIENTES</option>
                                <option value="14">CORDOBA</option>
                                <option value="30">ENTRE RIOS</option>
                                <option value="34">FORMOSA</option>
                                <option value="38">JUJUY</option>
                                <option value="42">LA PAMPA</option>
                                <option value="46">LA RIOJA</option>
                                <option value="50">MENDOZA</option>
                                <option value="54">MISIONES</option>
                                <option value="58">NEUQUEN</option>
                                <option value="62">RIO NEGRO</option>
                                <option value="70">SAN JUAN</option>
                                <option value="74">SAN LUIS</option>
                                <option value="66">SALTA</option>
                                <option value="78">SANTA CRUZ</option>
                                <option value="82">SANTA FE</option>
                                <option value="86">SANTIAGO DEL ESTERO</option>
                                <option value="94">TIERRA DEL FUEGO</option>
                                <option value="90">TUCUMAN</option>
                            </select>
                            @error('provincia')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <label for="localidad">Localidad</label><b>*</b>
                            <select type="text" class="form-control" value="{{old('localidad')}}" id="localidad" name="localidad" placeholder="Localidad">
                            </select>
                            @error('localidad')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-12">
                        <div class="form-group">
                            <label for="cod_postal">Cod.Postal</label><b>*</b>
                            <select type="text" class="form-control" value="{{old('cod_postal')}}" id="cod_postal" name="cod_postal" placeholder="Código">
                            </select>
                            @error('cod_postal')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <div class="form group">
                            <label for="telefono">Teléfono</label><b>*</b>
                            <input type="text" class="form-control" value="{{old('telefono')}}" id="telefono" name="telefono" placeholder="Teléfono" required>
                            @error('telefono')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form group">
                            <label for="email">Email</label><b>*</b>
                            <input type="email" class="form-control" value="{{old('email')}}" id="email" name="email" placeholder="Email" required>
                            @error('email')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-4">
                        <div class="form group">
                            <label for="estado">Estado</label><b>*</b>
                            <select type="text" class="form-control" value="{{old('estado')}}" id="estado" name="estado" placeholder="Estado">
                                <option selected disabled>Elige estado...</option>
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                                <option value="Atrasado">Atrasado</option>
                                <option value="Cancelado">Cancelado</option>
                                <option value="Cancelado con atraso">Cancelado con atraso</option>
                                <option value="Afectado">Afectado</option>
                                <option value="En Convenio">En Convenio</option>
                            </select>
                            @error('estado')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-sm-4">
                        <div class="form-group">
                            <label for="observacion">Observación</label>
                            <textarea class="form-control" value={{old('observacion')}} id="observacion" name="observacion" placeholder="Ingrese una observación"></textarea>
                            @error('observacion')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <br>
                <div class="form group">
                    <a href="{{url('admin/clientes')}}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Registrar Cliente</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Calcular edad automáticamente al ingresar la fecha de nacimiento
    document.addEventListener('DOMContentLoaded', function() {
        var nacimientoInput = document.getElementById('nacimiento');
        var edadInput = document.getElementById('edad');
        if(nacimientoInput && edadInput) {
            nacimientoInput.addEventListener('change', function() {
                var nacimiento = this.value;
                if(nacimiento) {
                    var hoy = new Date();
                    var fechaNac = new Date(nacimiento);
                    var edad = hoy.getFullYear() - fechaNac.getFullYear();
                    var m = hoy.getMonth() - fechaNac.getMonth();
                    if (m < 0 || (m === 0 && hoy.getDate() < fechaNac.getDate())) {
                        edad--;
                    }
                    edadInput.value = edad > 0 ? edad : '';
                } else {
                    edadInput.value = '';
                }
            });
        }
    });
</script>

<script>
    // Script para seleccionar la provincia y la localidad
    document.getElementById('provincia').addEventListener('change', function() {
        const idProv = this.value;
        const nombreProv = this.options[this.selectedIndex].text;
        const localidadSelect = document.getElementById('localidad');
        document.getElementById('nombreprov').value = nombreProv;

        // Limpiar las opciones actuales
        localidadSelect.innerHTML = '<option value="">Seleccione una localidad</option>';

        if (idProv) {
            // Realizar la solicitud AJAX
            fetch(`{{url('admin/localidades') }}/${idProv}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(localidad => {
                        const option = document.createElement('option');
                        option.value = localidad.id_local;
                        option.textContent = localidad.localidad;
                        localidadSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error al cargar localidades:', error));
        }
    });

    document.getElementById('localidad').addEventListener('change', function() {
        const idLocal = this.value;
        const nombreLocal = this.options[this.selectedIndex].text;
        const codpostalSelect = document.getElementById('cod_postal');
        document.getElementById('nombrelocal').value = nombreLocal;

        // Limpiar las opciones de códigos postales
        codpostalSelect.innerHTML = '<option selected disabled>Elige un Código...</option>';

        // Hacer una solicitud AJAX para obtener las localidades
        fetch(`{{url('admin/codpostales') }}/${idLocal}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(codigos => {
                    const option = document.createElement('option');
                    option.value = codigos.id;
                    option.textContent = codigos.cod_postal;
                    codpostalSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error al obtener los códigos postales:', error));
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function actualizarCuit() {
            const dni = document.getElementById('documento').value;
            let sexo = '';
            // Puedes tener un select o radio para sexo/tipo
            if(document.getElementById('sexo')) {
                sexo = document.getElementById('sexo').value;
            } else if(document.getElementById('tipopersona')) {
                // Si tienes un select para tipo de persona (M/F/S)
                sexo = document.getElementById('tipopersona').value;
            }
            if(dni && sexo) {
                fetch("{{ route('clientes.calcular-cuit') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({dni: dni, sexo: sexo})
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('cuit').value = data.cuit || '';
                });
            }
        }
        document.getElementById('documento').addEventListener('input', actualizarCuit);
        if(document.getElementById('sexo')) {
            document.getElementById('sexo').addEventListener('change', actualizarCuit);
        }
        if(document.getElementById('tipopersona')) {
            document.getElementById('tipopersona').addEventListener('change', actualizarCuit);
        }
    });
</script>

@endsection