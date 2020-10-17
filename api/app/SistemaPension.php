<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SistemaPension extends Model
{
    protected $table = "sistemapensiones";
    protected $primaryKey = 'Codigo';
    public $timestamps = false;
    protected $fillable = [
        'Codigo',
        'Nombre',
        'Siglas',
        'Vigencia'
    ];
}
