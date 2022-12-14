<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    protected $table = 'clientes';

    protected $fillable = [
        'nombre', 'apellido', 'tipo_documento_id', 'documento', 'deuda',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'tipo_documento_id', 'status', 'updated_at', 'created_at',
    ];
}
