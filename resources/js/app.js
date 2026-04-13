import './bootstrap';

AOS.init({
    duration: 800,
    once: true
});

// Animación al hacer submit
document.getElementById('loginForm')?.addEventListener('submit', (e) => {
    e.preventDefault();
    
    const btn = e.target.querySelector('button[type="submit"]');
    
    // Animación de carga
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Validando...';
    btn.disabled = true;
    
    // Simular validación
    setTimeout(() => {
        btn.innerHTML = '<i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión';
        btn.disabled = false;
    }, 2000);
});