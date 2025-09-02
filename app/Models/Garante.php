<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Garante extends Model
{
    protected $fillable = [
        'operacion_id',
        'cliente_id',
    ];

    public function operacion()
    {
        return $this->belongsTo(Operacion::class);
    }

    // Un garante pertenece a un cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
