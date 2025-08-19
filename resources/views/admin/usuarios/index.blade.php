@extends('layouts.admin')

@section('content')
    <h1>Listado de Usuarios</h1>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php $linea = 1; ?>
            @foreach($usuarios as $usuario)
                <tr>
                    <td style="text-align: right;">{{ $linea++ }}</td>
                    <td>{{ $usuario->name }}</td>
                    <td>{{ $usuario->email }}</td>
                    <td>
                        <a href="{{url('admin/usuarios/'.$usuario->id)}}" type="button" class="btn btn-success btn-sm"><i class="bi bi-eye"></i></a>
                        <a href="{{url('admin/usuarios/'.$usuario->id.'/edit')}}" type="button" class="btn btn-info btn-sm"><i class="bi bi-pencil"></i></a>
                        <a href="{{url('admin/usuarios/'.$usuario->id.'/confirm-delete')}}" type="button" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
@endsection