<?php

namespace Database\Seeders;

use App\Models\Cliente;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

/**
 * Para cada cliente, gera de 10 a 25 notas fiscais fictícias, agrupadas em
 * "pedidos" (2 a 4 notas por pedido_id), com datas de emissão coerentes
 * (todas as notas de um mesmo pedido emitidas em dias próximos).
 */
class NotaFiscalSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake('pt_BR');
        $numeroNota = 100000;

        Cliente::all()->each(function (Cliente $cliente) use ($faker, &$numeroNota) {
            $totalNotas = random_int(10, 25);
            $notas = [];
            $pedidoSequencial = random_int(1000, 1999);
            $geradas = 0;

            while ($geradas < $totalNotas) {
                $notasNoPedido = min(random_int(2, 4), $totalNotas - $geradas);
                $pedidoId = 'PED-'.$cliente->id.'-'.$pedidoSequencial++;
                $dataBasePedido = Carbon::now()->subDays(random_int(0, 365));

                for ($n = 0; $n < $notasNoPedido; $n++) {
                    $dataEmissao = $dataBasePedido->copy()->addDays(random_int(0, 3));

                    $notas[] = [
                        'cliente_id' => $cliente->id,
                        'pedido_id' => $pedidoId,
                        'numero_nota' => (string) $numeroNota++,
                        'valor_total' => $faker->randomFloat(2, 150, 8000),
                        'data_emissao' => $dataEmissao->format('Y-m-d'),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    $geradas++;
                }
            }

            $cliente->notasFiscais()->insert($notas);
        });

        $this->command?->info('Notas fiscais fictícias (agrupadas por pedido) geradas para todos os clientes.');
    }
}
