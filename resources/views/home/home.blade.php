<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Sistema Seguro</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Asegurar que el navbar ocupe todo el ancho */
        .navbar {
            width: 100%;
            margin: 0;
            border-radius: 0;
        }
        body {
            margin: 0;
            padding: 0;
        }
    </style>
</head>
</head>
<body>

    <!-- Navbar superior -->
    <nav class="navbar navbar-expand-lg navbar-dark shadow">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="bi bi-shield-lock-fill"></i> Sistema Seguro
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">
                            <i class="bi bi-house-fill"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-person"></i> Mi Perfil
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-grid"></i> Módulos
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-people"></i> Usuarios</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-diagram-3"></i> Departamentos</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-briefcase"></i> Cargos</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ session('usuario', 'Usuario') }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Botón para menú en móvil -->
    <button class="menu-toggle" onclick="toggleMenu()">
        <i class="bi bi-list"></i>
    </button>

    <!-- Menú lateral izquierdo con scroll y submenús -->
    <div class="sidebar-left" id="sidebarMenu">
        <div class="sidebar-header">
            <i class="bi bi-grid-3x3-gap-fill" style="font-size: 2rem; color: #4a6fa5;"></i>
            <h4>Menú de Accesos</h4>
            <p>{{ session('cargo', 'Usuario') }} | {{ session('departamento', 'General') }}</p>
        </div>
        
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="#" class="nav-link-custom">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <!-- Usuarios con submenu -->
            <li class="nav-item">
                <input type="checkbox" id="submenuUsuarios" class="submenu-toggle">
                <label for="submenuUsuarios" class="submenu-label">
                    <i class="bi bi-people"></i>
                    <span>Gestión de Usuarios</span>
                    <i class="bi bi-chevron-down dropdown-arrow"></i>
                </label>
                <ul class="submenu">
                    <li><a href="#"><i class="bi bi-person-plus"></i> Crear Usuario</a></li>
                    <li><a href="#"><i class="bi bi-person-badge"></i> Listar Usuarios</a></li>
                    <li><a href="#"><i class="bi bi-shield"></i> Roles y Permisos</a></li>
                </ul>
            </li>
            
            <!-- Departamentos con submenu -->
            <li class="nav-item">
                <input type="checkbox" id="submenuDeptos" class="submenu-toggle">
                <label for="submenuDeptos" class="submenu-label">
                    <i class="bi bi-diagram-3"></i>
                    <span>Departamentos</span>
                    <i class="bi bi-chevron-down dropdown-arrow"></i>
                </label>
                <ul class="submenu">
                    <li><a href="#"><i class="bi bi-plus-circle"></i> Nuevo Departamento</a></li>
                    <li><a href="#"><i class="bi bi-list-ul"></i> Listar Departamentos</a></li>
                </ul>
            </li>
            
            <!-- Cargos con submenu -->
            <li class="nav-item">
                <input type="checkbox" id="submenuCargos" class="submenu-toggle">
                <label for="submenuCargos" class="submenu-label">
                    <i class="bi bi-briefcase"></i>
                    <span>Cargos</span>
                    <i class="bi bi-chevron-down dropdown-arrow"></i>
                </label>
                <ul class="submenu">
                    <li><a href="#"><i class="bi bi-plus-circle"></i> Nuevo Cargo</a></li>
                    <li><a href="#"><i class="bi bi-list-ul"></i> Listar Cargos</a></li>
                </ul>
            </li>
            
            <!-- Reportes con submenu -->
            <li class="nav-item">
                <input type="checkbox" id="submenuReportes" class="submenu-toggle">
                <label for="submenuReportes" class="submenu-label">
                    <i class="bi bi-graph-up"></i>
                    <span>Reportes</span>
                    <i class="bi bi-chevron-down dropdown-arrow"></i>
                </label>
                <ul class="submenu">
                    <li><a href="#"><i class="bi bi-file-pdf"></i> Reporte PDF</a></li>
                    <li><a href="#"><i class="bi bi-file-excel"></i> Reporte Excel</a></li>
                </ul>
            </li>
            
            <!-- Configuración con submenu -->
            <li class="nav-item">
                <input type="checkbox" id="submenuConfig" class="submenu-toggle">
                <label for="submenuConfig" class="submenu-label">
                    <i class="bi bi-gear-wide"></i>
                    <span>Configuración</span>
                    <i class="bi bi-chevron-down dropdown-arrow"></i>
                </label>
            </li>
        </ul>
    </div>

    <!-- Contenido principal -->
    <div class="main-content">
        <div class="container">
            <div class="card shadow-lg p-5 border-0">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                    <h1 class="display-5 fw-bold text-primary mt-3">Bienvenido al Home</h1>
                    <p class="lead text-secondary">Has llegado al panel principal</p>
                    
                    <div class="alert alert-info mt-4">
                        <i class="bi bi-info-circle"></i> Has iniciado sesión como: 
                        <strong>{{ session('usuario') }}</strong> | 
                        Departamento: <strong>{{ session('departamento') }}</strong> | 
                        Cargo: <strong>{{ session('cargo') }}</strong>
                    </div>
                    
                    <hr>
                    <p class="text-muted">Sistema funcionando correctamente</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleMenu() {
            document.getElementById('sidebarMenu').classList.toggle('open');
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body> 
</html>