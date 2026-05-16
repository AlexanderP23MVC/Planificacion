<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;

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
        
        // Buscar el usuario en la base de datos
        $user = Usuario::join('departamento AS dp', 'dp.id_departamento', '=', 'usuario.id_departamento')
        ->join('cargos AS cg', 'cg.id_cargos', '=', 'usuario.id_cargos')
        ->join('estatus_sesion As es', 'es.id_estatus_sesion', '=', 'usuario.id_estatus_sesion')
        ->where('usuario.usuario', $usuario)
        ->select('usuario.id_usuario', 'usuario.usuario', 'usuario.password', 'dp.departamento', 'cg.cargos', 'es.sesiones')
        ->first();
        
        // Validar credenciales
        if ($user && $user->password === $password) {
            // Autenticación exitosa - solo redirige sin guardar sesión
            
            session([
            'id_usuario' => $user->id_usuario,
            'usuario' => $user->usuario,
            'departamento' => $user->departamento,
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
