<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </x-slot>

    <div class="space-y-6">
        <!-- Cards de resumo -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stat-card label="Boletos em aberto" :value="$cards['boletos_em_aberto']" accent="amber" />
            <x-stat-card label="Valor em aberto" :value="'R$ '.number_format($cards['valor_em_aberto'], 2, ',', '.')" accent="indigo" />
            <x-stat-card label="Boletos vencidos" :value="$cards['boletos_vencidos']" accent="red" />
            <x-stat-card label="Total de pedidos" :value="$cards['total_pedidos']" accent="emerald" />
        </div>

        <!-- Gráficos -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Valor de boletos por mês (últimos 6 meses)</h3>
                <canvas id="graficoMeses" height="110"></canvas>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Boletos por status</h3>
                <canvas id="graficoStatus" height="220"></canvas>
            </div>
        </div>

        <p class="text-xs text-gray-400">
            Todos os dados exibidos neste protótipo são fictícios (gerados via Faker) — nenhuma informação real de cliente é utilizada.
        </p>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script>
        const meses = @json($grafico['meses']['labels']);
        const valores = @json($grafico['meses']['valores']);
        const status = @json($grafico['status']);

        new Chart(document.getElementById('graficoMeses'), {
            type: 'bar',
            data: {
                labels: meses,
                datasets: [{
                    label: 'Valor de boletos (R$)',
                    data: valores,
                    backgroundColor: '#6366f1',
                    borderRadius: 6,
                    maxBarThickness: 48,
                }],
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: (v) => 'R$ ' + v.toLocaleString('pt-BR'),
                        },
                    },
                },
            },
        });

        new Chart(document.getElementById('graficoStatus'), {
            type: 'doughnut',
            data: {
                labels: ['Pago', 'Em aberto', 'Vencido'],
                datasets: [{
                    data: [status.pago, status.aberto, status.vencido],
                    backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                    borderWidth: 0,
                }],
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } },
            },
        });
    </script>
    @endpush
</x-app-layout>
