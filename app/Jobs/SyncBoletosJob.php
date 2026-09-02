<?php

namespace App\Jobs;

use App\Models\Boleto;
use App\Services\ErpApiMock;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

/**
 * Sincroniza boletos a partir do ERP externo (mockado nesta demonstração).
 *
 * Em produção este Job roda de forma assíncrona via fila (`ShouldQueue`),
 * disparado periodicamente pelo scheduler (ver routes/console.php). Cada
 * registro recebido do ERP é gravado via updateOrCreate, usando o par
 * (cliente_id, numero_documento) como chave de conciliação — o mesmo
 * padrão usado no sistema real que inspirou este protótipo.
 */
class SyncBoletosJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
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
            $boletos = $erp->getBoletos();

            $sincronizados = 0;

            foreach ($boletos as $dados) {
                Boleto::updateOrCreate(
                    [
                        'cliente_id' => $dados['cliente_id'],
                        'numero_documento' => $dados['numero_documento'],
                    ],
                    [
                        'valor_documento' => $dados['valor_documento'],
                        'saldo_devedor' => $dados['saldo_devedor'],
                        'data_vencimento' => $dados['data_vencimento'],
                        'data_baixa' => $dados['data_baixa'],
                    ]
                );

                $sincronizados++;
            }

            Log::info("SyncBoletosJob: {$sincronizados} boleto(s) sincronizado(s) a partir do ERP.");
        } catch (\Throwable $e) {
            // Erro de comunicação com o ERP externo (timeout, endpoint fora
            // do ar, resposta inválida, etc.) — registrado em log e a
            // exceção é relançada para que a fila trate o retry conforme
            // configurado (backoff/tentativas em config/queue.php).
            Log::error('SyncBoletosJob: falha ao sincronizar boletos com o ERP externo.', [
                'mensagem' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
