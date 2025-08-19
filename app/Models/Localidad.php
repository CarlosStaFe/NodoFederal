<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Localidad extends Model
{
    use HasFactory;

    protected $table = 'localidades';

    protected $fillable = ['id_prov', 'provincia', 'id_local', 'localidad', 'cod_postal'];

    public function pacientes()
    {
        return $this->hasMany(Paciente::class, 'cod_postal_id');
    }
}
