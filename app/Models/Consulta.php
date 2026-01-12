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

    protected $casts = [
        'fecha' => 'datetime',
    ];

    // Relación con Nodo
    public function nodo()
    {
        return $this->belongsTo(Nodo::class);
    }

    // Relación con Socio
    public function socio()
    {
        return $this->belongsTo(Socio::class);
    }

    // Relación con Usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
