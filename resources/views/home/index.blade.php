<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido | Sistema de Gestión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
      @vite(['resources/css/app.css'])
</head>
<body>
    <div class="welcome-section">
        <div class="container text-center">
            <div class="card shadow-lg p-5 border-0">
                <div class="card-body">
                    <h1 class="display-4 fw-bold text-primary mb-3">
                        <i class="bi bi-shield-lock-fill"></i> Login
                    </h1>
                    <p class="lead text-muted mb-4">Sistema de TSJ</p>
                    
                    <!-- FORMULARIO DE LOGIN -->
                    <div class="login-form">
                        <form id="loginForm">
                            <div class="mb-3">
                                <label for="username" class="form-label fw-semibold">
                                    <i class="bi bi-person"></i> Usuario
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person-circle"></i></span>
                                    <input type="text" class="form-control" id="username" placeholder="Ej: admin, usuario1" required>
                                </div>
                                <div class="invalid-feedback">Por favor ingrese un usuario válido</div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">
                                    <i class="bi bi-key"></i> Contraseña
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" class="form-control" id="password" placeholder="••••••" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2 mt-3">
                                <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                            </button>
                        </form>
                        <div id="errorAlert" class="alert alert-danger mt-3 d-none" role="alert"></div>
                        <div id="successAlert" class="alert alert-success mt-3 d-none" role="alert"></div>
                    </div>

                    <hr class="my-4">

                </div>

            </div>
        </div>
    </div>

</body>
</html>