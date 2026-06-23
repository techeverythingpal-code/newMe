<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center" dir="rtl">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                🏫 إدارة المدارس
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('schools.export') }}"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2 px-5 rounded-lg transition">
                    ⬇️ تصدير Excel
                </a>
                <button type="button" onclick="document.getElementById('schools-import-modal').classList.remove('hidden')"
                    class="bg-amber-100 hover:bg-amber-200 text-amber-700 font-bold py-2 px-5 rounded-lg transition">
                    ⬆️ استيراد Excel
                </button>
                <a href="{{ route('schools.create') }}"
                    class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-2 px-5 rounded-lg transition">
                    + إضافة مدرسة
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Import Modal -->
    <div id="schools-import-modal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50" dir="rtl">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl">
            <h3 class="font-bold text-lg mb-3">⬆️ استيراد المدارس من Excel</h3>
            <p class="text-sm text-gray-500 mb-4">
                الأعمدة المطلوبة: <span class="font-mono">School_ID, SchoolName, directorate_id</span>
            </p>
            <form action="{{ route('schools.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                    class="w-full border border-gray-300 rounded-lg p-2 mb-4 text-sm">
                <div class="flex gap-2 justify-end">
                    <button type="button" onclick="document.getElementById('schools-import-modal').classList.add('hidden')"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded-lg transition">
                        إلغاء
                    </button>
                    <button type="submit"
                        class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-2 px-4 rounded-lg transition">
                        استيراد
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="py-8" dir="rtl">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    ❌ {{ session('error') }}
                </div>
            @endif
            @if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif


            <div class="bg-white overflow-hidden shadow-sm rounded-2xl">
                <table class="w-full table-fixed text-right text-sm" id="schoolsTable">
                    <thead>
                        <tr class="bg-emerald-50 text-emerald-700">
                            <th class="px-4 py-3 w-10 text-xs font-semibold">#</th>
                            <th class="px-4 py-3 w-24 text-xs font-semibold">رقم المدرسة</th>
                            <th class="px-4 py-3 text-xs font-semibold">اسم المدرسة</th>
                            <th class="px-4 py-3 w-36 text-xs font-semibold">المديرية</th>
                            <th class="px-4 py-3 w-28 text-xs font-semibold">عدد المعلمين</th>
                            <th class="px-4 py-3 w-36 text-xs font-semibold">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($schools as $school)
                            <tr class="odd:bg-white even:bg-gray-50/60 hover:bg-emerald-50 transition-colors">
                                <td class="px-4 py-3 align-middle text-gray-400">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 align-middle font-bold text-emerald-600 whitespace-nowrap">{{ $school->School_ID }}</td>
                                <td class="px-4 py-3 align-middle font-medium text-gray-800 overflow-hidden text-ellipsis whitespace-nowrap" title="{{ $school->SchoolName }}">
                                    {{ $school->SchoolName }}
                                </td>
                                <td class="px-4 py-3 align-middle text-gray-600 overflow-hidden text-ellipsis whitespace-nowrap">{{ $school->directorate->Directorate_Name ?? '-' }}</td>
                                <td class="px-4 py-3 align-middle">
                                    <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold whitespace-nowrap">
                                        {{ $school->teachers()->count() }} معلم
                                    </span>
                                </td>
                                <td class="px-4 py-3 align-middle">
                                    <div class="flex gap-2 justify-end">
                                        <a href="{{ route('schools.edit', $school->School_ID) }}"
                                            class="bg-blue-100 hover:bg-blue-200 text-blue-700 font-bold py-1.5 px-3 rounded-lg text-xs transition whitespace-nowrap">
                                            ✏️ تعديل
                                        </a>
                                        <form action="{{ route('schools.destroy', $school->School_ID) }}"
                                            method="POST"
                                            onsubmit="return confirm('هل أنت متأكد من حذف هذه المدرسة؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-100 hover:bg-red-200 text-red-700 font-bold py-1.5 px-3 rounded-lg text-xs transition whitespace-nowrap">
                                                🗑️ حذف
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                                    <div class="text-5xl mb-3">🏫</div>
                                    <div>لا توجد مدارس بعد</div>
                                    <a href="{{ route('schools.create') }}"
                                        class="mt-3 inline-block bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-2 px-5 rounded-lg transition">
                                        + أضف أول مدرسة
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700 text-sm">
                    ← العودة للوحة التحكم
                </a>
            </div>

        </div>
    </div>
</x-app-layout>