<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
class HomeController extends Controller
{
        public function home()
    {
        if (!session('id_usuario')) {
            
            return redirect('/')->with('error', 'Debes iniciar sesión primero');
        }

        $id_usuario = session('id_usuario');
        $usuario = session('usuario');
        $departamento = session('departamento');
        $cargo = session('cargo');
        $sesiones = session('sesiones');
        
         return view('home.home');
    }

}
