<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Decimo extends Model
{
    use HasFactory;

    protected $table = "decimos";

    //////////////////////
    ///// RELACIONES /////
    //////////////////////

    /**
     * Usuario al que pertenece el décimo
     *
     * @return BelongsTo
     */
    public function usuario() : BelongsTo
    {
        return $this->belongsTo(User::class, "usuario", "id");
    }

    /////////////////////////////
    ///// MÉTODOS ESTÁTICOS /////
    /////////////////////////////

//TODO:
    public static function crearDecimo()
    {

    }

    //TODO:
    public static function modificarDecimo()
    {

    }

    //TODO:
    public static function eliminarDecimo()
    {

    }
}
