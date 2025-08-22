@extends('layouts.admin')

@section('content')

<div class="row">
    <h1>Consulta de antecedentes: {{$usuario->name}}</h1>
</div>

<div class="col-md-12">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Consultar</h3>
        </div>

        <div class="card-body">
            <form action="{{url('/admin/operaciones/'.$usuario->id)}}" method="POST">
                @csrf
                <div class="col-lg-2 col-md-1 position-relative">
                    <label for="tipo" class="form-label">Tipo</label>
                    <select id="tipo" name="tipo" class="form-select" required>
                        <option value="DNI">DNI</option>
                        <option value="CUIL">CUIL</option>
                    </select>
                    <div class="invalid-tooltip">Debe ingresar el tipo de documento a consultar</div>
                </div>
                <div class="col-lg-2 col-md-2 position-relative">
                    <label for="documento" class="form-label">Número</label>
                    <input id="documento" name="documento" type="text" value="" class="form-control" required>
                    <div class="invalid-tooltip">Debe ingresar el numero a consultar</div>
                </div> 
                <div class="bg-transparent borde-primary">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                    <button type="button" class="btn btn-warning" id="limpiarBtn">Limpiar</button>
                    <a href="/" type="button" class="btn btn-secondary">Salir</a>
                </div>
                    <br>
                    <div class="form group">
                        <a href="{{url('admin/operaciones')}}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Consultar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('form');
            form.addEventListener('submit', async (event) => {
                event.preventDefault();
            
                const tipo = document.getElementById('tipo').value;
                const documento = document.getElementById('documento').value;

                try {
                    const response = await fetch('/api/consultar', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ tipo, documento })
                    });
                
                    if (!response.ok) {
                        throw new Error(`Error en la consulta: ${response.status}`);
                    }
                
                    const datos = await response.json();
                    console.log('Respuesta de la API:', datos);

                    if (!datos || Object.keys(datos).length === 0) {
                        throw new Error('No se encontraron datos para el documento proporcionado.');
                    }

                    // Aquí puedes mostrar los datos en el div resultado si lo deseas
                    document.getElementById('modalResultadoBody').innerHTML = `
                        <div class="alert alert-success">
                            <strong>Resultado:</strong>
                            <pre>${JSON.stringify(datos, null, 2)}</pre>
                        </div>
                    `;
                    // Mostrar el modal
                    var modal = new bootstrap.Modal(document.getElementById('resultadoModal'));
                    modal.show();
                } catch (error) {
                    document.getElementById('resultado').innerHTML = `
                        <div class="alert alert-danger">
                            <strong>Error:</strong> ${error.message}
                        </div>
                    `;
                }
            });
        });
    </script>
    
@endsection