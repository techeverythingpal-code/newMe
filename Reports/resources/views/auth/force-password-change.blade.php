<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">تغيير كلمة المرور</h2>
        <p class="text-sm text-gray-500 mt-1">
            كلمة المرور الحالية مؤقتة. يرجى اختيار كلمة مرور جديدة للمتابعة.
        </p>
    </div>

    <form method="POST" action="{{ route('password.force.update') }}" class="space-y-5">
        @csrf
        @method('PUT')

        {{-- Current (temporary) password --}}
        <div>
            <label for="current_password" class="block text-sm font-bold text-gray-700 mb-1.5">كلمة المرور الحالية</label>
            <input id="current_password" type="password" name="current_password" required autofocus autocomplete="current-password"
                class="block w-full rounded-lg border-gray-300 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500">
            <x-input-error :messages="$errors->get('current_password')" class="mt-1.5" />
        </div>

        {{-- New password --}}
        <div>
            <label for="password" class="block text-sm font-bold text-gray-700 mb-1.5">كلمة المرور الجديدة</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                class="block w-full rounded-lg border-gray-300 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500">
            <p class="text-xs text-gray-500 mt-1">8 أحرف على الأقل، ويجب أن تختلف عن كلمة المرور الحالية.</p>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        {{-- Confirm --}}
        <div>
            <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-1.5">تأكيد كلمة المرور الجديدة</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                class="block w-full rounded-lg border-gray-300 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
        </div>

        <button type="submit"
            class="w-full bg-blue-800 hover:bg-blue-900 text-white font-bold py-3 rounded-lg transition">
            حفظ كلمة المرور الجديدة
        </button>
    </form>

    {{-- Escape hatch: log out without changing --}}
    <form method="POST" action="{{ route('logout') }}" class="mt-4 text-center">
        @csrf
        <button type="submit" class="text-sm text-gray-500 hover:text-gray-700 underline">
            تسجيل الخروج
        </button>
    </form>
</x-guest-layout>