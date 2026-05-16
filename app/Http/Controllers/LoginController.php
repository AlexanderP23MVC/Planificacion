<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataBase;

class LoginController extends Controller
{

    public function index()
    {
        return view('login.index'); 
    }

    public function login(Request $request)
    {
    
        $usuario = $request->usuario;
        $password = $request->password;
        
        $user = DataBase::login($usuario, $password);
        
        // Validar credenciales
        if ($user && $user->password === $password) {
            // Autenticación exitosa - solo redirige sin guardar sesión
            
            
        session([
            'id_usuario' => $user->id_usuario,
            'usuario' => $user->usuario,
            'departamento' => $user->departamento,
            'id_departamento' => $user->id_departamento, 
            'cargo' => $user->cargos,
            'sesiones' => $user->sesiones
            
        ]);

            return redirect('/home');
        } else {
            // Autenticación fallida
            return view('login.index', ['error' => 'Usuario o contraseña incorrectos']);
        }
    
    }


    public function logout(Request $request)
    {
            // Eliminar toda la sesión
            session()->flush();
            
            // Redirigir al login
            return redirect('/');
    }
    
}
