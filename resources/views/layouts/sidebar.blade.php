@php
    $links = [
        ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'M3 13.5V21h5.25v-5.25a1.5 1.5 0 011.5-1.5h4.5a1.5 1.5 0 011.5 1.5V21H21v-7.5M3 13.5L12 3l9 10.5M3 13.5h18'],
        ['route' => 'boletos.index', 'label' => 'Boletos', 'icon' => 'M2.25 8.25h19.5M2.25 8.25v10.5a1.5 1.5 0 001.5 1.5h16.5a1.5 1.5 0 001.5-1.5V8.25M2.25 8.25V6a1.5 1.5 0 011.5-1.5h16.5A1.5 1.5 0 0121.75 6v2.25M6 12h4.5M6 15.75h2.25'],
        ['route' => 'notas-fiscais.index', 'label' => 'Notas Fiscais', 'icon' => 'M9 12h6m-6 3.75h6M5.25 21h13.5A2.25 2.25 0 0021 18.75V5.25A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25v13.5A2.25 2.25 0 005.25 21z'],
        ['route' => 'pedidos.index', 'label' => 'Pedidos', 'icon' => 'M8.25 7.5V6.108c0-1.135.845-2.098 1.976-2.192.373-.03.748-.057 1.123-.08M15.75 18H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08M15.75 18.75v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V18.75m12-11.642V6.75a2.25 2.25 0 00-2.25-2.25h-.75a2.25 2.25 0 00-2.25 2.25v.108'],
    ];
@endphp

<aside class="w-64 shrink-0 bg-gray-900 text-gray-100 flex flex-col min-h-screen">
    <div class="px-5 py-5 border-b border-gray-800">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-500 font-bold text-white">PC</span>
            <span class="font-semibold tracking-tight leading-tight">
                Portal do<br>Cliente
            </span>
        </a>
    </div>

    <nav class="flex-1 px-3 py-4 space-y-1">
        @foreach ($links as $link)
            @php $active = request()->routeIs($link['route'].'*'); @endphp
            <a href="{{ route($link['route']) }}"
               class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                      {{ $active ? 'bg-indigo-500/15 text-indigo-300' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $link['icon'] }}" />
                </svg>
                {{ $link['label'] }}
            </a>
        @endforeach
    </nav>

    <div class="px-3 py-4 border-t border-gray-800">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-gray-300 hover:bg-gray-800 hover:text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                </svg>
                Sair
            </button>
        </form>
    </div>
</aside>
