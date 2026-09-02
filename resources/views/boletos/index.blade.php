<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Boletos</h2>
    </x-slot>

    <div class="space-y-6">
        <form method="GET" class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                <select name="status" class="rounded-lg border-gray-300 text-sm">
                    <option value="">Todos</option>
                    @foreach (['aberto' => 'Em aberto', 'pago' => 'Pago', 'vencido' => 'Vencido'] as $value => $label)
                        <option value="{{ $value }}" @selected(($filtros['status'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Vencimento de</label>
                <input type="date" name="vencimento_de" value="{{ $filtros['vencimento_de'] ?? '' }}" class="rounded-lg border-gray-300 text-sm">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Vencimento até</label>
                <input type="date" name="vencimento_ate" value="{{ $filtros['vencimento_ate'] ?? '' }}" class="rounded-lg border-gray-300 text-sm">
            </div>

            <div class="flex gap-2">
                <button type="submit" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">
                    Filtrar
                </button>
                <a href="{{ route('boletos.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50">
                    Limpar
                </a>
            </div>
        </form>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Documento</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Valor</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Saldo devedor</th>
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
                                <td class="px-4 py-3 text-gray-600">R$ {{ number_format($boleto->saldo_devedor, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $boleto->data_vencimento->format('d/m/Y') }}</td>
                                <td class="px-4 py-3"><x-status-badge :status="$boleto->status" /></td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('boletos.pdf', $boleto) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 font-medium">
                                        Ver PDF
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-400">Nenhum boleto encontrado para os filtros selecionados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($boletos->hasPages())
                <div class="px-4 py-3 border-t border-gray-100">
                    {{ $boletos->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
