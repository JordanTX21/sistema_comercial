<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expediente extends Model
{
    protected $table = 'expedientes';

    protected $fillable = [
        'cliente_id',
        'estado_solicitud',
        'titulo_propiedad',
        'mapa_ubicacion',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'status', 'updated_at', //'created_at',
    ];
    public function cliente(){
        return $this->belongsTo( Clientes::class )->where('status', '=', true);
    }
}
