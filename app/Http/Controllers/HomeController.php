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

public function actividadEspecificaById($id_actividad, $nombre)
{
    if (!session('id_usuario')) {
        return redirect('/')->with('error', 'Debes iniciar sesión primero');
    }
    
    // Obtener la actividad específica
    $actividad = DataBase::getById($id_actividad);
    
    // Obtener los estados para el select de ubicación
    $id_venezuela = 1; // ID de Venezuela
    $estados = DataBase::localidad($id_venezuela);
    
    // Crear un array de actividades para el foreach de la vista
    $actividades = collect([$actividad]);
    
    return view('sections.actividadEspecifica', [
        'actividades' => $actividades,
        'submenu_nombre' => $nombre,
        'id_sub_menu' => null,  // Para actividades específicas no hay sub_menu
        'estados' => $estados
    ]);
}

public static function getMenuActividadesEspecificas()
{
    return DB::table('actividades AS act')
        ->join('tipo_actividad AS tp_act', 'tp_act.id_tipo_actividad', '=', 'act.id_tipo_actividad')
        ->where('tp_act.actividad', 'Actividad Especifica')
        ->select(
            'tp_act.actividad as menu',
            'act.actividades as sub_menu',
            'act.id_actividades'
        )
        ->get();
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
    
    // Actualizar estatus en la tabla revision
    DataBase::actualizarEstatus($id_ref_unica, $accion);
    
    // Crear notificación para el departamento
    DataBase::actividadesRechazadas($id_ref_unica, $id_act_central);
    
    return redirect()->route('evaluacionActividad')->with('error', 'Actividad rechazada. Se ha notificado al departamento.');
}


 // Obtener notificaciones (AJAX)
    public function obtener()
{
    if (!session('id_usuario')) {
        return response()->json(['error' => 'No autorizado'], 401);
    }
    
    $id_departamento = session('id_departamento');
    $cargo = session('cargo');
    
    $notificaciones = DataBase::getNotificaciones($id_departamento, $cargo);
    
    return response()->json($notificaciones);
}
    
    // Obtener contador
    public function obtenerContador()
    {
        if (!session('id_usuario')) {
            return response()->json(['count' => 0]);
        }
        
        $id_departamento = session('id_departamento');
        $cargo = session('cargo');
        
        $count = DataBase::contarNotificacionesNoLeidas($id_departamento, $cargo);
        
        return response()->json(['count' => $count]);
    }
    
    // Marcar notificación como leída (ahora no necesario, pero lo mantenemos)
    public function marcarComoLeida(Request $request)
    {
        // Aquí podrías actualizar una columna "leido" en revision si la agregas
        return response()->json(['success' => true]);
    }
    
    // Marcar todas como leídas
    public function marcarTodasComoLeidas(Request $request)
    {
        return response()->json(['success' => true]);
    }
    
    // Ver todas las notificaciones
    public function verTodas()
    {
        if (!session('id_usuario')) {
            return redirect('/')->with('error', 'Debes iniciar sesión primero');
        }
        
        $id_departamento = session('id_departamento');
        $cargo = session('cargo');
        
        $notificaciones = DataBase::getNotificaciones($id_departamento, $cargo);
        
        return view('sections.notificaciones', [
            'notificaciones' => $notificaciones
        ]);
    }

}
