<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    public $timestamps = false;
    protected $table = 'usuario';
    

    protected $fillable = ['usuario', 
                            'password',
                            'id_cargos',
                            'id_departamento',
                            'id_estatus_sesion'];
}
