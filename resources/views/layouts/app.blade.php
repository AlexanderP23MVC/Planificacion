
@php
    use App\Http\Controllers\MenuController;
    use App\Models\DataBase;
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema Seguro')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    

    <body>
               
    <!-- Navbar superior -->
    <nav class="navbar navbar-expand-lg navbar-dark shadow">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url('/home') }}">
                <i class="bi bi-shield-lock-fill"></i> Sistema Seguro
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/home') }}">
                            <i class="bi bi-house-fill"></i> Home
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-grid"></i> Módulos
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ url('/usuarios') }}"><i class="bi bi-people"></i> Usuarios</a></li>
                            <li><a class="dropdown-item" href="{{ url('/departamentos') }}"><i class="bi bi-diagram-3"></i> Departamentos</a></li>
                            <li><a class="dropdown-item" href="{{ url('/cargos') }}"><i class="bi bi-briefcase"></i> Cargos</a></li>
                        </ul>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <!-- Notificaciones -->
                    <li class="nav-item notifications-dropdown">
                        <input type="checkbox" id="notificationsToggle" class="notifications-toggle">
                        <label for="notificationsToggle" class="notifications-label">
                            <span class="position-relative">
                                <i class="bi bi-bell-fill" style="font-size: 1.2rem;"></i>
                                <span class="badge-notif-count">3</span>
                            </span>
                        </label>
                        <div class="notifications-menu">
                            <div class="notifications-header">
                                <i class="bi bi-bell-fill"></i> Notificaciones
                            </div>
                            <ul class="notifications-list">
                                <li><a href="#"><i class="bi bi-envelope-fill text-primary"></i><div><div class="notification-title">Nuevo mensaje</div><div class="notification-time">Hace 5 min</div></div></a></li>
                                <li><a href="#"><i class="bi bi-person-plus-fill text-success"></i><div><div class="notification-title">Nuevo usuario</div><div class="notification-time">Hace 1 hora</div></div></a></li>
                            </ul>
                            <div class="notifications-footer">
                                <a href="#">Ver todas</a>
                            </div>
                        </div>
                    </li>
                    
                    <!-- Menú usuario -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ session('usuario', 'Usuario') }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-person"></i> Mi Perfil</a></li>
                            <li><hr class="dropdown-divider"></li>
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

    <!-- Menú lateral izquierdo -->
    <button class="menu-toggle" onclick="toggleMenu()">
        <i class="bi bi-list"></i>
    </button>

    <div class="sidebar-left" id="sidebarMenu">
        <div class="sidebar-header">
            <i class="bi bi-grid-3x3-gap-fill" style="font-size: 2rem; color: #4a6fa5;"></i>
            <h4>Menú de Accesos</h4>
            <p>{{ session('cargo', 'Usuario') }} | {{ session('departamento', 'General') }}</p>
        </div>
        
@php
    $menus = MenuController::getMenu();
    
    // Organizar por menú y submenú
    $menuOrganizado = [];
    foreach ($menus as $item) {
        $menuOrganizado[$item->menu]['submenus'][$item->id_sub_menu] = $item->sub_menu;
    }
@endphp

<!-- Menús dinámicos desde base de datos -->
@foreach($menuOrganizado as $nombreMenu => $menuData)
    <li class="nav-item">
        <input type="checkbox" id="menu{{ $loop->index }}" class="submenu-toggle">
        <label for="menu{{ $loop->index }}" class="submenu-label">
            <i class="bi bi-folder"></i>
            <span>{{ $nombreMenu }}</span>
            <i class="bi bi-chevron-down dropdown-arrow"></i>
        </label>
        <ul class="submenu">
            @foreach($menuData['submenus'] as $id_sub_menu => $nombreSub)
                @php
                    $actividadesSubmenu = DataBase::getActividadesBySubMenu($id_sub_menu);
                    $tipoActividad = $actividadesSubmenu->isNotEmpty() ? $actividadesSubmenu->first()->tipo_actividad : 'Actividad Fija';
                @endphp
                
                <li>
                    @if($tipoActividad == 'Actividad Especifica')
                        <a href="{{ route('actividad.especifica', ['id_sub_menu' => $id_sub_menu, 'nombre' => $nombreSub]) }}">
                    {{ str_replace('_', ' ', $nombreSub) }}
                    
                </a>
                    @else
                        <a href="{{ route('actividades.submenu', ['submenu_nombre' => $nombreSub, 'id_sub_menu' => $id_sub_menu]) }}">
                        {{ str_replace('_', ' ', $nombreSub) }}
                        </a>
                    @endif
                </li>
            @endforeach
        </ul>
    </li>
@endforeach
</ul>
    </div>

    <!-- Contenido principal -->
    <div class="main-content">
        @yield('content')
    </div>

    <script>
        function toggleMenu() {
            document.getElementById('sidebarMenu').classList.toggle('open');
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>