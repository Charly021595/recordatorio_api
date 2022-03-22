<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auto extends Model
{
    use HasFactory;
    protected $table = 'autos';

    // Relación
    public function usuario(){
        return $this->belongsTo('App\User', 'usuario_id');
    }
}
