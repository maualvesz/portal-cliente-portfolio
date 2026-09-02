<?php

namespace App\Services;

use App\Models\Cliente;
use Illuminate\Support\Str;

/**
 * Simula as respostas de uma API REST de um ERP externo.
 *
 * Nenhuma chamada HTTP real é feita aqui — esta classe existe apenas para
 * demonstrar, em um protótipo de portfólio, o formato de dados que um Job
 * de sincronização (SyncBoletosJob / SyncNotasFiscaisJob) consumiria de um
 * ERP de verdade. Em um cenário real, esta classe seria substituída por um
 * client HTTP (Guzzle/Http facade) apontando para o endpoint do ERP, com
 * autenticação e tratamento de timeouts/erros de rede.
 *
 * Os dados retornados são 100% fictícios, gerados via Faker.
 */
class ErpApiMock
{
    /**
     * Probabilidade (0-100) de simular uma falha de comunicação com o ERP,
     * para exercitar o tratamento de erro nos Jobs de sincronização.
     */
    protected int $chanceDeFalha = 0;

    public function __construct(int $chanceDeFalha = 0)
    {
        $this->chanceDeFalha = $chanceDeFalha;
    }

    /**
     * Simula GET /api/boletos do ERP externo: retorna um lote de boletos
     * fictícios para os clientes já cadastrados no portal.
     *
     * @return array<int, array<string, mixed>>
     *
     * @throws \RuntimeException quando a "comunicação" simulada falha.
     */
    public function getBoletos(): array
    {
        $this->simularFalhaDeRedeSeNecessario('GET /api/boletos');

        $faker = fake('pt_BR');
        $clientesIds = Cliente::pluck('id');

        if ($clientesIds->isEmpty()) {
            return [];
        }

        $lote = [];

        foreach ($clientesIds as $clienteId) {
            $novosBoletos = random_int(1, 3);

            for ($i = 0; $i < $novosBoletos; $i++) {
                $vencimento = now()->addDays(random_int(-10, 45));
                $pago = $faker->boolean(40);

                $lote[] = [
                    'cliente_id' => $clienteId,
                    'numero_documento' => 'ERP-'.strtoupper(Str::random(8)),
                    'valor_documento' => $faker->randomFloat(2, 200, 15000),
                    'saldo_devedor' => $pago ? 0 : $faker->randomFloat(2, 200, 15000),
                    'data_vencimento' => $vencimento->format('Y-m-d'),
                    'data_baixa' => $pago ? $vencimento->copy()->subDays(random_int(0, 5))->format('Y-m-d') : null,
                ];
            }
        }

        return $lote;
    }

    /**
     * Simula GET /api/notas-fiscais do ERP externo: retorna um lote de
     * notas fiscais fictícias, já agrupadas por pedido.
     *
     * @return array<int, array<string, mixed>>
     *
     * @throws \RuntimeException quando a "comunicação" simulada falha.
     */
    public function getNotasFiscais(): array
    {
        $this->simularFalhaDeRedeSeNecessario('GET /api/notas-fiscais');

        $faker = fake('pt_BR');
        $clientesIds = Cliente::pluck('id');

        if ($clientesIds->isEmpty()) {
            return [];
        }

        $lote = [];

        foreach ($clientesIds as $clienteId) {
            $pedidoId = 'PED-'.$clienteId.'-'.random_int(9000, 9999);
            $notasNoPedido = random_int(1, 3);
            $dataEmissao = now()->subDays(random_int(0, 10));

            for ($i = 0; $i < $notasNoPedido; $i++) {
                $lote[] = [
                    'cliente_id' => $clienteId,
                    'pedido_id' => $pedidoId,
                    'numero_nota' => (string) random_int(900000, 999999),
                    'valor_total' => $faker->randomFloat(2, 150, 8000),
                    'data_emissao' => $dataEmissao->format('Y-m-d'),
                ];
            }
        }

        return $lote;
    }

    /**
     * Simula uma falha de comunicação com o ERP (timeout, endpoint fora do
     * ar, etc.), de acordo com $chanceDeFalha.
     */
    protected function simularFalhaDeRedeSeNecessario(string $endpoint): void
    {
        if ($this->chanceDeFalha > 0 && random_int(1, 100) <= $this->chanceDeFalha) {
            throw new \RuntimeException("Falha simulada de comunicação com o ERP externo em {$endpoint} (timeout/endpoint indisponível).");
        }
    }
}
