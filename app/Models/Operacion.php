<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operacion extends Model
{
    protected $table = 'operaciones';
    protected $fillable = [
        'numero',
        'cliente_id',
        'estado_actual',
        'fecha_estado',
        'nodo_id',
        'socio_id',
        'tipo',
        'fecha_operacion',
        'valor_cuota',
        'cant_cuotas',
        'total',
        'fecha_cuota',
        'clase',
        'usuario_id',
    ];
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function socio()
    {
        return $this->belongsTo(Socio::class, 'socio_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

}
