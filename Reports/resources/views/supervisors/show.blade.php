<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center" dir="rtl">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                👤 بيانات المشرف: {{ $supervisor->SuperVisor_Name }}
            </h2>
            <a href="{{ route('supervisors.edit', $supervisor->SuperVisor_id) }}"
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-5 rounded-lg transition">
                ✏️ تعديل البيانات
            </a>
        </div>
    </x-slot>

    <div class="py-6" dir="rtl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Supervisor Info Card --}}
            <div class="bg-white shadow-sm rounded-2xl p-6">
                <h3 class="text-lg font-bold text-purple-700 mb-4 border-b pb-2">📋 البيانات الأساسية</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">رقم المشرف:</span>
                        <span class="font-bold text-gray-800 mr-2">{{ $supervisor->SuperVisor_id }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">اسم المشرف:</span>
                        <span class="font-bold text-gray-800 mr-2">{{ $supervisor->SuperVisor_Name }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">التخصص:</span>
                        <span class="font-bold text-gray-800 mr-2">{{ $supervisor->SuperVisor_Major ?: '—' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">المديرية:</span>
                        {{-- Clickable directorate → directorate details page --}}
                        @if ($supervisor->directorate)
                            <a href="{{ route('directorates.show', $supervisor->directorate->Directorate_id) }}"
                                class="font-bold text-orange-600 hover:underline mr-2">
                                {{ $supervisor->directorate->Directorate_Name }}
                            </a>
                        @else
                            <span class="font-bold text-gray-800 mr-2">—</span>
                        @endif
                    </div>
                    <div>
                        <span class="text-gray-500">حالة كلمة المرور:</span>
                        @if ($supervisor->must_change_password)
                            <span class="bg-amber-100 text-amber-700 px-3 py-0.5 rounded-full text-xs font-bold mr-2">
                                مؤقتة — لم يغيّرها بعد
                            </span>
                        @else
                            <span class="bg-green-100 text-green-700 px-3 py-0.5 rounded-full text-xs font-bold mr-2">
                                تم تعيينها
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-5 text-white shadow-lg text-center">
                    <div class="text-3xl font-bold">{{ $totalTeachers }}</div>
                    <div class="text-sm opacity-80 mt-1">إجمالي المعلمين</div>
                </div>
                <div class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl p-5 text-white shadow-lg text-center">
                    <div class="text-3xl font-bold">{{ number_format($avgTotal ?? 0, 1) }}</div>
                    <div class="text-sm opacity-80 mt-1">متوسط الدرجات</div>
                </div>
                <div class="bg-gradient-to-br from-amber-500 to-amber-700 rounded-2xl p-5 text-white shadow-lg text-center">
                    <div class="text-3xl font-bold">{{ $highestScore ?? 0 }}</div>
                    <div class="text-sm opacity-80 mt-1">أعلى درجة</div>
                </div>
            </div>

            {{-- Teachers Table --}}
            <div class="bg-white rounded-2xl shadow p-5">
                <h3 class="text-right font-semibold text-gray-700 mb-4">قائمة المعلمين</h3>
                <table class="w-full text-right text-sm">
                    <thead>
                        <tr class="bg-purple-50 text-purple-700 border-b border-purple-100">
                            <th class="p-3">#</th>
                            <th class="p-3">اسم المعلم</th>
                            <th class="p-3">التخصص</th>
                            <th class="p-3">المجموع</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teachers as $teacher)
                            <tr class="border-b border-gray-100 hover:bg-purple-50 transition">
                                <td class="p-3 text-gray-400">{{ $loop->iteration }}</td>
                                <td class="p-3 font-medium">
                                    {{-- Clickable name → teacher details page --}}
                                    <a href="{{ route('teachers.show', $teacher->Teacher_id) }}"
                                        class="text-gray-800 hover:text-blue-600 hover:underline">
                                        {{ $teacher->Teacher_Name }}
                                    </a>
                                </td>
                                <td class="p-3 text-gray-600">{{ $teacher->teacher_major }}</td>
                                <td class="p-3">
                                    <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-bold">
                                        {{ $teacher->grades->total ?? 0 }} / 100
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-5 text-center text-gray-400">لا يوجد معلمون بعد</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>
                <a href="{{ route('supervisors.index') }}" class="text-gray-500 hover:text-gray-700 text-sm">
                    ← العودة لقائمة المشرفين
                </a>
            </div>

        </div>
    </div>
</x-app-layout>