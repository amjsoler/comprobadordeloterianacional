<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

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
    public function sorteo(): BelongsTo
    {
        return $this->belongsTo(Sorteo::class, "usuario", "id");
    }

    /////////////////////////////
    ///// MÉTODOS ESTÁTICOS /////
    /////////////////////////////


}
