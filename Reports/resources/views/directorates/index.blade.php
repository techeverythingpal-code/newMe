<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center" dir="rtl">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                🏢 إدارة المديريات
            </h2>
            <a href="{{ route('directorates.create') }}"
                class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-5 rounded-lg transition">
                + إضافة مديرية
            </a>
        </div>
    </x-slot>

    <div class="py-8" dir="rtl">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Success Message --}}
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    ✅ {{ session('success') }}
                </div>
            @endif
            {{-- Error Message --}}
@if (session('error'))
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
        ❌ {{ session('error') }}
    </div>
@endif

            {{-- Table --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl">
                <table class="w-full text-right text-sm">
                    <thead>
                        <tr class="bg-orange-50 text-orange-700 border-b border-orange-100">
                            <th class="px-6 py-4">#</th>
                            <th class="px-6 py-4">رقم المديرية</th>
                            <th class="px-6 py-4">اسم المديرية</th>
                            <th class="px-6 py-4">عدد المدارس</th>
                            <th class="px-6 py-4">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($directorates as $directorate)
                            <tr class="border-b border-gray-100 hover:bg-orange-50 transition">
                                <td class="px-6 py-4 text-gray-400">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 font-bold text-orange-600">{{ $directorate->Directorate_id }}</td>
                                <td class="px-6 py-4 font-medium text-gray-800">{{ $directorate->Directorate_Name }}</td>
                                <td class="px-6 py-4">
                                    <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-xs font-bold">
                                        {{ $directorate->schools_count }} مدرسة
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2 justify-end">
                                        {{-- Edit --}}
                                        <a href="{{ route('directorates.edit', $directorate->Directorate_id) }}"
                                            class="bg-blue-100 hover:bg-blue-200 text-blue-700 font-bold py-1 px-4 rounded-lg text-xs transition">
                                            ✏️ تعديل
                                        </a>
                                        {{-- Delete --}}
                                        <form action="{{ route('directorates.destroy', $directorate->Directorate_id) }}"
                                            method="POST"
                                            onsubmit="return confirm('هل أنت متأكد من حذف هذه المديرية؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-100 hover:bg-red-200 text-red-700 font-bold py-1 px-4 rounded-lg text-xs transition">
                                                🗑️ حذف
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-400">
                                    <div class="text-5xl mb-3">🏢</div>
                                    <div>لا توجد مديريات بعد</div>
                                    <a href="{{ route('directorates.create') }}"
                                        class="mt-3 inline-block bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-5 rounded-lg transition">
                                        + أضف أول مديرية
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Back to Dashboard --}}
            <div class="mt-4">
                <a href="{{ route('dashboard') }}"
                    class="text-gray-500 hover:text-gray-700 text-sm">
                    ← العودة للوحة التحكم
                </a>
            </div>

        </div>
    </div>
</x-app-layout>