<?php

namespace App\Console;

use App\Jobs\Borrar;
use App\Jobs\EsnifarResultados;
use App\Jobs\EsnifarSorteos;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        //TODO: Una vez en produccion, ver si estas tareas requieren de un withoutoverlapping
        $schedule->job(new EsnifarSorteos(), "jobs", "database")->everyTenMinutes();
        $schedule->job(new EsnifarResultados(), "jobs", "database")->everyTenMinutes();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
