@extends('layouts.admin')

@section('content')

    <div class="row">
        <h2>Panel Principal - {{Auth::user()->email}}</h2>
    </div>

    <hr>

    <div class="row">
        @can('admin.usuarios.index')
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{$total_usuarios}}</h3>
                        <p>Usuarios</p>
                    </div>
                    <div class="icon">
                        <i class="fas bi bi-person-badge"></i>
                    </div>
                    <a href="{{url('admin/usuarios')}}" class="small-box-footer">Más información <i class="fas bi bi-person-badge"></i></a>
                </div>
            </div>
        @endcan

    @role('admin|secretaria')
            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{$total_nodos}}</h3>
                        <p>Nodos</p>
                    </div>
                    <div class="icon">
                        <i class="fas bi bi-diagram-3-fill"></i>
                    </div>
                    <a href="{{url('admin/nodos')}}" class="small-box-footer">Más información <i class="fas bi bi-diagram-3-fill"></i></a>
                </div>
            </div>
    @endrole

        @can('admin.nodos.index')
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{$total_socios}}</h3>
                        <p>Socios</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-solid fa-building-columns"></i>
                    </div>
                    <a href="{{url('admin/socios')}}" class="small-box-footer">Más información <i class="fas fa-solid fa-building-columns"></i></a>
                </div>
            </div>
        @endcan

        @can('admin.socios.index')
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{$total_clientes}}</h3>
                        <p>Clientes</p>
                    </div>
                    <div class="icon">
                        <i class="fas bi bi-people-fill"></i>
                    </div>
                    <a href="{{url('admin/clientes')}}" class="small-box-footer">Más información <i class="fas bi bi-people-fill"></i></a>
                </div>
            </div>
        @endcan

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{$total_operaciones}}</h3>
                <p>Operaciones</p>
            </div>
            <div class="icon">
                <i class="fas bi bi-briefcase-fill"></i>
            </div>
            <a href="{{url('admin/operaciones/cargar')}}" class="small-box-footer">Cargar Operaciones <i class="fas bi bi-briefcase-fill"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3>{{$total_operaciones}}</h3>
                    <p>Consultas</p>
                </div>
                <div class="icon">
                    <i class="fas bi bi-search"></i>
                </div>
                <a href="{{url('admin/operaciones/consultar')}}" class="small-box-footer">Realizar Consultas <i class="fas bi bi-search"></i></a>
            </div>
        </div>

    </div>

@endsection