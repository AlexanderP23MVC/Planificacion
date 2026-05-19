<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataBase;
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


public function actividades($submenu_nombre, $id_sub_menu)
{
    if (!session('id_usuario')) {
        return redirect('/')->with('error', 'Debes iniciar sesión primero');
    }
    
    $actividades = DataBase::getActividadesBySubMenu($id_sub_menu);
    return view('sections.actividades', [
        'actividades' => $actividades,
        'submenu_nombre' => $submenu_nombre,
        'id_sub_menu' => $id_sub_menu
    ]);
}

public function actividadEspecifica($id_sub_menu, $nombre)
    {
        if (!session('id_usuario')) {
            return redirect('/')->with('error', 'Debes iniciar sesión primero');
        }
        
        $actividades = DataBase::getActividadesBySubMenu($id_sub_menu);
        
        return view('sections.actividadEspecifica', [
            'actividades' => $actividades,
            'submenu_nombre' => $nombre,
            'id_sub_menu' => $id_sub_menu
        ]);
    }

}
