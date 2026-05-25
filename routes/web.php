<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', [LoginController::class, 'index']);

// Ruta para procesar el login
Route::post('/login', [LoginController::class, 'login'])->name('login');



// Ruta para cerrar sesión
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/home', [HomeController::class, 'home']);


// Rutas para notificaciones
Route::get('/notificaciones/obtener', [HomeController::class, 'obtener'])->name('notificaciones.obtener');
Route::post('/notificaciones/marcar-leida', [HomeController::class, 'marcarComoLeida'])->name('notificaciones.marcar.leida');
Route::post('/notificaciones/marcar-todas-leidas', [HomeController::class, 'marcarTodasComoLeidas'])->name('notificaciones.marcar.todas.leidas');
Route::get('/notificaciones', [HomeController::class, 'verTodas'])->name('notificaciones.ver.todas');
Route::get('/notificaciones/contador', [HomeController::class, 'obtenerContador'])->name('notificaciones.contador');



Route::post('/aprobar-actividad', [HomeController::class, 'aprobarActividad'])->name('aprobar.actividad');

Route::post('/acertificar-actividad', [HomeController::class, 'certificarActividad'])->name('certificar.actividad');

Route::post('/rechazar-actividad', [HomeController::class, 'rechazarActividad'])->name('rechazar.actividad');

Route::post('/guardar-actividad-especifica', [HomeController::class, 'guardar'])->name('guardar.actividad.especifica');

Route::post('/guardar-actividad-especifica', [HomeController::class, 'guardar'])->name('guardar.actividad.especifica');

Route::post('/guardar-registros', [RegistroController::class, 'guardar'])->name('guardar.registros');


// Rutas de planificiacion
Route::get('planificacion/{submenu_nombre}/{id_sub_menu}', [HomeController::class, 'planificacionActividad'])->name('planificacion.actividad');



// Rutas dinamicas deben estar de ultima
Route::get('/actividad-especifica-actividad/{id_actividad}/{nombre}', [HomeController::class, 'actividadEspecificaById'])->name('actividad.especifica.actividad');
Route::get('/{submenu_nombre}/{id_sub_menu}', [HomeController::class, 'actividades'])->name('actividades.submenu');

Route::get('/actividad-especifica/{id_sub_menu}/{nombre}', [HomeController::class, 'actividadEspecifica'])->name('actividad.especifica');

Route::get('/api/localidades/{id_padre}', [HomeController::class, 'getLocalidades'])->name('api.localidades');

Route::get('/actividad-especifica/{id_sub_menu}/{nombre}', [HomeController::class, 'actividadEspecifica'])->name('actividad.especifica');

Route::get('/evaluacionActividad', [HomeController::class, 'verAprobacionActividades'])->name('evaluacionActividad');






//siempre ald final de las rutas
Route::fallback(function () {
    // Verificar si hay sesión activa
    if (session('id_usuario')) {
        return redirect('/home')->with('error', 'La página que buscas no existe');
    }
    return redirect('/')->with('error', 'La página que buscas no existe');
});
