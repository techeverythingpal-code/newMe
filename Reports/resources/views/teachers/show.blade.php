<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <a href="{{ route('teachers.index') }}"
                class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-5 rounded-lg transition">
                ← العودة
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                👨‍🏫 بيانات المعلم
            </h2>
        </div>
    </x-slot>

    <div class="py-8" dir="rtl">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Teacher Info Card --}}
            <div class="bg-white shadow-sm rounded-2xl p-6">
                <h3 class="text-lg font-bold text-blue-700 mb-4 border-b pb-2">📋 البيانات الأساسية</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">رقم المعلم:</span>
                        <span class="font-bold text-gray-800 mr-2">{{ $teacher->Teacher_id }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">اسم المعلم:</span>
                        <span class="font-bold text-gray-800 mr-2">{{ $teacher->Teacher_Name }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">المدرسة:</span>
                        <span class="font-bold text-gray-800 mr-2">{{ $teacher->school->SchoolName ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">المشرف:</span>
                        <span class="font-bold text-gray-800 mr-2">{{ $teacher->supervisor->SuperVisor_Name ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">التخصص:</span>
                        <span class="font-bold text-gray-800 mr-2">{{ $teacher->teacher_major }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">المؤهل:</span>
                        <span class="font-bold text-gray-800 mr-2">{{ $teacher->teacher_qualify }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">تاريخ التعيين:</span>
                        <span class="font-bold text-gray-800 mr-2">{{ $teacher->date }}</span>
                    </div>
                </div>
            </div>

            {{-- Grades Card --}}
            @if($teacher->grades)
            <div class="bg-white shadow-sm rounded-2xl p-6">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <span class="bg-blue-100 text-blue-700 px-4 py-1 rounded-full font-bold">
                        المجموع: {{ $teacher->grades->total }} / 100
                    </span>
                    <h3 class="text-lg font-bold text-blue-700">📊 الدرجات التفصيلية</h3>
                </div>

                <div class="grid grid-cols-2 gap-3 text-sm">
                    @php
                        $scores = [
                            'score1'  => ['يوظف أسس المنهاج وخطوطه العريضة', 5],
                            'score2'  => ['يتمكن من ربط الأهداف العامة للمنهاج', 7],
                            'score3'  => ['يتمكن من المحتوى التعليمي ويعمل على إثرائه', 7],
                            'score4'  => ['يتمكن من الربط العمودي والأفقي للمحتوى', 7],
                            'score5'  => ['يستخدم استراتيجيات تدريس متنوعة', 7],
                            'score6'  => ['يعد الخطط ويطورها وفق أسس علمية', 5],
                            'score7'  => ['يوظف تكنولوجيا التعليم بأنواعها', 4],
                            'score8'  => ['يوظف المنصات التعليمية الإلكترونية', 4],
                            'score9'  => ['يوفر مناخاً تعليمياً آمناً وداعماً', 6],
                            'score10' => ['يوظف القياس والتقويم التربوي', 6],
                            'score11' => ['يكلف الطلبة بمهمات تعزز البحث العلمي', 4],
                            'score12' => ['يتمكن من ربط المبحث بسياقات حياتية', 6],
                            'score13' => ['يوظف طرق التدريس وفق احتياجات الطلبة', 6],
                            'score14' => ['يدمج الطلبة ذوي الاحتياجات الخاصة', 3],
                            'score15' => ['يتمكن من أساسيات اللغة والعلوم والرياضيات', 3],
                            'score16' => ['يعمق معرفته بالمحتوى العلمي', 4],
                            'score17' => ['يستثمر التغذية الراجعة في تطوير أدائه', 3],
                            'score18' => ['يوظف مهارات الاتصال والتواصل', 3],
                            'score19' => ['يحرص على استمرارية نموه المهني', 4],
                            'score20' => ['يلتزم بالأنظمة والقوانين التربوية', 2],
                            'score21' => ['يشارك في اللجان والأنشطة التربوية', 2],
                            'score22' => ['يحرص على بناء علاقات إنسانية', 2],
                        ];
                    @endphp

                    @foreach($scores as $field => [$label, $max])
                        <div class="flex justify-between items-center bg-gray-50 rounded-lg px-3 py-2">
                            <span class="font-bold text-blue-600">
                                {{ $teacher->grades->$field }} / {{ $max }}
                            </span>
                            <span class="text-gray-700 text-right">{{ $label }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 text-center">
                    <a href="{{ route('teacher-grades.edit', $teacher->Teacher_id) }}"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-lg transition">
                        ✏️ تعديل الدرجات
                    </a>
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>