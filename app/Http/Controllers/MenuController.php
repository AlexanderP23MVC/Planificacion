<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataBase;

class MenuController extends Controller
{
        public static function getMenu()
    {
        $id_departamento = session('id_departamento');
    
        // Llamar a la función menu del modelo Home
        $menus = DataBase::menu($id_departamento);
               
        return $menus;
    }

    public static function getMenuActividadesEspecificas()
{
    return DataBase::getMenuActividadesEspecificas();
}
}
