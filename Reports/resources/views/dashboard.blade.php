<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-right">
            لوحة التحكم
        </h2>
    </x-slot>

    <div class="py-6" dir="rtl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Count Cards --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">

    <a href="#" class="block bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-5 text-white shadow-lg hover:scale-105 transition-transform duration-200">
        <div class="text-4xl mb-2">👨‍🏫</div>
        <div class="text-3xl font-bold">{{ $totalTeachers }}</div>
        <div class="text-sm opacity-80 mt-1">إجمالي المعلمين</div>
    </a>

    <a href="{{ route('schools.index') }}" class="block bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl p-5 text-white shadow-lg hover:scale-105 transition-transform duration-200">
        <div class="text-4xl mb-2">🏫</div>
        <div class="text-3xl font-bold">{{ $totalSchools }}</div>
        <div class="text-sm opacity-80 mt-1">المدارس</div>
    </a>

    <a href="{{ route('supervisors.index') }}" class="block bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl p-5 text-white shadow-lg hover:scale-105 transition-transform duration-200">
        <div class="text-4xl mb-2">👤</div>
        <div class="text-3xl font-bold">{{ $totalSupervisors }}</div>
        <div class="text-sm opacity-80 mt-1">المشرفون</div>
    </a>

    <a href="{{ route('directorates.index') }}" class="block bg-gradient-to-br from-orange-500 to-orange-700 rounded-2xl p-5 text-white shadow-lg hover:scale-105 transition-transform duration-200">
        <div class="text-4xl mb-2">🏢</div>
        <div class="text-3xl font-bold">{{ $totalDirectorates }}</div>
        <div class="text-sm opacity-80 mt-1">المديريات</div>
    </a>

</div>

            {{-- Grades Summary --}}
            <div class="grid grid-cols-3 gap-4 mb-8">

                <div class="bg-white rounded-2xl p-5 shadow text-center border-t-4 border-blue-500">
                    <div class="text-2xl font-bold text-blue-600">{{ number_format($avgTotal, 1) }}</div>
                    <div class="text-sm text-gray-500 mt-1">متوسط الدرجات</div>
                </div>

                <div class="bg-white rounded-2xl p-5 shadow text-center border-t-4 border-emerald-500">
                    <div class="text-2xl font-bold text-emerald-600">{{ $highestScore }}</div>
                    <div class="text-sm text-gray-500 mt-1">أعلى درجة</div>
                </div>

                <div class="bg-white rounded-2xl p-5 shadow text-center border-t-4 border-red-500">
                    <div class="text-2xl font-bold text-red-500">{{ $lowestScore }}</div>
                    <div class="text-sm text-gray-500 mt-1">أدنى درجة</div>
                </div>

            </div>

            {{-- Charts Row --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

                <div class="bg-white rounded-2xl p-5 shadow">
                    <h3 class="text-right font-semibold text-gray-700 mb-4">المعلمون لكل مدرسة</h3>
                    <canvas id="schoolChart"></canvas>
                </div>

                <div class="bg-white rounded-2xl p-5 shadow">
                    <h3 class="text-right font-semibold text-gray-700 mb-4">المعلمون لكل مشرف</h3>
                    <canvas id="supervisorChart"></canvas>
                </div>

            </div>

            {{-- Recent Teachers Table --}}
            <div class="bg-white rounded-2xl shadow p-5">
                <h3 class="text-right font-semibold text-gray-700 mb-4">آخر المعلمين المضافين</h3>
                <table class="w-full text-right text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500">
                            <th class="p-3 rounded-r-lg">اسم المعلم</th>
                            <th class="p-3">المدرسة</th>
                            <th class="p-3">المشرف</th>
                            <th class="p-3">التخصص</th>
                            <th class="p-3 rounded-l-lg">تاريخ التعيين</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTeachers as $teacher)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="p-3 font-medium text-gray-800">{{ $teacher->Teacher_Name }}</td>
                            <td class="p-3 text-gray-600">{{ $teacher->school->SchoolName ?? '-' }}</td>
                            <td class="p-3 text-gray-600">{{ $teacher->supervisor->SuperVisor_Name ?? '-' }}</td>
                            <td class="p-3 text-gray-600">{{ $teacher->teacher_major }}</td>
                            <td class="p-3 text-gray-600">{{ $teacher->date }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-5 text-center text-gray-400">لا يوجد معلمون بعد</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Teachers per School Chart
        new Chart(document.getElementById('schoolChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($teachersPerSchool->pluck('SchoolName')) !!},
                datasets: [{
                    label: 'عدد المعلمين',
                    data: {!! json_encode($teachersPerSchool->pluck('teachers_count')) !!},
                    backgroundColor: [
                        '#3b82f6','#10b981','#8b5cf6','#f59e0b','#ef4444'
                    ],
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });

        // Teachers per Supervisor Chart
        new Chart(document.getElementById('supervisorChart'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($teachersPerSupervisor->pluck('SuperVisor_Name')) !!},
                datasets: [{
                    data: {!! json_encode($teachersPerSupervisor->pluck('teachers_count')) !!},
                    backgroundColor: [
                        '#3b82f6','#10b981','#8b5cf6','#f59e0b','#ef4444'
                    ],
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    </script>

</x-app-layout>