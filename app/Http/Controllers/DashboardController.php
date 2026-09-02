<?php

namespace App\Http\Controllers;

use App\Models\Boleto;
use App\Models\NotaFiscal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Exibe o dashboard do cliente logado: cards de resumo + dados para os
     * gráficos (valor de boletos por mês nos últimos 6 meses, e
     * distribuição de boletos por status).
     */
    public function index()
    {
        $clienteId = Auth::user()->cliente_id;

        $boletosQuery = Boleto::where('cliente_id', $clienteId);

        $abertos = (clone $boletosQuery)->status('aberto');
        $vencidos = (clone $boletosQuery)->status('vencido');

        $cards = [
            'boletos_em_aberto' => (clone $abertos)->count(),
            'valor_em_aberto' => (clone $abertos)->sum('saldo_devedor'),
            'boletos_vencidos' => (clone $vencidos)->count(),
            'total_pedidos' => NotaFiscal::where('cliente_id', $clienteId)
                ->distinct('pedido_id')
                ->count('pedido_id'),
        ];

        $grafico = [
            'meses' => $this->valoresPorMes($clienteId),
            'status' => $this->distribuicaoPorStatus($clienteId),
        ];

        return view('dashboard', [
            'cards' => $cards,
            'grafico' => $grafico,
        ]);
    }

    /**
     * Valor total de boletos por mês de vencimento, para os últimos 6 meses.
     */
    protected function valoresPorMes(?int $clienteId): array
    {
        $inicio = Carbon::now()->startOfMonth()->subMonths(5);

        $registros = Boleto::where('cliente_id', $clienteId)
            ->where('data_vencimento', '>=', $inicio->format('Y-m-d'))
            ->get(['valor_documento', 'data_vencimento']);

        $labels = [];
        $valores = [];

        for ($i = 5; $i >= 0; $i--) {
            $mes = Carbon::now()->subMonths($i);
            $chave = $mes->format('Y-m');
            $labels[$chave] = ucfirst($mes->translatedFormat('M/y'));
            $valores[$chave] = 0.0;
        }

        foreach ($registros as $boleto) {
            $chave = Carbon::parse($boleto->data_vencimento)->format('Y-m');

            if (array_key_exists($chave, $valores)) {
                $valores[$chave] += (float) $boleto->valor_documento;
            }
        }

        return [
            'labels' => array_values($labels),
            'valores' => array_map(fn ($v) => round($v, 2), array_values($valores)),
        ];
    }

    /**
     * Distribuição da quantidade de boletos por status calculado.
     */
    protected function distribuicaoPorStatus(?int $clienteId): array
    {
        $boletos = Boleto::where('cliente_id', $clienteId)->get(['data_baixa', 'data_vencimento']);

        $contagem = ['pago' => 0, 'aberto' => 0, 'vencido' => 0];

        foreach ($boletos as $boleto) {
            $contagem[$boleto->status]++;
        }

        return $contagem;
    }
}
