<?php

namespace App\Console\Commands;

use App\Jobs\SyncBoletosJob;
use App\Jobs\SyncNotasFiscaisJob;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

/**
 * Dispara manualmente e de forma SÍNCRONA os Jobs de sincronização com o
 * ERP externo (mockado), sem depender de um queue worker rodando — útil
 * para demonstrar o fluxo em um ambiente local/portfólio.
 *
 * Em produção, os mesmos Jobs rodam de forma assíncrona via fila,
 * agendados pelo scheduler (routes/console.php).
 */
#[Signature('sync:demo {--falhar= : Chance (0-100) de simular falha de comunicação com o ERP}')]
#[Description('Dispara manualmente a sincronização (síncrona) com o ERP externo mockado, para fins de demonstração.')]
class SyncDemoCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $chanceDeFalha = (int) ($this->option('falhar') ?? 0);

        $this->info('Sincronizando boletos com o ERP externo (mock)...');

        try {
            SyncBoletosJob::dispatchSync($chanceDeFalha);
            $this->info('Boletos sincronizados com sucesso.');
        } catch (\Throwable $e) {
            $this->error('Falha ao sincronizar boletos: '.$e->getMessage());
        }

        $this->info('Sincronizando notas fiscais com o ERP externo (mock)...');

        try {
            SyncNotasFiscaisJob::dispatchSync($chanceDeFalha);
            $this->info('Notas fiscais sincronizadas com sucesso.');
        } catch (\Throwable $e) {
            $this->error('Falha ao sincronizar notas fiscais: '.$e->getMessage());
        }

        $this->info('Sincronização de demonstração concluída.');

        return self::SUCCESS;
    }
}
