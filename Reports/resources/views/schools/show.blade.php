<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center" dir="rtl">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                🏫 بيانات المدرسة: {{ $school->SchoolName }}
            </h2>
            <a href="{{ route('schools.edit', $school->School_ID) }}"
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-5 rounded-lg transition">
                ✏️ تعديل البيانات
            </a>
        </div>
    </x-slot>

    <div class="py-8" dir="rtl">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Success Message --}}
            @if (session('success'))
                <div class="p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    ✅ {{ session('success') }}
                </div>
            @endif

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl p-5 text-white shadow-lg text-center">
                    <div class="text-3xl font-bold">{{ $teachers->count() }}</div>
                    <div class="text-sm opacity-80 mt-1">عدد المعلمين</div>
                </div>
                <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl p-5 text-white shadow-lg text-center">
                    <div class="text-3xl font-bold">{{ $supervisorsCount }}</div>
                    <div class="text-sm opacity-80 mt-1">عدد المشرفين</div>
                </div>
            </div>

            {{-- School Info Card --}}
            <div class="bg-white shadow-sm rounded-2xl p-6">
                <h3 class="text-lg font-bold text-emerald-700 mb-4 border-b pb-2">📋 البيانات الأساسية</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">رقم المدرسة:</span>
                        <span class="font-bold text-gray-800 mr-2">{{ $school->School_ID }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">اسم المدرسة:</span>
                        <span class="font-bold text-gray-800 mr-2">{{ $school->SchoolName }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">المديرية:</span>
                        @if ($school->directorate)
                            <a href="{{ route('directorates.show', $school->directorate->Directorate_id) }}"
                                class="font-bold text-orange-600 hover:underline mr-2">
                                {{ $school->directorate->Directorate_Name }}
                            </a>
                        @else
                            <span class="font-bold text-gray-800 mr-2">—</span>
                        @endif
                    </div>
                    <div>
                        <span class="text-gray-500">مدير المدرسة:</span>
                        <span class="font-bold text-gray-800 mr-2">{{ $school->principal_name ?: '—' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">العنوان:</span>
                        <span class="font-bold text-gray-800 mr-2">{{ $school->address ?: '—' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">الهاتف:</span>
                        @if ($school->phone)
                            <a href="tel:{{ $school->phone }}" dir="ltr"
                                class="font-bold text-blue-600 hover:underline mr-2">{{ $school->phone }}</a>
                        @else
                            <span class="font-bold text-gray-800 mr-2">—</span>
                        @endif
                    </div>
                    <div>
                        <span class="text-gray-500">البريد الإلكتروني:</span>
                        @if ($school->email)
                            <a href="mailto:{{ $school->email }}" dir="ltr"
                                class="font-bold text-blue-600 hover:underline mr-2">{{ $school->email }}</a>
                        @else
                            <span class="font-bold text-gray-800 mr-2">—</span>
                        @endif
                    </div>
                </div>

                @if ($school->notes)
                    <div class="mt-4 pt-4 border-t text-sm">
                        <div class="text-gray-500 mb-1">معلومات / ملاحظات:</div>
                        <div class="text-gray-800 whitespace-pre-line">{{ $school->notes }}</div>
                    </div>
                @endif
            </div>

            {{-- Teachers --}}
            <div class="bg-white shadow-sm rounded-2xl p-5">
                <h3 class="font-semibold text-emerald-700 mb-4">👩‍🏫 المعلمون</h3>
                <table class="w-full text-right text-sm">
                    <thead>
                        <tr class="bg-emerald-50 text-emerald-700 border-b border-emerald-100">
                            <th class="p-3">#</th>
                            <th class="p-3">رقم المعلم</th>
                            <th class="p-3">اسم المعلم</th>
                            <th class="p-3">التخصص</th>
                            <th class="p-3">المشرف</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($teachers as $teacher)
                            <tr class="border-b border-gray-100 hover:bg-emerald-50 transition">
                                <td class="p-3 text-gray-400">{{ $loop->iteration }}</td>
                                <td class="p-3 text-emerald-600 font-bold">{{ $teacher->Teacher_id }}</td>
                                <td class="p-3 font-medium">
                                    <a href="{{ route('teachers.show', $teacher->Teacher_id) }}"
                                        class="text-gray-800 hover:text-emerald-700 hover:underline">
                                        {{ $teacher->Teacher_Name }}
                                    </a>
                                </td>
                                <td class="p-3 text-gray-600">{{ $teacher->teacher_major ?: '—' }}</td>
                                <td class="p-3">
                                    @if ($teacher->supervisor)
                                        <a href="{{ route('supervisors.show', $teacher->supervisor->SuperVisor_id) }}"
                                            class="text-purple-700 hover:underline">
                                            {{ $teacher->supervisor->SuperVisor_Name }}
                                        </a>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-5 text-center text-gray-400">لا يوجد معلمون في هذه المدرسة</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>
                <a href="{{ route('schools.index') }}" class="text-gray-500 hover:text-gray-700 text-sm">
                    ← العودة لقائمة المدارس
                </a>
            </div>

        </div>
    </div>
</x-app-layout>