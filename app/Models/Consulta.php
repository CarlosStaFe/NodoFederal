<?php

namespace App\Models;

use App\Traits\AuditableTrait;
use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    use AuditableTrait;

    protected $fillable = [
        'numero',
        'tipo',
        'cuit',
        'apelynombres',
        'fecha',
        'nodo_id',
        'socio_id',
        'user_id',
        'created_by',
        'updated_by',
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
