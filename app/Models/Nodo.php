<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nodo extends Model
{
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function localidad()
    {
        return $this->belongsTo(Localidad::class, 'cod_postal_id');
    }
}
