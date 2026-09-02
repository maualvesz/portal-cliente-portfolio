<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pedidos</h2>
    </x-slot>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Pedido</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Data</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Notas fiscais</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Boletos vinculados</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Valor total</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($pedidos as $pedido)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $pedido->pedido_id }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ \Illuminate\Support\Carbon::parse($pedido->data_pedido)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $pedido->qtd_notas }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $pedido->qtd_boletos }}</td>
                            <td class="px-4 py-3 text-gray-600">R$ {{ number_format($pedido->valor_total, 2, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('pedidos.show', $pedido->pedido_id) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Detalhes</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-400">Nenhum pedido encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pedidos->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $pedidos->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
