@extends('layouts.admin')

@section('content')

    <div class="row">
        <h2>Panel Principal - {{Auth::user()->email}}</h2>
    </div>

    <hr>

    <div class="row">
        {{-- @can('admin.usuarios.index') --}}
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
        {{-- @endcan --}}

        {{-- @can('admin.nodos.index') --}}
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{$total_nodos}}</h3>
                        <p>Nodos</p>
                    </div>
                    <div class="icon">
                        <i class="fas bi bi-person-badge"></i>
                    </div>
                    <a href="{{url('admin/nodos')}}" class="small-box-footer">Más información <i class="fas bi bi-person-badge"></i></a>
                </div>
            </div>
        {{-- @endcan --}}

        {{-- @can('admin.socios.index') --}}
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{$total_socios}}</h3>
                        <p>Socios</p>
                    </div>
                    <div class="icon">
                        <i class="fas bi bi-person-badge"></i>
                    </div>
                    <a href="{{url('admin/socios')}}" class="small-box-footer">Más información <i class="fas bi bi-person-badge"></i></a>
                </div>
            </div>
        {{-- @endcan --}}

        {{-- @can('admin.clientes.index') --}}
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{$total_clientes}}</h3>
                        <p>Clientes</p>
                    </div>
                    <div class="icon">
                        <i class="fas bi bi-person-badge"></i>
                    </div>
                    <a href="{{url('admin/clientes')}}" class="small-box-footer">Más información <i class="fas bi bi-person-badge"></i></a>
                </div>
            </div>
        {{-- @endcan --}}
    </div>

@endsection