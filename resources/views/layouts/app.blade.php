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
                    @if(session('cargo') == 'Gerente')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-grid"></i> Módulos
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('evaluacionActividad') }}">
                                    <i class="bi bi-people"></i> Aprobacion
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif
                </ul>

                <ul class="navbar-nav">
                    <!-- Notificaciones - Visible para TODOS -->
                    <li class="nav-item notifications-dropdown">
                        <input type="checkbox" id="notificationsToggle" class="notifications-toggle">
                        <label for="notificationsToggle" class="notifications-label">
                            <span class="position-relative">
                                <i class="bi bi-bell-fill" style="font-size: 1.2rem;"></i>
                                <span class="badge-notif-count" id="notificacionesCount">0</span>
                            </span>
                        </label>
                        <div class="notifications-menu">
                            <div class="notifications-header">
                                <i class="bi bi-bell-fill"></i> Notificaciones
                                <button type="button" class="btn btn-sm btn-link float-end"
                                    onclick="marcarTodasComoLeidas()" style="font-size: 0.75rem;">Marcar todas</button>
                            </div>
                            <ul class="notifications-list" id="notificacionesList">
                                <li class="text-center text-muted py-3">
                                    <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                                    Cargando...
                                </li>
                            </ul>
                            <div class="notifications-footer">
                                <a href="{{ route('notificaciones.ver.todas') }}">Ver todas</a>
                            </div>
                        </div>
                    </li>

                    <!-- Menú usuario -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ session('usuario', 'Usuario') }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('notificaciones.ver.todas') }}"><i
                                        class="bi bi-bell"></i> Mis Notificaciones</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
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
        // Menús normales (con sub_menu)
        $menus = MenuController::getMenu();
        $menuOrganizado = [];
        foreach ($menus as $item) {
        $menuOrganizado[$item->menu]['submenus'][$item->id_sub_menu] = $item->sub_menu;
        }

        // Menús de Actividades Específicas
        $actividadesEspecificas = MenuController::getMenuActividadesEspecificas();
        $menuEspecifico = [];
        foreach ($actividadesEspecificas as $item) {
        $menuEspecifico[$item->menu]['submenus'][$item->id_actividades] = $item->sub_menu;
        }
        @endphp

        <ul class="nav-menu">
            <!-- Dashboard fijo -->
            <li class="nav-item">
                <a href="{{ url('/home') }}" class="nav-link-custom">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Menús dinámicos desde sub_menu -->
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
                    $tipoActividad = $actividadesSubmenu->isNotEmpty() ? $actividadesSubmenu->first()->tipo_actividad :
                    'Actividad Fija';
                    @endphp
                    <li>
                        @if($tipoActividad == 'Actividad Especifica')
                        <a
                            href="{{ route('actividad.especifica', ['id_sub_menu' => $id_sub_menu, 'nombre' => $nombreSub]) }}">
                            {{ str_replace('_', ' ', $nombreSub) }}
                        </a>
                        @else
                        <a
                            href="{{ route('actividades.submenu', ['submenu_nombre' => $nombreSub, 'id_sub_menu' => $id_sub_menu]) }}">
                            {{ str_replace('_', ' ', $nombreSub) }}
                        </a>
                        @endif
                    </li>
                    @endforeach
                </ul>
            </li>
            @endforeach

            <!-- Menús de Actividades Específicas (desde tabla actividades) -->
            @foreach($menuEspecifico as $nombreMenu => $menuData)
            <li class="nav-item">
                <input type="checkbox" id="menuEspecifico{{ $loop->index }}" class="submenu-toggle">
                <label for="menuEspecifico{{ $loop->index }}" class="submenu-label">
                    <i class="bi bi-star-fill"></i>
                    <span>{{ $nombreMenu }}</span>
                    <i class="bi bi-chevron-down dropdown-arrow"></i>
                </label>
                <ul class="submenu">
                    @foreach($menuData['submenus'] as $id_actividad => $nombreSub)
                    <li>
                        <a href="{{ route('actividad.especifica.actividad', ['id_actividad' => $id_actividad, 'nombre' => $nombreSub]) }}">
                            {{ str_replace('_', ' ', $nombreSub) }}
                        </a>
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
<script>
    // Función para obtener el tiempo relativo
    function tiempoRelativo(fecha) {
        const ahora = new Date();
        const notifFecha = new Date(fecha);
        const diffMs = ahora - notifFecha;
        const diffMin = Math.floor(diffMs / 60000);
        const diffHoras = Math.floor(diffMin / 60);
        const diffDias = Math.floor(diffHoras / 24);

        if (diffMin < 1) return 'Hace unos segundos';
        if (diffMin < 60) return `Hace ${diffMin} min`;
        if (diffHoras < 24) return `Hace ${diffHoras} horas`;
        return `Hace ${diffDias} días`;
    }

    // Cargar notificaciones
    function cargarNotificaciones() {
        console.log('Cargando notificaciones...');

        fetch('{{ route("notificaciones.obtener") }}')
            .then(response => response.json())
            .then(data => {
                console.log('Notificaciones recibidas:', data);
                const count = data.length;
                const notificacionesList = document.getElementById('notificacionesList');
                const countBadge = document.getElementById('notificacionesCount');

                // Actualizar contador
                if (countBadge) countBadge.textContent = count;

                if (!notificacionesList) return;

                if (count === 0) {
                    notificacionesList.innerHTML = '<li class="text-center text-muted py-3"><i class="bi bi-inbox"></i> No hay notificaciones</li>';
                    return;
                }

                let html = '';
                data.forEach(notif => {
                    const tiempo = tiempoRelativo(notif.fecha_notificacion);
                    html += `
                        <li>
                            <a href="#" onclick="marcarComoLeida(${notif.id_notificacion})">
                                <i class="bi bi-bell-fill text-warning"></i>
                                <div>
                                    <div class="notification-title">Actividad pendiente de revisión</div>
                                    <div class="notification-time">${tiempo}</div>
                                </div>
                                <span class="badge bg-danger ms-2">Nueva</span>
                            </a>
                        </li>
                    `;
                });

                notificacionesList.innerHTML = html;
            })
            .catch(error => {
                console.error('Error al cargar notificaciones:', error);
                const notificacionesList = document.getElementById('notificacionesList');
                if (notificacionesList) {
                    notificacionesList.innerHTML = '<li class="text-center text-danger py-3">Error al cargar notificaciones</li>';
                }
            });
    }

    // Marcar una notificación como leída
    function marcarComoLeida(id) {
        fetch('{{ route("notificaciones.marcar.leida") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ id_notificacion: id })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    cargarNotificaciones();
                    actualizarContador();
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Marcar todas como leídas
    function marcarTodasComoLeidas() {
        fetch('{{ route("notificaciones.marcar.todas.leidas") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    cargarNotificaciones();
                    actualizarContador();
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Actualizar contador
    function actualizarContador() {
        fetch('{{ route("notificaciones.contador") }}')
            .then(response => response.json())
            .then(data => {
                const countBadge = document.getElementById('notificacionesCount');
                if (countBadge) countBadge.textContent = data.count;
            })
            .catch(error => console.error('Error:', error));
    }

    // Inicializar cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', function () {
        console.log('DOM cargado, iniciando notificaciones...');
        cargarNotificaciones();
        // Actualizar cada 30 segundos
        setInterval(cargarNotificaciones, 30000);
    });
</script>

</html>