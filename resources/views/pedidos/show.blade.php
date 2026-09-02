<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pedido {{ $pedidoId }}</h2>
    </x-slot>

    <div class="space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <x-stat-card label="Data do pedido" :value="$dataPedido->format('d/m/Y')" />
            <x-stat-card label="Notas fiscais" :value="$notas->count()" />
            <x-stat-card label="Valor total das notas" :value="'R$ '.number_format($valorTotal, 2, ',', '.')" accent="emerald" />
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700">Notas fiscais do pedido</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Nota Fiscal</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Valor</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Emissão</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-600">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($notas as $nota)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium text-gray-800">{{ $nota->numero_nota }}</td>
                                <td class="px-4 py-3 text-gray-600">R$ {{ number_format($nota->valor_total, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $nota->data_emissao->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-right space-x-3">
                                    <a href="{{ route('notas-fiscais.show', $nota) }}" class="text-gray-600 hover:text-gray-900 font-medium">Detalhes</a>
                                    <a href="{{ route('notas-fiscais.pdf', $nota) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 font-medium">PDF</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700">Boletos vinculados (por proximidade de data)</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Documento</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Valor</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Vencimento</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-600">PDF</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($boletos as $boleto)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium text-gray-800">{{ $boleto->numero_documento }}</td>
                                <td class="px-4 py-3 text-gray-600">R$ {{ number_format($boleto->valor_documento, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $boleto->data_vencimento->format('d/m/Y') }}</td>
                                <td class="px-4 py-3"><x-status-badge :status="$boleto->status" /></td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('boletos.pdf', $boleto) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 font-medium">Ver PDF</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-400">Nenhum boleto vinculado a este pedido no período considerado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <a href="{{ route('pedidos.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
            &larr; Voltar para pedidos
        </a>
    </div>
</x-app-layout>
