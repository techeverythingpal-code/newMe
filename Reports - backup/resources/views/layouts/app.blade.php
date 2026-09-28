<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'newMe') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-brand-100">
        {{-- In RTL the first flex child (the sidebar) sits on the right.
             To put it on the left instead, add "flex-row-reverse" to the div below. --}}
        <div x-data="{ sidebarOpen: false }" class="min-h-screen flex">

            {{-- Sidebar (also renders the mobile backdrop) --}}
            @include('layouts.navigation')

            <div class="flex-1 min-w-0 flex flex-col">

                {{-- Mobile top bar --}}
                <div class="lg:hidden sticky top-0 z-30 flex items-center justify-between bg-brand-500 text-white px-4 h-14">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 font-bold">
                        <span class="text-xl">📋</span>
                        <span>{{ config('app.name') }}</span>
                    </a>
                    <button type="button" @click="sidebarOpen = true"
                        class="p-2 rounded-md hover:bg-white/10 focus:outline-none" aria-label="القائمة">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

                <!-- Page Heading -->
                @isset($header)
                    <header class="px-4 sm:px-6 lg:px-8 pt-6">
                        <div class="bg-white rounded-2xl shadow-sm px-6 py-4">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="flex-1">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>