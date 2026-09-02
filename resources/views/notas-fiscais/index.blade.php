<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Notas Fiscais</h2>
    </x-slot>

    <div class="space-y-6">
        <form method="GET" class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-medium text-gray-500 mb-1">Buscar (número da nota ou pedido)</label>
                <input type="text" name="busca" value="{{ $filtros['busca'] ?? '' }}" placeholder="Ex: 100234 ou PED-1-1001" class="w-full rounded-lg border-gray-300 text-sm">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Emissão de</label>
                <input type="date" name="data_de" value="{{ $filtros['data_de'] ?? '' }}" class="rounded-lg border-gray-300 text-sm">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Emissão até</label>
                <input type="date" name="data_ate" value="{{ $filtros['data_ate'] ?? '' }}" class="rounded-lg border-gray-300 text-sm">
            </div>

            <div class="flex gap-2">
                <button type="submit" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">
                    Filtrar
                </button>
                <a href="{{ route('notas-fiscais.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50">
                    Limpar
                </a>
            </div>
        </form>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Nota Fiscal</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Pedido</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Valor</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Emissão</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-600">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($notas as $nota)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium text-gray-800">{{ $nota->numero_nota }}</td>
                                <td class="px-4 py-3 text-gray-600">
                                    <a href="{{ route('pedidos.show', $nota->pedido_id) }}" class="text-indigo-600 hover:underline">{{ $nota->pedido_id }}</a>
                                </td>
                                <td class="px-4 py-3 text-gray-600">R$ {{ number_format($nota->valor_total, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $nota->data_emissao->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-right space-x-3">
                                    <a href="{{ route('notas-fiscais.show', $nota) }}" class="text-gray-600 hover:text-gray-900 font-medium">Detalhes</a>
                                    <a href="{{ route('notas-fiscais.pdf', $nota) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 font-medium">PDF</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-400">Nenhuma nota fiscal encontrada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($notas->hasPages())
                <div class="px-4 py-3 border-t border-gray-100">
                    {{ $notas->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
