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
    <body class="font-sans text-gray-900 antialiased" dir="rtl">
        <div class="min-h-screen flex items-center justify-center bg-gray-100 p-4">
            <div class="w-full max-w-4xl bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row">

                {{-- Branding panel --}}
                <div class="relative w-full md:w-2/5 bg-gradient-to-br from-blue-600 via-blue-700 to-blue-900 text-white p-8 sm:p-10 flex flex-col justify-center overflow-hidden min-h-[220px] md:min-h-[520px]">
                    {{-- Decorative circles --}}
                    <div class="absolute -top-10 -left-10 w-40 h-40 rounded-full bg-white/10"></div>
                    <div class="absolute bottom-10 left-6 w-24 h-24 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-16 -left-16 w-48 h-48 rounded-full bg-white/10"></div>
                    <div class="absolute top-1/3 left-1/4 w-16 h-16 rounded-full bg-white/15"></div>

                    <div class="relative z-10">
                        <div class="w-16 h-16 rounded-2xl bg-white/15 flex items-center justify-center text-3xl mb-6">
                            🎓
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-bold mb-2">أهلاً بك في newMe</h1>
                        <p class="text-blue-100 font-semibold mb-4">نظام تقييم المعلمين</p>
                        <p class="text-sm text-blue-100/90 leading-relaxed max-w-xs">
                            منصة لمتابعة تقييم أداء المعلمين وإدارة تقارير المديريات والمشرفين في التعليم المدرسي.
                        </p>
                    </div>
                </div>

                {{-- Form panel --}}
                <div class="w-full md:w-3/5 p-8 sm:p-10 flex flex-col justify-center">
                    {{ $slot }}
                </div>

            </div>
        </div>
    </body>
</html>