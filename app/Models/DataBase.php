<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DataBase extends Model
{
    protected $table = 'usuario'; 
    public $timestamps = false;
    

     public static function login($usuario, $password)
    {
        // Buscar usuario con joins
        $user = self::join('departamento AS dp', 'dp.id_departamento', '=', 'usuario.id_departamento')
        ->join('cargos AS cg', 'cg.id_cargos', '=', 'usuario.id_cargos')
        ->join('estatus_sesion AS es', 'es.id_estatus_sesion', '=', 'usuario.id_estatus_sesion')
        ->where('usuario.usuario', $usuario)
        ->select(
            'usuario.id_usuario',
            'usuario.usuario',
            'usuario.password',
            'dp.departamento',
            'dp.id_departamento',      // ← Verifica que esté
            'cg.cargos',
            'es.sesiones'
        )
        ->first();
        
        // Validar credenciales
        if ($user && $user->password === $password) {

            return $user;
        }
        
        return null;
    }

    public static function menu($id_departamento)
            {
                return DB::table('departamento AS dp')
        ->join('menu AS me', 'me.id_departamento', '=', 'dp.id_departamento')
        ->join('sub_menu AS sub', 'sub.id_menu', '=', 'me.id_menu')
        ->join('unidad_sub_menu_actividades AS usa', 'usa.id_sub_menu', '=', 'sub.id_sub_menu')
        ->join('unidad_actividad AS ua', 'ua.id_unidad_actividad', '=', 'usa.id_unidad_actividad')
        ->join('actividades AS act', 'act.id_actividades', '=', 'usa.id_actividades')
        ->where('dp.id_departamento', $id_departamento)
        ->select(
            'dp.id_departamento',
            'dp.departamento',
            'me.id_menu',
            'me.menu',
            'sub.id_sub_menu',
            'sub.sub_menu',
            'ua.id_unidad_actividad',
            'ua.unidad',
            'act.id_actividades',
            'act.actividades'
        )
        ->get();
            }

        public static function getActividadesBySubMenu($id_sub_menu)
        {
            return DB::table('departamento AS dp')
                ->join('menu AS me', 'me.id_departamento', '=', 'dp.id_departamento')
                ->join('sub_menu AS sub', 'sub.id_menu', '=', 'me.id_menu')
                ->join('unidad_sub_menu_actividades AS usa', 'usa.id_sub_menu', '=', 'sub.id_sub_menu')
                ->join('unidad_actividad AS ua', 'ua.id_unidad_actividad', '=', 'usa.id_unidad_actividad')
                ->join('actividades AS act', 'act.id_actividades', '=', 'usa.id_actividades')
                ->join('tipo_actividad AS tp_act', 'tp_act.id_tipo_actividad', '=', 'act.id_tipo_actividad')
                ->where('sub.id_sub_menu', $id_sub_menu)
                ->select(
                    'dp.id_departamento',
                    'dp.departamento',
                    'me.id_menu',
                    'me.menu',
                    'sub.id_sub_menu',
                    'sub.sub_menu',
                    'ua.id_unidad_actividad',
                    'ua.unidad',
                    'act.id_actividades',
                    'act.actividades',
                    'tp_act.id_tipo_actividad',
                    'tp_act.actividad as tipo_actividad'
                )
                ->get();
        }

         public static function localidad($id_padre)
        {
            $query = DB::table('localidad');
            
            if ($id_padre === null) {
                $query->whereNull('id_padre');
            } else {
                $query->where('id_padre', $id_padre);
            }
            
            return $query->orderBy('localidad', 'asc')->get();
        }



        public static function crearReferenciaUnica()
{
    $id_ref_unica = DB::table('ref_unica')->insertGetId(
        ['ref_uuid' => Str::uuid()],
        'id_ref_unica'  
    );
    
    return $id_ref_unica;
}

// Obtener información del usuario con su estatus
public static function getInfoUsuario($id_usuario)
{
    $info = DB::table('usuario AS us')
        ->leftJoin('cargos AS cg', 'cg.id_cargos', '=', 'us.id_cargos')
        ->leftJoin('perfil_cargo AS pfc', 'cg.id_cargos', '=', 'pfc.id_cargos')
        ->leftJoin('tipo_perfil AS tpp', 'pfc.id_tipo_perfil', '=', 'tpp.id_tipo_perfil')
        ->leftJoin('perfiles_estatus AS pfe', 'pfe.id_tipo_perfil', '=', 'tpp.id_tipo_perfil')
        ->leftJoin('estatus AS es', 'es.id_estatus', '=', 'pfe.id_estatus')
        ->where('us.id_usuario', $id_usuario)
        ->select(
            'us.id_usuario',
            'us.usuario',
            'us.id_departamento',
            'cg.id_cargos',
            'cg.cargos',
            'tpp.id_tipo_perfil',
            'tpp.tipo_perfil',
            'es.id_estatus',
            'es.estatus'
        )
        ->first();
    
    return $info;
}

// Guardar en tabla revision
public static function guardarRevision($id_ref_unica, $id_estatus)
{
    $datosRevision = [
        'id_ref_unica' => $id_ref_unica,
        'id_estatus' => $id_estatus,
        'fecha_revision' => null,
        'observaciones' => null
    ];
    
    // Verificar si ya existe una revisión para esta referencia
    $existeRevision = DB::table('revision')
        ->where('id_ref_unica', $id_ref_unica)
        ->first();
    
    if ($existeRevision) {
        // Actualizar registro existente
        return DB::table('revision')
            ->where('id_revision', $existeRevision->id_revision)
            ->update($datosRevision);
    } else {
        // Insertar nuevo registro
        return DB::table('revision')->insert($datosRevision);
    }
    
}

// Guardar notificación
public static function guardarNotificacion($id_ref_unica, $id_usuario, $id_departamento, $id_tipo_perfil)
{
    $datosNotificacion = [
        'id_ref_unica' => $id_ref_unica,
        'id_tipo_perfil_destino' => $id_tipo_perfil,
        'id_departamento_destino' => $id_departamento,
        'id_usuario_emisor' => $id_usuario,
        'leido' => false,
        'fecha_notificacion' => date('Y-m-d')
    ];
    

    $existeNotificacion = DB::table('notificacion')
        ->where('id_ref_unica', $id_ref_unica)
        ->first();
    
    if ($existeNotificacion) {
        return DB::table('notificacion')
            ->where('id_notificacion', $existeNotificacion->id_notificacion)
            ->update($datosNotificacion);
    } else {
        return DB::table('notificacion')->insert($datosNotificacion);
    }
}


public static function guardarActividad($id_usuario, $id_actividad, $datos)
{

    $usuarioInfo = self::getInfoUsuario($id_usuario);
    

    $id_ref_unica = $datos['id_ref_unica'] ?? self::crearReferenciaUnica();
    

    $datosGuardar = [
        'id_usuario' => $id_usuario,
        'id_actividades' => $id_actividad,
        'id_ref_unica' => $id_ref_unica,
        'id_localidad' => $datos['id_localidad'] ?? null,
        'nombre_actividad' => $datos['nombre_actividad'] ?? null,
        'descripcion_actividad' => $datos['descripcion_actividad'] ?? null,
        'participante_atendido' => $datos['participante_atendido'] ?? 0,
        'cantidad_masculino' => $datos['cantidad_masculino'] ?? 0,
        'cantidad_femenino' => $datos['cantidad_femenino'] ?? 0,
        'duracion_hora' => $datos['duracion_hora'] ?? 0,
        'fecha_registro' => $datos['fecha_registro'] ?? date('Y-m-d'),
        'fecha_inicio' => $datos['fecha_inicio'] ?? null,
        'fecha_fin' => $datos['fecha_fin'] ?? null
    ];
    

    DB::table('act_central')->insert($datosGuardar);
    
    $id_estatus = $usuarioInfo->id_estatus ?? 1;  // Si no hay, usar 1 (pendiente)
    $id_departamento = $usuarioInfo->id_departamento ?? null;
    $id_tipo_perfil = $usuarioInfo->id_tipo_perfil ?? null;
    
    // Guardar en revision
    self::guardarRevision($id_ref_unica, $id_estatus);
    
    // Guardar en notificacion
    self::guardarNotificacion($id_ref_unica, $id_usuario, $id_departamento, $id_tipo_perfil);
    
    return $id_ref_unica;
}


// ACTIVIDAD ESPECIFICA
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

public static function getById($id_actividad)
{
    return DB::table('actividades')
        ->where('id_actividades', $id_actividad)
        ->first();
}

// ACTIVIDAD FIJA

public static function getAprobacionActividades($id_departamento, $cargo, $departamento_nombre = null)
{
    $esGerente = ($cargo == 'Gerente');
    $esPlanificacion = (strtolower($departamento_nombre) == 'planificacion');
    
    $query = DB::table('usuario AS us')
        ->join('departamento AS dp', 'us.id_departamento', '=', 'dp.id_departamento')
        ->join('act_central AS act_cen', 'act_cen.id_usuario', '=', 'us.id_usuario')
        ->join('actividades AS act', 'act.id_actividades', '=', 'act_cen.id_actividades')
        ->join('tipo_actividad AS tp_act', 'tp_act.id_tipo_actividad', '=', 'act.id_tipo_actividad')
        ->join('ref_unica AS ref', 'ref.id_ref_unica', '=', 'act_cen.id_ref_unica')
        ->join('revision AS rv', 'rv.id_ref_unica', '=', 'ref.id_ref_unica')
        ->join('estatus AS es', 'es.id_estatus', '=', 'rv.id_estatus')
        ->select(
            'rv.id_revision',
            'us.id_usuario',
            'us.usuario',
            'dp.id_departamento',
            'dp.departamento',
            'ref.id_ref_unica',
            'act_cen.nombre_actividad',
            'act_cen.descripcion_actividad',
            'act_cen.id_act_central',
            'act_cen.id_localidad',
            'act_cen.fecha_registro',
            'act_cen.duracion_hora',
            'act_cen.participante_atendido',
            'act_cen.cantidad_masculino',
            'act_cen.cantidad_femenino',
            'act_cen.fecha_inicio',
            'act_cen.fecha_fin',
            'rv.id_estatus',
            'es.id_estatus as estatus_id',
            'es.estatus'
        );
    
    if ($esGerente) {
        $query->where('dp.id_departamento', $id_departamento);
    } elseif ($esPlanificacion) {
        $query->where('es.estatus', 'APROBADO'); // Solo aprobados
    } else {
        $query->where('dp.id_departamento', 0); // No resultados
    }
    
    return $query->orderBy('act_cen.fecha_registro', 'desc');
}

    public static function actualizarEstatus($id_ref_unica, $accion)
        {
            
            $estatus = DB::table('estatus')
                ->where('estatus', $accion)
                ->first();            
            
            if (!$estatus) {
                return false;
            }            
            
            $id_estatus = $estatus->id_estatus;
                        
           return DB::table('revision')
                ->where('id_ref_unica', $id_ref_unica)
                ->update([
                    'id_estatus' => $id_estatus,
                    'id_usuario_revisor' => session('id_usuario'),
                    'fecha_revision' => now()->toDateString()
                ]);
        }

public static function actividadesRechazadas($id_ref_unica, $id_act_central)
{
    // Obtener información de la actividad para saber a qué departamento notificar
    $actividad = DB::table('act_central')
        ->join('usuario', 'act_central.id_usuario', '=', 'usuario.id_usuario')
        ->where('act_central.id_act_central', $id_act_central)
        ->select('usuario.id_departamento')
        ->first();
    
    // Crear notificación para el departamento (id_tipo_perfil_destino = 1)
    if ($actividad) {
        return self::crearNotificacionDepartamento(
            $id_ref_unica,
            1,  // id_tipo_perfil_destino = Departamento
            $actividad->id_departamento,
            session('id_usuario')
        );
    }
    
    return false;
}

public static function crearNotificacionDepartamento($id_ref_unica, $id_tipo_perfil_destino, $id_departamento_destino, $id_usuario_emisor)
{
    return DB::table('notificacion')->insert([
        'id_ref_unica' => $id_ref_unica,
        'id_tipo_perfil_destino' => $id_tipo_perfil_destino,
        'id_departamento_destino' => $id_departamento_destino,
        'id_usuario_emisor' => $id_usuario_emisor,
        'leido' => false,
        'fecha_notificacion' => now()->toDateString()
    ]);
}







// Obtener notificaciones según el cargo del usuario
public static function obtenerNotificacion()
{
    return DB::table('notificacion');
}

public static function getNotificaciones($id_departamento, $cargo, $departamento_nombre = null)
{
    $esGerente = ($cargo == 'Gerente');
    $esPlanificacion = (strtolower($departamento_nombre) == 'planificacion');
    
    $query = DB::table('revision AS rv')
        ->join('ref_unica AS ref', 'rv.id_ref_unica', '=', 'ref.id_ref_unica')
        ->join('act_central AS act', 'ref.id_ref_unica', '=', 'act.id_ref_unica')
        ->join('usuario AS us', 'act.id_usuario', '=', 'us.id_usuario')
        ->join('actividades AS actv', 'act.id_actividades', '=', 'actv.id_actividades')
        ->join('estatus AS es', 'rv.id_estatus', '=', 'es.id_estatus')
        ->select(
            'rv.id_revision as id_notificacion',
            'rv.id_ref_unica',
            'rv.id_estatus',
            'rv.fecha_revision as fecha_notificacion',
            'us.usuario',
            'us.id_departamento',
            'actv.actividades',
            'es.estatus'
        );
    
    if ($esGerente) {
        // Gerente: ve actividades PENDIENTES de su departamento
        $query->where('us.id_departamento', $id_departamento)
              ->where('rv.id_estatus', 1);
    } 
    elseif ($esPlanificacion) {
        // Planificación: ve actividades APROBADAS de todos los departamentos
        $query->where('rv.id_estatus', 2);
    } 
    else {
        return collect();
    }
    
    return $query->orderBy('rv.fecha_revision', 'desc')->get();
}

// Contar notificaciones no leídas
public static function contarNotificacionesNoLeidas($id_departamento, $cargo, $departamento_nombre = null)
{
    $esGerente = ($cargo == 'Gerente');
    $esPlanificacion = (strtolower($departamento_nombre) == 'planificacion');
    
    $query = DB::table('revision AS rv')
        ->join('ref_unica AS ref', 'rv.id_ref_unica', '=', 'ref.id_ref_unica')
        ->join('act_central AS act', 'ref.id_ref_unica', '=', 'act.id_ref_unica')
        ->join('usuario AS us', 'act.id_usuario', '=', 'us.id_usuario');
    
    if ($esGerente) {
        $query->where('us.id_departamento', $id_departamento)
              ->where('rv.id_estatus', 1);
    } 
    elseif ($esPlanificacion) {
        $query->where('rv.id_estatus', 2);
    } 
    else {
        return 0;
    }
    
    return $query->count();
}

public static function marcarNotificacionComoLeida($id_notificacion)
{
    return DB::table('notificacion')
        ->where('id_notificacion', $id_notificacion)
        ->update(['leido' => true]);
}

        
}
