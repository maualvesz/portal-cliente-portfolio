<?php

namespace Database\Seeders;

use App\Models\Cliente;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

/**
 * Para cada cliente, gera de 15 a 40 boletos fictícios distribuídos nos
 * últimos 12 meses, com mistura de pagos, em aberto e vencidos.
 */
class BoletoSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake('pt_BR');

        Cliente::all()->each(function (Cliente $cliente) use ($faker) {
            $quantidade = random_int(15, 40);
            $boletos = [];

            for ($i = 0; $i < $quantidade; $i++) {
                $dataEmissao = Carbon::now()->subDays(random_int(0, 365));
                $dataVencimento = $dataEmissao->copy()->addDays(random_int(15, 45));
                $valor = $faker->randomFloat(2, 200, 15000);

                // Distribuição aproximada: 55% pagos, 25% em aberto, 20% vencidos.
                $sorteio = random_int(1, 100);

                if ($sorteio <= 55) {
                    // Pago: baixa entre a emissão e (no máximo) hoje.
                    $limiteBaixa = $dataVencimento->isFuture() ? $dataVencimento : Carbon::now();
                    $dataBaixa = $faker->dateTimeBetween($dataEmissao, $limiteBaixa);
                    $saldoDevedor = 0;
                } elseif ($sorteio <= 80) {
                    // Em aberto: vencimento ainda não passou.
                    $dataVencimento = Carbon::now()->addDays(random_int(1, 60));
                    $dataBaixa = null;
                    $saldoDevedor = $valor;
                } else {
                    // Vencido: vencimento já passou e não há baixa.
                    $dataVencimento = Carbon::now()->subDays(random_int(1, 180));
                    $dataBaixa = null;
                    $saldoDevedor = $valor;
                }

                $boletos[] = [
                    'cliente_id' => $cliente->id,
                    'numero_documento' => strtoupper($faker->bothify('BOL-####??')),
                    'valor_documento' => $valor,
                    'saldo_devedor' => $saldoDevedor,
                    'data_vencimento' => $dataVencimento->format('Y-m-d'),
                    'data_baixa' => $dataBaixa ? Carbon::parse($dataBaixa)->format('Y-m-d') : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $cliente->boletos()->insert($boletos);
        });

        $this->command?->info('Boletos fictícios gerados para todos os clientes.');
    }
}
