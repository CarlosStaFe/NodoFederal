<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    protected $fillable = [
        'numero',
        'tipo',
        'cuit',
        'apelynombres',
        'fecha',
        'nodo_id',
        'socio_id',
        'user_id',
    ];
}
