<?php

namespace App\Http\Controllers;

use App\Models\Boleto;
use App\Models\NotaFiscal;
use Illuminate\Support\Facades\Auth;

class PedidoController extends Controller
{
    /**
     * Número de dias, a partir da emissão das notas de um pedido, em que um
     * boleto é considerado "vinculado" a ele (por proximidade de data —
     * não existe uma FK direta entre boletos e pedidos neste protótipo,
     * assim como no sistema real que o inspirou).
     */
    protected const JANELA_VINCULO_DIAS = 45;

    /**
     * Lista os pedidos do cliente logado: cada pedido é um agrupamento de
     * notas fiscais por pedido_id, mostrando quantidade de notas,
     * quantidade de boletos vinculados (por proximidade de data), valor
     * total e data do pedido.
     */
    public function index()
    {
        $clienteId = Auth::user()->cliente_id;

        $pedidos = NotaFiscal::where('cliente_id', $clienteId)
            ->selectRaw('pedido_id, COUNT(*) as qtd_notas, SUM(valor_total) as valor_total, MIN(data_emissao) as data_pedido')
            ->groupBy('pedido_id')
            ->orderByDesc('data_pedido')
            ->paginate(15);

        $pedidos->getCollection()->transform(function ($pedido) use ($clienteId) {
            $pedido->qtd_boletos = $this->contarBoletosVinculados($clienteId, $pedido->data_pedido); 

            return $pedido;
        });

        return view('pedidos.index', ['pedidos' => $pedidos]);
    }

    /**
     * Exibe o detalhe de um pedido: notas fiscais que o compõem e boletos
     * vinculados por proximidade de data.
     */
    public function show(string $pedidoId)
    {
        $clienteId = Auth::user()->cliente_id;

        $notas = NotaFiscal::where('cliente_id', $clienteId)
            ->where('pedido_id', $pedidoId)
            ->orderBy('data_emissao')
            ->get();

        abort_if($notas->isEmpty(), 404);

        $dataPedido = $notas->min('data_emissao');

        $boletos = Boleto::where('cliente_id', $clienteId)
            ->whereBetween('data_vencimento', [
                $dataPedido->copy(),
                $dataPedido->copy()->addDays(self::JANELA_VINCULO_DIAS),
            ])
            ->orderBy('data_vencimento')
            ->get();

        return view('pedidos.show', [
            'pedidoId' => $pedidoId,
            'notas' => $notas,
            'boletos' => $boletos,
            'valorTotal' => $notas->sum('valor_total'),
            'dataPedido' => $dataPedido,
        ]);
    }

    protected function contarBoletosVinculados(?int $clienteId, string $dataPedido): int
    {
        $inicio = \Carbon\Carbon::parse($dataPedido);

        return Boleto::where('cliente_id', $clienteId)
            ->whereBetween('data_vencimento', [
                $inicio->copy(),
                $inicio->copy()->addDays(self::JANELA_VINCULO_DIAS),
            ])
            ->count();
    }
}
