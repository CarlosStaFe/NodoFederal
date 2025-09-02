<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'tipodoc',
        'documento',
        'sexo',
        'cuit',
        'apelnombres',
        'nacimiento',
        'domicilio',
        'cod_postal_id',
        'telefono',
        'email',
        'estado',
        'fechaestado',
        'observacion',
    ];

    public function localidad()
    {
        return $this->belongsTo(Localidad::class, 'cod_postal_id');
    }

    // Un cliente puede tener muchos garantes
    public function garantes()
    {
        return $this->hasMany(Garante::class);
    }
}
