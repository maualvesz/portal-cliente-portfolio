<?php

namespace App\Http\Controllers;

use App\Models\Boleto;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BoletoController extends Controller
{
    /**
     * Lista os boletos do cliente logado, com filtro por status e por
     * intervalo de vencimento, paginados.
     */
    public function index(Request $request)
    {
        $clienteId = Auth::user()->cliente_id;

        $boletos = Boleto::where('cliente_id', $clienteId)
            ->status($request->query('status'))
            ->when($request->filled('vencimento_de'), fn ($q) => $q->whereDate('data_vencimento', '>=', $request->query('vencimento_de')))
            ->when($request->filled('vencimento_ate'), fn ($q) => $q->whereDate('data_vencimento', '<=', $request->query('vencimento_ate')))
            ->orderByDesc('data_vencimento')
            ->paginate(15)
            ->withQueryString();

        return view('boletos.index', [
            'boletos' => $boletos,
            'filtros' => $request->only(['status', 'vencimento_de', 'vencimento_ate']),
        ]);
    }

    /**
     * Gera um PDF fictício do boleto (layout simples, claramente rotulado
     * como fictício/demonstração).
     */
    public function pdf(Boleto $boleto)
    {
        abort_unless($boleto->cliente_id === Auth::user()->cliente_id, 403);

        $pdf = Pdf::loadView('pdf.boleto', ['boleto' => $boleto]);

        return $pdf->stream("boleto-{$boleto->numero_documento}.pdf");
    }
}
