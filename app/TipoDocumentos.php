<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoDocumentos extends Model
{
    protected $table = 'tipo_documentos';

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
