<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center" dir="rtl">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                🏢 بيانات المديرية: {{ $directorate->Directorate_Name }}
            </h2>
            <a href="{{ route('directorates.edit', $directorate->Directorate_id) }}"
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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gradient-to-br from-orange-500 to-orange-700 rounded-2xl p-5 text-white shadow-lg text-center">
                    <div class="text-3xl font-bold">{{ $schools->count() }}</div>
                    <div class="text-sm opacity-80 mt-1">عدد المدارس</div>
                </div>
                <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl p-5 text-white shadow-lg text-center">
                    <div class="text-3xl font-bold">{{ $supervisors->count() }}</div>
                    <div class="text-sm opacity-80 mt-1">عدد المشرفين</div>
                </div>
                <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-5 text-white shadow-lg text-center">
                    <div class="text-3xl font-bold">{{ $totalTeachers }}</div>
                    <div class="text-sm opacity-80 mt-1">عدد المعلمين</div>
                </div>
            </div>

            {{-- Directorate Info Card --}}
            <div class="bg-white shadow-sm rounded-2xl p-6">
                <h3 class="text-lg font-bold text-orange-700 mb-4 border-b pb-2">📋 البيانات الأساسية</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">رقم المديرية:</span>
                        <span class="font-bold text-gray-800 mr-2">{{ $directorate->Directorate_id }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">اسم المديرية:</span>
                        <span class="font-bold text-gray-800 mr-2">{{ $directorate->Directorate_Name }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">مدير المديرية:</span>
                        <span class="font-bold text-gray-800 mr-2">{{ $directorate->manager_name ?: '—' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">العنوان:</span>
                        <span class="font-bold text-gray-800 mr-2">{{ $directorate->address ?: '—' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">الهاتف:</span>
                        @if ($directorate->phone)
                            <a href="tel:{{ $directorate->phone }}" dir="ltr"
                                class="font-bold text-blue-600 hover:underline mr-2">{{ $directorate->phone }}</a>
                        @else
                            <span class="font-bold text-gray-800 mr-2">—</span>
                        @endif
                    </div>
                    <div>
                        <span class="text-gray-500">البريد الإلكتروني:</span>
                        @if ($directorate->email)
                            <a href="mailto:{{ $directorate->email }}" dir="ltr"
                                class="font-bold text-blue-600 hover:underline mr-2">{{ $directorate->email }}</a>
                        @else
                            <span class="font-bold text-gray-800 mr-2">—</span>
                        @endif
                    </div>
                </div>

                @if ($directorate->notes)
                    <div class="mt-4 pt-4 border-t text-sm">
                        <div class="text-gray-500 mb-1">معلومات / ملاحظات:</div>
                        <div class="text-gray-800 whitespace-pre-line">{{ $directorate->notes }}</div>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Supervisors --}}
                <div class="bg-white shadow-sm rounded-2xl p-5">
                    <h3 class="font-semibold text-purple-700 mb-4">👤 المشرفون</h3>
                    <table class="w-full text-right text-sm">
                        <thead>
                            <tr class="bg-purple-50 text-purple-700 border-b border-purple-100">
                                <th class="p-3">#</th>
                                <th class="p-3">اسم المشرف</th>
                                <th class="p-3">التخصص</th>
                                <th class="p-3">المعلمون</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($supervisors as $supervisor)
                                <tr class="border-b border-gray-100 hover:bg-purple-50 transition">
                                    <td class="p-3 text-gray-400">{{ $loop->iteration }}</td>
                                    <td class="p-3 font-medium">
                                        <a href="{{ route('supervisors.show', $supervisor->SuperVisor_id) }}"
                                            class="text-purple-700 hover:underline">
                                            {{ $supervisor->SuperVisor_Name }}
                                        </a>
                                    </td>
                                    <td class="p-3 text-gray-600">{{ $supervisor->SuperVisor_Major }}</td>
                                    <td class="p-3">
                                        <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-bold">
                                            {{ $supervisor->teachers_count }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-5 text-center text-gray-400">لا يوجد مشرفون في هذه المديرية</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Schools --}}
                <div class="bg-white shadow-sm rounded-2xl p-5">
                    <h3 class="font-semibold text-orange-700 mb-4">🏫 المدارس</h3>
                    <table class="w-full text-right text-sm">
                        <thead>
                            <tr class="bg-orange-50 text-orange-700 border-b border-orange-100">
                                <th class="p-3">#</th>
                                <th class="p-3">رقم المدرسة</th>
                                <th class="p-3">اسم المدرسة</th>
                                <th class="p-3">المعلمون</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($schools as $school)
                                <tr class="border-b border-gray-100 hover:bg-orange-50 transition">
                                    <td class="p-3 text-gray-400">{{ $loop->iteration }}</td>
                                    <td class="p-3 text-orange-600 font-bold">{{ $school->School_ID }}</td>
                                    <td class="p-3 font-medium">
                                        {{-- Clickable school name → school details page --}}
                                        <a href="{{ route('schools.show', $school->School_ID) }}"
                                            class="text-emerald-700 hover:underline">
                                            {{ $school->SchoolName }}
                                        </a>
                                    </td>
                                    <td class="p-3">
                                        <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-xs font-bold">
                                            {{ $school->teachers_count }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-5 text-center text-gray-400">لا توجد مدارس في هذه المديرية</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

            <div>
                <a href="{{ route('directorates.index') }}" class="text-gray-500 hover:text-gray-700 text-sm">
                    ← العودة لقائمة المديريات
                </a>
            </div>

        </div>
    </div>
</x-app-layout>