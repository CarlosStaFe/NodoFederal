@extends('layouts.admin')

@section('content')

<div class="row">
    <h1>Registrar Nodos</h1>
</div>

<div class="col-md-12">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Completar los datos</h3>
        </div>

        <div class="card-body">
            <form action="{{url('/admin/nodos/create')}}" method="POST">
                @csrf
                <div class="row">
                    <div>
                        <input id="nombrelocal" name="nombrelocal" type="hidden">
                        <input id="nombreprov" name="nombreprov" type="hidden">
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="numero">Número</label><b>*</b>
                            <input type="number" class="form-control" value="{{ old('numero', isset($numero) ? $numero : '') }}" id="numero" name="numero" placeholder="Nodo" required readonly>
                            @error('numero')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="factura">Factura</label><b>*</b>
                            <input type="number" class="form-control" value="{{ old('factura', isset($factura) ? $factura : '') }}" id="factura" name="factura" placeholder="Número de Facturación" required>
                            @error('factura')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="nombre">Nombre del Nodo</label><b>*</b>
                            <input type="text" class="form-control" value="{{old('nombre')}}" id="nombre" name="nombre" placeholder="Nombre del Nodo" required>
                            @error('nombre')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="cuit">C.U.I.T.</label><b>*</b>
                            <input type="text" class="form-control" value="{{old('cuit')}}" id="cuit" name="cuit" placeholder="C.U.I.T." required>
                            @error('cuit')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="tipo">Tipo I.V.A.</label><b>*</b>
                            <select type="text" class="form-control" value="{{old('tipo')}}" id="tipo" name="tipo" placeholder="Tipo I.V.A." required>
                                <option selected disabled>Elige una tipo de I.V.A...</option>
                                <option value="Consumidor Final">Consumidor Final</option>
                                <option value="Exento">Exento</option>
                                <option value="Monotributo">Monotributo</option>
                                <option value="No Responsable">No Responsable</option>
                                <option value="Resp. Inscripto">Resp. Inscripto</option>
                            </select>
                            @error('tipo')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="domicilio">Domicilio</label><b>*</b>
                            <input type="text" class="form-control" value="{{old('domicilio')}}" id="domicilio" name="domicilio" placeholder="Domicilio" required>
                            @error('domicilio')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
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
                    <div class="col-md-3 col-sm-12">
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
                    <div class="col-md-5 col-sm-4">
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
                            </select>
                            @error('estado')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-3 col-sm-4">
                        <div class="form group">
                            <label for="valor_consulta">Valor por Consulta</label>
                            <input type="number" class="form-control" value="{{ old('valor_consulta') }}" id="valor_consulta" name="valor_consulta" placeholder="Valor por consulta" step="0.01" min="0" pattern="^\d+(\.\d{1,2})?$" inputmode="decimal">
                            @error('valor_consulta')
                                <small style="color: red">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-4">
                        <div class="form group">
                            <label for="valor_afectar">Valor por Afectar</label>
                            <input type="number" class="form-control" value="{{ old('valor_afectar') }}" id="valor_afectar" name="valor_afectar" placeholder="Valor por afectar"  step="0.01" min="0" pattern="^\d+(\.\d{1,2})?$" inputmode="decimal">
                            @error('valor_afectar')
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
                    <a href="{{url('admin/nodos')}}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Registrar Nodo</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- // Script para seleccionar la provincia y la localidad -->
<script>
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

@endsection