<?php

namespace App\Jobs;

use App\Helpers\Helpers;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class EsnifarResultados implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::debug("Entrando al Job EsnifarResultados");

        try{
            $result = Helpers::esnifarYGuardarResultadosDisponibles();

            if($result["code"] == 0){
                Log::debug("Ejecución del Job EsnifarResultados satisfactoria");
            }else{
                Log::debug("Ejecución del Job EsnifarResultados erronea");
                throw new Exception("Ejecución del job EsnifarResultados erronea");
            }
        }
        catch(Exception $e){
            Log::error($e->getMessage());

            throw new Exception("Ejecución del job EsnifarResultados erronea");
        }

        Log::debug("Saliendo del Job EsnifarResultados");
    }
}
