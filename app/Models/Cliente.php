<?php

namespace App\Models;

use App\Traits\AuditableTrait;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use AuditableTrait;
    protected $fillable = [
        'tipodoc',
        'documento',
        'sexo',
        'cuit',
        'apelnombres',
        'nacimiento',
        'nacionalidad',
        'domicilio',
        'cod_postal_id',
        'telefono',
        'email',
        'estado',
        'fechaestado',
        'observacion',
        'created_by',
        'updated_by',
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
