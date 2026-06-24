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


            {{-- Charts --}}
            @if($totalTeachers > 0)
            <div class="bg-white rounded-2xl p-5 shadow mb-8">
                <h3 class="text-right font-semibold text-gray-700 mb-4">درجات معلميك</h3>
                <canvas id="teacherScoresChart"></canvas>
            </div>

            <div class="bg-white rounded-2xl p-5 shadow mb-8">
                <h3 class="text-right font-semibold text-gray-700 mb-4">
                    نقاط القوة والضعف (متوسط % لكل معيار)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2">
                        <canvas id="radarChart"></canvas>
                    </div>
                    <div class="text-right text-xs text-gray-600 space-y-1 overflow-y-auto max-h-96">
                        @php
                            $criteriaText = [
                                1 => 'يوظف أسس المنهاج وخطوطه العريضة في العملية التعليمية',
                                2 => 'يتمكن من ربط الأهداف العامة للمنهاج مع أهداف المرحلة',
                                3 => 'يتمكن من المحتوى التعليمي ويعمل على إثرائه',
                                4 => 'يتمكن من الربط العمودي والأفقي للمحتوى التعليمي',
                                5 => 'يستخدم استراتيجيات تدريس متنوعة تتلاءم مع الموقف التعليمي',
                                6 => 'يعد الخطط ويطورها وفق أسس علمية',
                                7 => 'يوظف تكنولوجيا التعليم بأنواعها في العملية التعليمية',
                                8 => 'يوظف المنصات التعليمية الإلكترونية لتعزيز تعلم الطلبة',
                                9 => 'يوفر مناخاً تعليمياً آمناً وداعماً يمتاز بالمرونة والابتكار',
                                10 => 'يوظف القياس والتقويم التربوي بأنواعه',
                                11 => 'يكلف الطلبة بمهمات تعزز مهارات البحث العلمي',
                                12 => 'يتمكن من ربط المبحث بسياقات حياتية وتعليمية متنوعة',
                                13 => 'يوظف طرق التدريس بما ينسجم مع احتياجات الطلبة وقدراتهم',
                                14 => 'يدمج الطلبة ذوي الاحتياجات الخاصة في العملية التعليمية',
                                15 => 'يتمكن من أساسيات اللغة العربية والعلوم والرياضيات',
                                16 => 'يعمق معرفته بالمحتوى العلمي ويوجهها نحو زيادة الإلمام',
                                17 => 'يستثمر التغذية الراجعة في تطوير أدائه',
                                18 => 'يوظف مهارات الاتصال والتواصل في العملية التعليمية',
                                19 => 'يحرص على استمرارية نموه المهني ويعد أبحاثاً في تخصصه',
                                20 => 'يلتزم بالأنظمة والقوانين واللوائح التربوية',
                                21 => 'يشارك في اللجان المختلفة والأنشطة التربوية',
                                22 => 'يحرص على بناء علاقات إنسانية مع الأطراف ذات العلاقة',
                            ];
                        @endphp
                        @foreach($criteriaText as $num => $text)
                            <div><span class="font-bold text-purple-600">{{ $num }}.</span> {{ $text }}</div>
                        @endforeach
                    </div>
                </div>
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
                                    لا يوجد معلمون بعد
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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




</x-app-layout>