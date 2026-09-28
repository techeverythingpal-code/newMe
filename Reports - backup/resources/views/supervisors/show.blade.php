<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-right">
            📊 إحصائيات المشرف: {{ $supervisor->SuperVisor_Name }}
        </h2>
    </x-slot>

    <div class="py-6" dir="rtl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Info + Stats Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">

                <div class="bg-white rounded-2xl p-5 shadow text-center border-t-4 border-purple-500">
                    <div class="text-sm text-gray-500 mb-1">المديرية</div>
                    <div class="text-lg font-bold text-purple-600">
                        {{ $supervisor->directorate->Directorate_Name ?? '—' }}
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-5 text-white shadow-lg text-center">
                    <div class="text-3xl font-bold">{{ $totalTeachers }}</div>
                    <div class="text-sm opacity-80 mt-1">إجمالي المعلمين</div>
                </div>

                <div class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl p-5 text-white shadow-lg text-center">
                    <div class="text-3xl font-bold">{{ number_format($avgTotal, 1) }}</div>
                    <div class="text-sm opacity-80 mt-1">متوسط الدرجات</div>
                </div>

                <div class="bg-gradient-to-br from-amber-500 to-amber-700 rounded-2xl p-5 text-white shadow-lg text-center">
                    <div class="text-3xl font-bold">{{ $highestScore }}</div>
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
                            <th class="p-3">المجموع</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teachers as $teacher)
                            <tr class="border-b border-gray-100 hover:bg-purple-50 transition">
                                <td class="p-3 text-gray-400">{{ $loop->iteration }}</td>
                                <td class="p-3 font-medium text-gray-800">{{ $teacher->Teacher_Name }}</td>
                                <td class="p-3">
                                    <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-bold">
                                        {{ $teacher->grades->total ?? 0 }} / 100
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="p-5 text-center text-gray-400">لا يوجد معلمون بعد</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <a href="{{ route('supervisors.index') }}" class="text-gray-500 hover:text-gray-700 text-sm">
                    ← العودة لقائمة المشرفين
                </a>
            </div>

        </div>
    </div>

    @if($totalTeachers > 0)
   


    @endif

</x-app-layout>