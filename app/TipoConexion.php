<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoConexion extends Model
{
    protected $table = 'tipo_conexions';
    
    protected $fillable = [
        'tipo',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'status', 'updated_at', 'created_at',
    ];
}
