<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'files';
    protected $fillable = ['type','name','url'];
    protected $hidden = ['created_at', 'updated_at'];
}
