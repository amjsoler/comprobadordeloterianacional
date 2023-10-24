<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resultado extends Model
{
    use HasFactory;

    protected $table = "resultados";

    //////////////////////
    ///// RELACIONES /////
    //////////////////////


    /**
     * El sorteo al que pertenece este resultado
     *
     * @return BelongsTo
     */
    public function sorteo() : BelongsTo
    {
        return $this->belongsTo(Sorteo::class, "usuario", "id");
    }

    /////////////////////////////
    ///// MÉTODOS ESTÁTICOS /////
    /////////////////////////////

    //TODO:
    public static function crearResultado()
    {

    }

    //TODO:
    public static function modificarResultado()
    {

    }

    //TODO:
    public static function eliminarResultado()
    {

    }

}
