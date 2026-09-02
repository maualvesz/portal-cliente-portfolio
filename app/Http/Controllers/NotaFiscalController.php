<?php

namespace App\Http\Controllers;

use App\Models\NotaFiscal;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotaFiscalController extends Controller
{
    /**
     * Lista as notas fiscais do cliente logado, com busca (número da nota
     * ou pedido) e filtro por data de emissão.
     */
    public function index(Request $request)
    {
        $clienteId = Auth::user()->cliente_id;

        $notas = NotaFiscal::where('cliente_id', $clienteId)
            ->when($request->filled('busca'), function ($q) use ($request) {
                $busca = $request->query('busca');
                $q->where(function ($q) use ($busca) {
                    $q->where('numero_nota', 'like', "%{$busca}%")
                        ->orWhere('pedido_id', 'like', "%{$busca}%");
                });
            })
            ->when($request->filled('data_de'), fn ($q) => $q->whereDate('data_emissao', '>=', $request->query('data_de')))
            ->when($request->filled('data_ate'), fn ($q) => $q->whereDate('data_emissao', '<=', $request->query('data_ate')))
            ->orderByDesc('data_emissao')
            ->paginate(15)
            ->withQueryString();

        return view('notas-fiscais.index', [
            'notas' => $notas,
            'filtros' => $request->only(['busca', 'data_de', 'data_ate']),
        ]);
    }

    /**
     * Exibe o detalhe de uma nota fiscal.
     */
    public function show(NotaFiscal $notaFiscal)
    {
        abort_unless($notaFiscal->cliente_id === Auth::user()->cliente_id, 403);

        return view('notas-fiscais.show', ['nota' => $notaFiscal]);
    }

    /**
     * Gera um PDF fictício da nota fiscal.
     */
    public function pdf(NotaFiscal $notaFiscal)
    {
        abort_unless($notaFiscal->cliente_id === Auth::user()->cliente_id, 403);

        $pdf = Pdf::loadView('pdf.nota-fiscal', ['nota' => $notaFiscal]);

        return $pdf->stream("nota-fiscal-{$notaFiscal->numero_nota}.pdf");
    }
}
