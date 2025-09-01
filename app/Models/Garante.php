<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Garante extends Model
{
    protected $fillable = [
        'operacion_id',
        'cuit',
        'tipodoc',
        'sexo',
        'documento',
        'apelnombres',
    ];

    public function operacion()
    {
        return $this->belongsTo(Operacion::class);
    }
}
