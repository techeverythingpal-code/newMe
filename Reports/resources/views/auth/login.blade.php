<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">تسجيل الدخول</h2>
        <p class="text-sm text-gray-500 mt-1">أدخل بياناتك للوصول إلى لوحة التحكم</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Username --}}
        <div>
            <label for="login" class="block text-sm font-bold text-gray-700 mb-1.5">اسم المستخدم أو البريد الإلكتروني</label>
            <div class="relative">
                <span class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                </span>
                <input id="login" type="text" name="login" value="{{ old('login') }}" required autofocus autocomplete="username"
                    class="block w-full rounded-lg border-gray-300 pr-10 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <x-input-error :messages="$errors->get('login')" class="mt-1.5" />
        </div>

        {{-- Password --}}
        <div x-data="{ show: false }">
            <label for="password" class="block text-sm font-bold text-gray-700 mb-1.5">كلمة المرور</label>
            <div class="relative">
                <span class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                </span>
                <input :type="show ? 'text' : 'password'" id="password" name="password" required autocomplete="current-password"
                    class="block w-full rounded-lg border-gray-300 pr-10 pl-16 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500">
                <button type="button" @click="show = !show"
                    class="absolute inset-y-0 left-3 flex items-center text-xs font-bold text-blue-600 hover:text-blue-800">
                    <span x-text="show ? 'إخفاء' : 'إظهار'"></span>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        {{-- Remember + Forgot password --}}
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center gap-2 text-sm text-gray-600">
                <input id="remember_me" type="checkbox" name="remember"
                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                تذكرني
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-800 font-semibold">
                    نسيت كلمة المرور؟
                </a>
            @endif
        </div>

        <button type="submit"
            class="w-full bg-blue-800 hover:bg-blue-900 text-white font-bold py-3 rounded-lg transition">
            تسجيل الدخول
        </button>
    </form>
</x-guest-layout>