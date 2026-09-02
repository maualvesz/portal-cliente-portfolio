<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Portal do Cliente') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100 text-gray-900">
        <div class="min-h-screen flex">
            @include('layouts.sidebar')

            <div class="flex-1 flex flex-col min-w-0">
                <!-- Top bar -->
                <header class="bg-white border-b border-gray-200 sticky top-0 z-10">
                    <div class="px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
                        <div>
                            @isset($header)
                                {{ $header }}
                            @endisset
                        </div>

                        <div class="flex items-center gap-3 text-sm">
                            <div class="text-right hidden sm:block">
                                <p class="font-medium text-gray-800">{{ Auth::user()->name }}</p>
                                <p class="text-gray-500">{{ Auth::user()->cliente?->nome_fantasia ?? 'Sem cliente vinculado' }}</p>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="text-gray-500 hover:text-gray-800" title="Meu perfil">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 p-4 sm:p-6 lg:p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
