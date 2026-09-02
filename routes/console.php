<?php

use App\Jobs\SyncBoletosJob;
use App\Jobs\SyncNotasFiscaisJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Agendamento da sincronização com o ERP externo
|--------------------------------------------------------------------------
|
| A partir do Laravel 11, o agendamento deixou de ficar em
| app/Console/Kernel.php e passou a ser definido diretamente aqui (ou via
| Application::configure()->withSchedule() em bootstrap/app.php). O
| equivalente ao clássico `$schedule->job(...)->hourly()` é o seguinte:
|
| Em produção isso exige um cron único apontando para
| `php artisan schedule:run` a cada minuto (ver README) — o Laravel decide,
| a cada execução, quais tarefas realmente precisam rodar.
*/
Schedule::job(new SyncBoletosJob)->hourly()->name('sync-boletos-erp');
Schedule::job(new SyncNotasFiscaisJob)->hourly()->name('sync-notas-fiscais-erp');
