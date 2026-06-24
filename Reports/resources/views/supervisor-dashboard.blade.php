<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-right">
            لوحة التحكم - {{ auth()->user()->SuperVisor_Name }}
        </h2>
    </x-slot>

    <div class="py-6" dir="rtl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Stats Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">

                <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-5 text-white shadow-lg">
                    <div class="text-4xl mb-2">👨‍🏫</div>
                    <div class="text-3xl font-bold">{{ $totalTeachers }}</div>
                    <div class="text-sm opacity-80 mt-1">إجمالي المعلمين</div>
                </div>

                <div class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl p-5 text-white shadow-lg">
                    <div class="text-4xl mb-2">📊</div>
                    <div class="text-3xl font-bold">{{ number_format($avgTotal, 1) }}</div>
                    <div class="text-sm opacity-80 mt-1">متوسط الدرجات</div>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl p-5 text-white shadow-lg">
                    <div class="text-4xl mb-2">🏆</div>
                    <div class="text-3xl font-bold">{{ $highestScore }}</div>
                    <div class="text-sm opacity-80 mt-1">أعلى درجة</div>
                </div>

            </div>

            {{-- Chart (always shows ALL teachers, unaffected by filters below) --}}
            @if($totalTeachers > 0)
            <div class="bg-white rounded-2xl p-5 shadow mb-8">
                <h3 class="text-right font-semibold text-gray-700 mb-4">درجات معلميك</h3>
                <canvas id="teacherScoresChart"></canvas>
            </div>
            @endif

            {{-- My Teachers Table --}}
            <div class="bg-white rounded-2xl shadow p-5">
                <div class="flex justify-between items-center mb-4">
                    <a href="{{ route('teachers.create') }}"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg text-sm transition">
                        + إضافة معلم
                    </a>
                    <h3 class="font-semibold text-gray-700">معلمون</h3>
                </div>

                {{-- Filters (live: auto-submits as you type/select) --}}
                <form id="filterForm" method="GET" action="{{ route('dashboard') }}" class="grid grid-cols-1 md:grid-cols-5 gap-3 mb-5">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="بحث (اسم، تخصص، مؤهل...)"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm md:col-span-2">

                    <select name="school_id" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="">كل المدارس</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->School_ID }}" @selected(request('school_id') == $school->School_ID)>
                                {{ $school->SchoolName }}
                            </option>
                        @endforeach
                    </select>

                    <input type="number" name="min_score" value="{{ request('min_score') }}"
                        placeholder="من (المجموع)"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm">

                    <input type="number" name="max_score" value="{{ request('max_score') }}"
                        placeholder="إلى (المجموع)"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm">

                    <div class="md:col-span-5 flex gap-2 justify-end">
                        <a href="{{ route('dashboard') }}"
                            class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded-lg text-sm transition">
                            إعادة تعيين
                        </a>
                    </div>
                </form>

                <table class="w-full text-right text-sm">
                    <thead>
                        <tr class="bg-blue-50 text-blue-700 border-b border-blue-100">
                            <th class="p-3">#</th>
                            <th class="p-3">اسم المعلم</th>
                            <th class="p-3">المدرسة</th>
                            <th class="p-3">التخصص</th>
                            <th class="p-3">المجموع</th>
                            <th class="p-3">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teachers as $teacher)
                            <tr class="border-b border-gray-100 hover:bg-blue-50 transition">
                                <td class="p-3 text-gray-400">{{ $loop->iteration }}</td>
                                <td class="p-3 font-medium text-gray-800">{{ $teacher->Teacher_Name }}</td>
                                <td class="p-3 text-gray-600">{{ $teacher->school->SchoolName ?? '-' }}</td>
                                <td class="p-3 text-gray-600">{{ $teacher->teacher_major }}</td>
                                <td class="p-3">
                                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">
                                        {{ $teacher->grades->total ?? 0 }} / 100
                                    </span>
                                </td>
                                <td class="p-3">
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
                                            onsubmit="return confirm('هل أنت متأكد؟')">
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
                                <td colspan="6" class="p-5 text-center text-gray-400">
                                    لا يوجد معلمون مطابقون لهذا البحث
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $teachers->links() }}
                </div>
            </div>

        </div>
    </div>

    @if($totalTeachers > 0)
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        new Chart(document.getElementById('teacherScoresChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'المجموع',
                    data: {!! json_encode($chartData) !!},
                    backgroundColor: '#3b82f6',
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, max: 100 } }
            }
        });
    </script>
    @endif

    <script>
        const filterForm = document.getElementById('filterForm');
        let debounceTimer;

        filterForm.querySelectorAll('input[type="text"], input[type="number"]').forEach(input => {
            input.addEventListener('input', () => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => filterForm.submit(), 500);
            });
        });

        filterForm.querySelectorAll('select').forEach(select => {
            select.addEventListener('change', () => filterForm.submit());
        });
    </script>
</x-app-layout>