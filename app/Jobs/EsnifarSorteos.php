<?php

namespace App\Jobs;

use App\Helpers\Helpers;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class EsnifarSorteos implements ShouldQueue
{
    use Dispatchable, SerializesModels, Queueable, InteractsWithQueue;

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
        Log::debug("Entrando al Job Esnifar Sorteos");

        try{
            $result = Helpers::esnifarYGuardarNuevosSorteos();

            if($result["code"] == 0){
                Log::debug("Ejecución del Job Esnifar Sorteos satisfactoria");
            }else{
                Log::debug("Ejecución del Job Esnifar Sorteos erronea");
                throw new Exception("Ejecución del job EsnifarSorteos erronea");
            }
        }
        catch(Exception $e){
            Log::error($e->getMessage());

            throw new Exception("Ejecución del job EsnifarSorteos erronea");
        }

        Log::debug("Saliendo del Job Esnifar Sorteos");
    }
}
