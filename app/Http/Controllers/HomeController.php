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

    // Obtener el ID de Venezuela (ajusta según tu BD)
    $id_venezuela = 1; // ID de Venezuela en tu tabla localidad
    
    // Cargar estados de Venezuela (hijos de Venezuela)
    $estados = DataBase::localidad($id_venezuela);
    
    return view('sections.actividadEspecifica', [
        'actividades' => $actividades,
        'submenu_nombre' => $nombre,
        'id_sub_menu' => $id_sub_menu,
        'estados' => $estados
    ]);
}

// Método API para carga dinámica (retorna JSON)
public function getLocalidades($id_padre)
{
    $localidades = DataBase::localidad($id_padre);
    return response()->json($localidades);
}



    public function guardar(Request $request)
{
    if (!session('id_usuario')) {
        return redirect('/')->with('error', 'Debes iniciar sesión primero');
    }
    
    $actividades = $request->actividades;
    $id_usuario = session('id_usuario');
    
    foreach ($actividades as $id_actividad => $data) {
        // Llamar al método del modelo DataBase
        DataBase::guardarActividad($id_usuario, $id_actividad, $data);
    }
    
    return redirect()->back()->with('success', 'Registro guardado correctamente');
}



public function verAprobacionActividades()
{
    if (!session('id_usuario')) {
        return redirect('/')->with('error', 'Debes iniciar sesión primero');
    }
    
    $id_departamento = session('id_departamento');
    $actividades = DataBase::getAprobacionActividades($id_departamento);
    
    return view('sections.aprobacion', [
        'actividades' => $actividades,
        'id_departamento' => $id_departamento,
        
    ]);
}

public function aprobarActividad(Request $request)
{
        if (!session('id_usuario')) {
        return redirect('/')->with('error', 'Debes iniciar sesión primero');
    }

    $id_ref_unica = $request->id_ref_unica;
    $id_act_central = $request->id_act_central;
    $accion = $request->accion;
    DataBase::actualizarEstatus($id_ref_unica, $accion);
    return redirect()->route('evaluacionActividad')->with('success', 'Actividad aprobada correctamente');
}



public function rechazarActividad(Request $request)
{

    if (!session('id_usuario')) {
        return redirect('/')->with('error', 'Debes iniciar sesión primero');
    }

    $id_ref_unica = $request->id_ref_unica;
    $id_act_central = $request->id_act_central;
    $accion = $request->accion;
    DataBase::actualizarEstatus($id_ref_unica, $accion);
    return redirect()->route('evaluacionActividad')->with('error', 'Actividad rechazada');
}

}
