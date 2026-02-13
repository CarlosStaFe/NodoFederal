<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nodo Federal</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/ICONO_LF.ico') }}" />
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ url('dist/css/adminlte.min.css?v=3.2.0') }}">
    <!-- Iconos Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- jQuery -->
    <script src="{{url('plugins/jquery/jquery.min.js')}}"></script>
    <!-- Sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- DataTables Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ url('/admin') }}" class="nav-link">Sistema Nodo Federal</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#"
                        role="button">
                        <i class="fas fa-th-large"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="index3.html" class="brand-link">
                <img src="{{ asset('dist/img/NODO FEDERAL - LOGO.jpeg') }}" alt="Nodo Federal Logo" class="img-circle" style="height:80px">
                <span class="brand-text font-weight-light"> </span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="info">
                        <a href="" class="d-block">Usuario: <b>{{ Auth::user()->name }}</b></a>
                        <a href="" class="d-block">Rol: <b>{{ Auth::user()->roles->pluck('name')->first() }}</b></a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Administración -->
                        @can('admin.administracion.index')
                            <li class="nav-item">
                                <a href="#" class="nav-link active">
                                    <i class="nav-icon fas bi bi-coin"></i>
                                    <p>
                                        Administración
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ url('admin/administracion/consultar') }}" class="nav-link active">
                                            <i class="bi bi-inboxes nav-icon"></i>
                                            <p>Consultar Totales</p>
                                        </a>
                                    </li>
                                    @if(auth()->user()->hasRole(['admin', 'secretaria']))
                                        <li class="nav-item">
                                            <a href="{{ url('admin/administracion/basedatos') }}" class="nav-link active">
                                                <i class="bi bi-database-gear nav-icon"></i>
                                                <p>Base de Datos</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ url('admin/administracion/conectados') }}" class="nav-link active">
                                                <i class="bi bi-people nav-icon"></i>
                                                <p>Usuarios Conectados</p>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endcan
                        <!-- Usuarios -->
                        @can('admin.usuarios.index')
                            <li class="nav-item">
                                <a href="#" class="nav-link active">
                                    <i class="nav-icon fas bi bi-person-badge"></i>
                                    <p>
                                        Usuarios
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ url('admin/usuarios/create') }}" class="nav-link active">
                                            <i class="bi bi-person-bounding-box nav-icon"></i>
                                            <p>Crear Usuarios</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/usuarios') }}" class="nav-link active">
                                            <i class="bi bi-list-check nav-icon"></i>
                                            <p>Listado de Usuarios</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endcan
                        <!-- Nodos -->
                        @can('admin.nodos.index')
                            <li class="nav-item">
                                <a href="#" class="nav-link active">
                                    <i class="nav-icon fas bi bi-diagram-3-fill"></i>
                                    <p>
                                        Nodos
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ url('admin/nodos/create') }}" class="nav-link active">
                                            <i class="bi bi-geo nav-icon"></i>
                                            <p>Crear Nodos</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/nodos') }}" class="nav-link active">
                                            <i class="bi bi-list-columns-reverse nav-icon"></i>
                                            <p>Listado de Nodos</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endcan
                        <!-- Socios -->
                        @can('admin.socios.index')
                            <li class="nav-item">
                                <a href="#" class="nav-link active">
                                    <i class="nav-icon fas fa-solid fa-building-columns"></i>
                                    <p>
                                        Socios
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ url('admin/socios/create') }}" class="nav-link active">
                                            <i class="bi bi-buildings nav-icon"></i>
                                            <p>Crear Socios</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/socios') }}" class="nav-link active">
                                            <i class="bi bi-card-checklist nav-icon"></i>
                                            <p>Listado de Socios</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endcan
                        <!-- Clientes -->
                        @can('admin.clientes.index')
                            <li class="nav-item">
                                <a href="#" class="nav-link active">
                                    <i class="nav-icon fas bi bi-people-fill"></i>
                                    <p>
                                        Clientes
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ url('admin/clientes/create') }}" class="nav-link active">
                                            <i class="bi bi-person-plus nav-icon"></i>
                                            <p>Crear Clientes</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/clientes') }}" class="nav-link active">
                                            <i class="bi bi-people nav-icon"></i>
                                            <p>Listado de Clientes</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endcan
                        <!-- Operaciones -->
                        @can('admin.operaciones.index')
                            <li class="nav-item">
                                <a href="#" class="nav-link active">
                                    <i class="nav-icon fas bi bi-briefcase-fill"></i>
                                    <p>
                                        Operaciones
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ url('admin/operaciones/consultar') }}" class="nav-link active">
                                            <i class="bi bi-search nav-icon"></i>
                                            <p>Consultar Antecedentes</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/operaciones/cargar') }}" class="nav-link active">
                                            <i class="bi bi-bag-plus nav-icon"></i>
                                            <p>Cargar Operaciones</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin/operaciones/show') }}" class="nav-link active">
                                            <i class="bi bi-fire nav-icon"></i>
                                            <p>Afectar/Desafectar Cliente</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endcan
                        <!-- Cerrar Sesión -->
                        <li class="nav-item">
                            <a href="{{ route('logout') }}" class="nav-link" style="background-color: #a9200e;"
                                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                <i class="nav-icon fas bi bi-door-open-fill"></i>
                                <p>
                                    Cerrar Sesión
                                </p>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <div class="content-wrapper">
            <div class="container">
                @yield('content')
            </div>
        </div>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
            <div class="p-3">
                <h5>Título</h5>
                <p>Contenido Barra Lateral</p>
            </div>
        </aside>

        <!-- SweetAlert -->
        @if((($message = Session::get('mensaje')) && ($icono = Session::get('icono'))))
            <script>
                Swal.fire({
                    //position: "top-end",
                    icon: "{{$icono}}",
                    title: "{{$message}}",
                    text: "{{$text ?? ''}}",
                    confirmButtonText: "{{$confirmButtonText ?? 'Aceptar'}}",
                    showConfirmButton: "{{$showConfirmButton ?? 'false'}}",
                    timer: "{{$timer ?? 3000}}",
                });
            </script>
        @endif

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline">
                Sistema Nodo Federal
            </div>
            <!-- Default to the left -->
            <strong>Copyright &copy; 2026 - <a href="#">OM Computación</a>.</strong> Todos los derechos reservados.
        </footer>
    </div>

    <!-- REQUIRED SCRIPTS -->

    <!-- Bootstrap 4 -->
    <script src="{{ url('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- DataTables Bootstrap 5 & Plugins -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="{{url('plugins/jszip/jszip.min.js')}}"></script>
    <script src="{{url('plugins/pdfmake/pdfmake.min.js')}}"></script>
    <script src="{{url('plugins/pdfmake/vfs_fonts.js')}}"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
    <!-- AdminLTE App -->
    <script src="{{ url('dist/js/adminlte.min.js?v=3.2.0') }}"></script>
</body>

</html>
