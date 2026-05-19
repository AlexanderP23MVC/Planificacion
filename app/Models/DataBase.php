<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
            'act.actividades'
        )
        ->get();
}


    

        
}
