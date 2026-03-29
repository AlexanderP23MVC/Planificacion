<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
class HomeController extends Controller
{
    public function index()
    {
        // Solo retornamos la vista que está en resources/views/home/index.blade.php
        // No necesitamos consultar la DB por ahora
        $usuarios = Usuario::all();
        return view('home.index',compact('usuarios'));
    }
}
