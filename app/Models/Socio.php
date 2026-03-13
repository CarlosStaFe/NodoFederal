<?php

namespace App\Models;

use App\Traits\AuditableTrait;
use Illuminate\Database\Eloquent\Model;

class Socio extends Model
{
    use AuditableTrait;

    protected $fillable = [
        'created_by',
        'updated_by',
    ];
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function nodo()
    {
        return $this->belongsTo(\App\Models\Nodo::class, 'nodo_id');
    }

    public function localidad()
    {
        return $this->belongsTo(\App\Models\Localidad::class, 'cod_postal_id');
    }
}
