<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
    protected $table = 'ciudades';
    protected $fillable = [
        'nombre_ciudad',
        'latitud',
        'longitud',
    ];
    
}
