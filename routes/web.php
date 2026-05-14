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

// Route::middleware(['auth'])->group(function () {
//     Route::get('/home', [HomeController::class, 'index']);
//     Route::get('/dashboard', [DashboardController::class, 'index']);
//     // Más rutas protegidas aquí
// });
