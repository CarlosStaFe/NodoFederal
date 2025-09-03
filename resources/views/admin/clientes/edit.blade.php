@extends('layouts.admin')

@section('content')

<div class="row">
    <h1>Modificar cliente: {{$cliente->apelnombres}}</h1>
</div>

<div class="col-md-12">
    <div class="card card-outline card-info">
        <div class="card-header">
            <h3 class="card-title">Actualizar los datos</h3>
        </div>

        <div class="card-body">
            <form action="{{url('/admin/clientes',$cliente->id)}}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div>
                        <input id="nombrelocal" name="nombrelocal" type="hidden">
                        <input id="nombreprov" name="nombreprov" type="hidden">
                        <input id="codigopostal" name="codigopostal" type="hidden" value="{{$cliente->localidad->id}}">
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="tipodoc" class="form-label">Tipo Doc.</label><b>*</b>
                            <select id="tipodoc" name="tipodoc" class="form-select" required>
                                <option value="{{$cliente->tipodoc}}">{{$cliente->tipodoc ?? 'N/A'}}</option>
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
                            <select type="text" class="form-control" id="sexo" name="sexo" placeholder="Sexo" required>
                                <option value="{{$cliente->sexo}}">{{$cliente->sexo ?? 'N/A'}}</option>
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
                            <input id="documento"  value="{{$cliente->documento}}" name="documento" type="text" class="form-control" placeholder="Documento..." required>
                            @error('documento')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="cuit">C.U.I.T.</label><b>*</b>
                            <input type="text" class="form-control" value="{{$cliente->cuit}}" id="cuit" name="cuit" placeholder="C.U.I.T." required readonly>
                            @error('cuit')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="apelnombres" class="form-label">Apellido y Nombres</label><b>*</b>
                            <input id="apelnombres" value="{{$cliente->apelnombres}}" name="apelnombres" type="text" class="form-control" placeholder="Apellido y Nombres" required>
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
                            <input id="nacimiento" value="{{ $cliente->nacimiento ? \Carbon\Carbon::parse($cliente->nacimiento)->format('d-m-Y') : '' }}" name="nacimiento" type="text" class="form-control" required placeholder="dd-mm-aaaa">
                            @error('nacimiento')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="edad" class="form-label">Edad</label>
                            <input id="edad" name="edad" type="number" class="form-control" value="{{ $cliente->nacimiento ? \Carbon\Carbon::parse($cliente->nacimiento)->age : '' }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nacionalidad" class="form-label">Nacionalidad</label><b>*</b>
                            <select id="nacionalidad" name="nacionalidad" class="form-control" required>
                                <option disabled {{ !$cliente->nacionalidad ? 'selected' : '' }}>Elige nacionalidad...</option>
                                <option value="Argentina" {{ $cliente->nacionalidad == 'Argentina' ? 'selected' : '' }}>Argentina</option>
                                <option value="Boliviana" {{ $cliente->nacionalidad == 'Boliviana' ? 'selected' : '' }}>Boliviana</option>
                                <option value="Brasileña" {{ $cliente->nacionalidad == 'Brasileña' ? 'selected' : '' }}>Brasileña</option>
                                <option value="Chilena" {{ $cliente->nacionalidad == 'Chilena' ? 'selected' : '' }}>Chilena</option>
                                <option value="Colombiana" {{ $cliente->nacionalidad == 'Colombiana' ? 'selected' : '' }}>Colombiana</option>
                                <option value="Ecuatoriana" {{ $cliente->nacionalidad == 'Ecuatoriana' ? 'selected' : '' }}>Ecuatoriana</option>
                                <option value="Paraguaya" {{ $cliente->nacionalidad == 'Paraguaya' ? 'selected' : '' }}>Paraguaya</option>
                                <option value="Peruana" {{ $cliente->nacionalidad == 'Peruana' ? 'selected' : '' }}>Peruana</option>
                                <option value="Uruguaya" {{ $cliente->nacionalidad == 'Uruguaya' ? 'selected' : '' }}>Uruguaya</option>
                                <option value="Venezolana" {{ $cliente->nacionalidad == 'Venezolana' ? 'selected' : '' }}>Venezolana</option>
                                <option value="Otra" {{ $cliente->nacionalidad == 'Otra' ? 'selected' : '' }}>Otra</option>
                            </select>
                            @error('nacionalidad')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="domicilio">Domicilio</label><b>*</b>
                            <input type="text" class="form-control" value="{{$cliente->domicilio}}" id="domicilio" name="domicilio" placeholder="Domicilio" required>
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
                            <select type="text" class="form-control" value="{{strtoupper($cliente->localidad->provincia ?? 'N/A')}}" id="provincia" name="provincia" placeholder="Provincia">
                                <option value="{{$cliente->localidad->id_prov}}">{{strtoupper($cliente->localidad->provincia ?? 'N/A')}}</option>
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
                            <select type="text" class="form-control" value="{{strtoupper($cliente->localidad->localidad ?? 'N/A')}}" id="localidad" name="localidad" placeholder="Localidad">
                                <option value="{{$cliente->localidad->id_local}}">{{strtoupper($cliente->localidad->localidad ?? 'N/A')}}</option>
                            </select>
                            @error('localidad')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-12">
                        <div class="form-group">
                            <label for="cod_postal">Cod.Postal</label><b>*</b>
                            <select type="text" class="form-control" value="{{$cliente->localidad->cod_postal ?? 'N/A' }}" id="cod_postal" name="cod_postal" placeholder="Código">
                                <option value="{{$cliente->localidad->id}}">{{strtoupper($cliente->localidad->cod_postal ?? 'N/A')}}</option>
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
                            <input type="text" class="form-control" value="{{ $cliente->telefono }}" id="telefono" name="telefono" placeholder="Teléfono" required>
                            @error('telefono')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form group">
                            <label for="email">Email</label><b>*</b>
                            <input type="email" class="form-control" value="{{ $cliente->email }}" id="email" name="email" placeholder="Email" required>
                            @error('email')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-4">
                        <div class="form group">
                            <label for="estado">Estado</label><b>*</b>
                            <select type="text" class="form-control" value="{{$cliente->estado}}" id="estado" name="estado" placeholder="Estado">
                                <option value="{{$cliente->estado}}">{{strtoupper($cliente->estado ?? 'N/A')}}</option>
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
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fechaestado" class="form-label">Fecha Estado</label><b>*</b>
                            <input id="fechaestado" value="{{ $cliente->fechaestado ? \Carbon\Carbon::parse($cliente->fechaestado)->format('d-m-Y') : '' }}" name="fechaestado" type="text" class="form-control" required placeholder="dd-mm-aaaa">
                            @error('fechaestado')
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
                            <textarea class="form-control" id="observacion" name="observacion" placeholder="Ingrese una observación">{{ $cliente->observacion }}</textarea>
                            @error('observacion')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <br>
                <div class="form group">
                    <a href="{{url('admin/clientes')}}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-info">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Calcular edad automáticamente al cambiar la fecha de nacimiento en edición
    document.addEventListener('DOMContentLoaded', function() {
        var nacimientoInput = document.getElementById('nacimiento');
        var edadInput = document.getElementById('edad');
        if(nacimientoInput && edadInput) {
            nacimientoInput.addEventListener('change', function() {
                var nacimiento = this.value;
                if(nacimiento) {
                    // Soporta formato dd-mm-yyyy
                    var partes = nacimiento.split('-');
                    var fechaNac = new Date(partes[2], partes[1] - 1, partes[0]);
                    var hoy = new Date();
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
    document.getElementById('provincia').addEventListener('change', function () {
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

@endsection