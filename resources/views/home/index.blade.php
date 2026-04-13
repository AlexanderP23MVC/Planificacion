<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema Seguro</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="welcome-section">
        <div class="container text-center">
            <div class="card shadow-lg p-5 border-0" data-aos="fade-up" data-aos-duration="1000">
                <div class="card-body">
                    <h1 class="display-4 fw-bold text-primary mb-3" data-aos="zoom-in" data-aos-delay="200">
                        <i class="bi bi-shield-lock-fill"></i> Login
                    </h1>
                    
                    <div class="login-form" data-aos="fade-right" data-aos-delay="400">
                        <form id="loginForm">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-person"></i> Usuario
                                </label>
                                <input type="text" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-key"></i> Contraseña
                                </label>
                                <input type="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2 mt-3">
                                <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</body>
</html>