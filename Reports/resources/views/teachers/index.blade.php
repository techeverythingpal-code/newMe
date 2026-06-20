<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex gap-2">
                <a href="{{ route('teachers.export') }}"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2 px-5 rounded-lg transition">
                    ⬇️ تصدير Excel
                </a>
                <button type="button" onclick="document.getElementById('teachers-import-modal').classList.remove('hidden')"
                    class="bg-amber-100 hover:bg-amber-200 text-amber-700 font-bold py-2 px-5 rounded-lg transition">
                    ⬆️ استيراد Excel
                </button>
                <a href="{{ route('teachers.create') }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-5 rounded-lg transition shadow">
                    + إضافة معلم
                </a>
            </div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                👨‍🏫 إدارة المعلمين
            </h2>
        </div>
    </x-slot>

    <!-- Import Modal -->
    <div id="teachers-import-modal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50" dir="rtl">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl">
            <h3 class="font-bold text-lg mb-3">⬆️ استيراد المعلمين من Excel</h3>
            <p class="text-sm text-gray-500 mb-4">
                الأعمدة المطلوبة: <span class="font-mono">Teacher_id, Teacher_Name, school_id, supervisor_id, date, teacher_qualify, teacher_major</span>
            </p>
            <form action="{{ route('teachers.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                    class="w-full border border-gray-300 rounded-lg p-2 mb-4 text-sm">
                <div class="flex gap-2 justify-end">
                    <button type="button" onclick="document.getElementById('teachers-import-modal').classList.add('hidden')"
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
                <table class="w-full text-right text-sm">
                    <thead>
                        <tr class="bg-blue-50 text-blue-700 border-b border-blue-100">
                            <th class="px-6 py-4">#</th>
                            <th class="px-6 py-4">رقم المعلم</th>
                            <th class="px-6 py-4">اسم المعلم</th>
                            <th class="px-6 py-4">المدرسة</th>
                            <th class="px-6 py-4">المشرف</th>
                            <th class="px-6 py-4">التخصص</th>
                            <th class="px-6 py-4">المؤهل</th>
                            <th class="px-6 py-4">تاريخ التعيين</th>
                            <th class="px-6 py-4">المجموع</th>
                            <th class="px-6 py-4">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teachers as $teacher)
                            <tr class="border-b border-gray-100 hover:bg-blue-50 transition">
                                <td class="px-6 py-4 text-gray-400">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 font-bold text-blue-600">{{ $teacher->Teacher_id }}</td>
                                <td class="px-6 py-4 font-medium text-gray-800">{{ $teacher->Teacher_Name }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $teacher->school->SchoolName ?? '-' }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $teacher->supervisor->SuperVisor_Name ?? '-' }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $teacher->teacher_major }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $teacher->teacher_qualify }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $teacher->date }}</td>
                                <td class="px-6 py-4">
                                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">
                                        {{ $teacher->grades->total ?? 0 }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2 justify-end">
                                        <a href="{{ route('teachers.show', $teacher->Teacher_id) }}"
                                            class="bg-green-100 hover:bg-green-200 text-green-700 font-bold py-1 px-3 rounded-lg text-xs transition">
                                            👁️ عرض
                                        </a>
                                        <a href="{{ route('teachers.edit', $teacher->Teacher_id) }}"
                                            class="bg-blue-100 hover:bg-blue-200 text-blue-700 font-bold py-1 px-3 rounded-lg text-xs transition">
                                            ✏️ تعديل
                                        </a>
                                        <form action="{{ route('teachers.destroy', $teacher->Teacher_id) }}"
                                            method="POST"
                                            onsubmit="return confirm('هل أنت متأكد من حذف هذا المعلم؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-100 hover:bg-red-200 text-red-700 font-bold py-1 px-3 rounded-lg text-xs transition">
                                                🗑️ حذف
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-6 py-10 text-center text-gray-400">
                                    <div class="text-5xl mb-3">👨‍🏫</div>
                                    <div>لا يوجد معلمون بعد</div>
                                    <a href="{{ route('teachers.create') }}"
                                        class="mt-3 inline-block bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-5 rounded-lg transition">
                                        + أضف أول معلم
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