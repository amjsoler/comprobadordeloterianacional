<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Sorteo extends Model
{
    use HasFactory;

    protected $table = "sorteos";

    //////////////////////
    ///// RELACIONES /////
    //////////////////////


    public function resultado() : HasOne
    {
        return $this->hasOne(Resultado::class, "sorteo", "id");
    }

    /////////////////////////////
    ///// MÉTODOS ESTÁTICOS /////
    /////////////////////////////

    //TODO:
    public static function crearSorteo()
    {

    }

    //TODO:
    public static function modificarSorteo()
    {

    }

    //TODO:
    public static function eliminarSorteo()
    {

    }
}
