<?php

namespace App\Jobs;

use App\Models\NotaFiscal;
use App\Services\ErpApiMock;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

/**
 * Sincroniza notas fiscais a partir do ERP externo (mockado nesta
 * demonstração). Ver SyncBoletosJob para detalhes do padrão adotado.
 */
class SyncNotasFiscaisJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected int $chanceDeFalhaSimulada = 0,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $erp = new ErpApiMock($this->chanceDeFalhaSimulada);
            $notas = $erp->getNotasFiscais();

            $sincronizadas = 0;

            foreach ($notas as $dados) {
                NotaFiscal::updateOrCreate(
                    [
                        'cliente_id' => $dados['cliente_id'],
                        'numero_nota' => $dados['numero_nota'],
                    ],
                    [
                        'pedido_id' => $dados['pedido_id'],
                        'valor_total' => $dados['valor_total'],
                        'data_emissao' => $dados['data_emissao'],
                    ]
                );

                $sincronizadas++;
            }

            Log::info("SyncNotasFiscaisJob: {$sincronizadas} nota(s) fiscal(is) sincronizada(s) a partir do ERP.");
        } catch (\Throwable $e) {
            Log::error('SyncNotasFiscaisJob: falha ao sincronizar notas fiscais com o ERP externo.', [
                'mensagem' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
