<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido | Sistema de Gestión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .welcome-section {
            min-height: 100vh; /* Cambiado a min-height por si la lista es larga */
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            padding: 20px 0;
        }
        .user-list {
            text-align: left;
            max-width: 400px;
            margin: 20px auto;
        }
    </style>
</head>
<body>

    <div class="welcome-section">
        <div class="container text-center">
            <div class="card shadow-lg p-5 border-0">
                <div class="card-body">
                    <h1 class="display-4 fw-bold text-primary mb-3">¡Bienvenido al Sistema!</h1>
                    <p class="lead text-muted mb-4">
                        La conexión con **PostgreSQL** se ha establecido correctamente. 
                        Estás listo para gestionar tus datos de manera segura.
                    </p>
                    
                    <hr class="my-4">

                    <div class="user-list">
                        <h5 class="text-secondary mb-3 text-center">Usuarios Registrados:</h5>
                        <ul class="list-group shadow-sm">
                            @forelse($usuarios as $user)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>
                                        <i class="bi bi-person-circle me-2"></i>
                                        <strong>Usuario:</strong> {{ $user->user_name }}
                                    </span>
                                    <span class="badge bg-info rounded-pill">ID: {{ $user->id_user }}</span>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted">No hay usuarios registrados.</li>
                            @endforelse
                        </ul>
                    </div>

                    <div class="d-grid gap-2 d-sm-flex justify-content-sm-center mt-4">
                        <a href="#" class="btn btn-primary btn-lg px-4 gap-3">Ver Inventario</a>
                        <a href="#" class="btn btn-outline-secondary btn-lg px-4">Configuración</a>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 text-muted mt-3">
                    <small>Usuario conectado con permisos restringidos (ISO 27001)</small>
                </div>
            </div>
        </div>
    </div>

</body>
</html>