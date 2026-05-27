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
    
    $cargo = session('cargo');
    $departamento_nombre = session('departamento', '');
    $id_departamento = session('id_departamento');
    
    // Validar acceso: solo Gerente o Planificación
    $esGerente = ($cargo == 'Gerente');
    $esPlanificacion = (strtolower($departamento_nombre) == 'planificacion');
    
    if (!$esGerente && !$esPlanificacion) {
        return redirect('/home')->with('error', 'No tienes permiso para acceder a esta sección');
    }
    
    // Usar paginación (15 registros por página)
    $actividades = DataBase::getAprobacionActividades($id_departamento, $cargo, $departamento_nombre)
        ->paginate(15);
    
    return view('sections.aprobacion', [
        'actividades' => $actividades,
        'id_departamento' => $id_departamento
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

public function certificarActividad(Request $request)
{
    if (!session('id_usuario')) {
        return redirect('/')->with('error', 'Debes iniciar sesión primero');
    }
    
    $id_ref_unica = $request->id_ref_unica;
    $accion = $request->accion; // CERTIFICADO
    
    // Actualizar estatus a CERTIFICADO (id_estatus = 5)
    DataBase::actualizarEstatus($id_ref_unica, $accion);
    
    return redirect()->back()->with('success', 'Actividad certificada correctamente');
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


// Obtener contador de notificaciones
public function obtenerContador()
{
    if (!session('id_usuario')) {
        return response()->json(['count' => 0]);
    }
    
    $cargo = session('cargo');
    $departamento_nombre = session('departamento', '');
    $id_departamento = session('id_departamento');
    
    $count = DataBase::contarNotificacionesNoLeidas($id_departamento, $cargo, $departamento_nombre);
    
    return response()->json(['count' => $count]);
}

// Obtener notificaciones (AJAX)
public function obtener()
{
    if (!session('id_usuario')) {
        return response()->json(['error' => 'No autorizado'], 401);
    }
    
    $id_departamento = session('id_departamento');
    $cargo = session('cargo');
    $departamento_nombre = session('departamento', '');
    
    $notificaciones = DataBase::getNotificaciones($id_departamento, $cargo, $departamento_nombre);
    
    return response()->json($notificaciones);
}

// Marcar una notificación como leída (ahora solo es un placeholder)
public function marcarComoLeida(Request $request)
{
    // No es necesario actualizar nada porque no hay columna "leido"
    return response()->json(['success' => true]);
}

// Marcar todas como leídas (placeholder)
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
    $departamento_nombre = session('departamento', '');
    
    $notificaciones = DataBase::getNotificaciones($id_departamento, $cargo, $departamento_nombre);
    
    return view('sections.notificaciones', [
        'notificaciones' => $notificaciones
    ]);
}


    //controller para planificacion

public function planificacionActividad($submenu_nombre, $id_sub_menu)
{
    if (!session('id_usuario')) {
        return redirect('/')->with('error', 'Debes iniciar sesión primero');
    }
    
    $actividades = DataBase::getActividadesBySubMenu($id_sub_menu);


    // Determinar qué vista mostrar según el submenu_nombre
    switch($submenu_nombre) {
        case 'graficos':
            $vista = 'sections.planificacion.graficos';
            break;
        case 'estadisticas':
            $vista = 'sections.planificacion.estadisticas';
            break;
        case 'reportes':
            $vista = 'sections.planificacion.reportes';
            break;
        default:
            $vista = 'home.home';
            break;
    }

    return view($vista, [
        'submenu_nombre' => $submenu_nombre,
        'id_sub_menu' => $id_sub_menu
    ]);
}

}
