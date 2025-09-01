<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Socio extends Model
{
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
