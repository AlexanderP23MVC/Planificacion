<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    public $timestamps = false;
    protected $table = 'usuario';
    protected $primaryKey = 'id_user';

    protected $fillable = [
        'user_name',

        // añade aquí los campos de tu tabla
    ];
}
