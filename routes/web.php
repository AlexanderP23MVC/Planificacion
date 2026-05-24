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

Route::get('/{submenu_nombre}/{id_sub_menu}', [HomeController::class, 'actividades'])->name('actividades.submenu');

Route::get('/actividad-especifica/{id_sub_menu}/{nombre}', [HomeController::class, 'actividadEspecifica'])->name('actividad.especifica');

// Ruta para la API (carga dinámica)
Route::get('/api/localidades/{id_padre}', [HomeController::class, 'getLocalidades'])->name('api.localidades');

// Ruta para mostrar el formulario
Route::get('/actividad-especifica/{id_sub_menu}/{nombre}', [HomeController::class, 'actividadEspecifica'])->name('actividad.especifica');

Route::get('/evaluacionActividad', [HomeController::class, 'verAprobacionActividades'])->name('evaluacionActividad');


Route::post('/aprobar-actividad', [HomeController::class, 'aprobarActividad'])->name('aprobar.actividad');

Route::post('/rechazar-actividad', [HomeController::class, 'rechazarActividad'])->name('rechazar.actividad');

Route::post('/guardar-actividad-especifica', [HomeController::class, 'guardar'])->name('guardar.actividad.especifica');

Route::post('/guardar-actividad-especifica', [HomeController::class, 'guardar'])->name('guardar.actividad.especifica');

Route::post('/guardar-registros', [RegistroController::class, 'guardar'])->name('guardar.registros');

//siempre ald final de las rutas
Route::fallback(function () {
    // Verificar si hay sesión activa
    if (session('id_usuario')) {
        return redirect('/home')->with('error', 'La página que buscas no existe');
    }
    return redirect('/')->with('error', 'La página que buscas no existe');
});
