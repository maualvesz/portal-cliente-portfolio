<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nota Fiscal {{ $nota->numero_nota }}</h2>
    </x-slot>

    <div class="max-w-2xl bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-4">
        <dl class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <dt class="text-gray-500">Número da nota</dt>
                <dd class="font-medium text-gray-900">{{ $nota->numero_nota }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Pedido</dt>
                <dd class="font-medium text-gray-900">
                    <a href="{{ route('pedidos.show', $nota->pedido_id) }}" class="text-indigo-600 hover:underline">{{ $nota->pedido_id }}</a>
                </dd>
            </div>
            <div>
                <dt class="text-gray-500">Valor total</dt>
                <dd class="font-medium text-gray-900">R$ {{ number_format($nota->valor_total, 2, ',', '.') }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Data de emissão</dt>
                <dd class="font-medium text-gray-900">{{ $nota->data_emissao->format('d/m/Y') }}</dd>
            </div>
        </dl>

        <div class="pt-4 border-t border-gray-100 flex gap-3">
            <a href="{{ route('notas-fiscais.pdf', $nota) }}" target="_blank" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">
                Ver PDF
            </a>
            <a href="{{ route('notas-fiscais.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50">
                Voltar
            </a>
        </div>
    </div>
</x-app-layout>
