<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <a href="{{ route('teachers.show', $teacher->Teacher_id) }}"
                class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-5 rounded-lg transition">
                ← العودة
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📊 تعديل درجات: {{ $teacher->Teacher_Name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8" dir="rtl">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-2xl p-6">

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        <h3 class="font-bold mb-2">حدثت أخطاء:</h3>
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('teacher-grades.update', $teacher->Teacher_id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    @php
                        $scores = [
                            'score1'  => ['يوظف أسس المنهاج وخطوطه العريضة في العملية التعليمية', 5],
                            'score2'  => ['يتمكن من ربط الأهداف العامة للمنهاج مع أهداف المرحلة', 7],
                            'score3'  => ['يتمكن من المحتوى التعليمي ويعمل على إثرائه', 7],
                            'score4'  => ['يتمكن من الربط العمودي والأفقي للمحتوى التعليمي', 7],
                            'score5'  => ['يستخدم استراتيجيات تدريس متنوعة تتلاءم مع الموقف التعليمي', 7],
                            'score6'  => ['يعد الخطط ويطورها وفق أسس علمية', 5],
                            'score7'  => ['يوظف تكنولوجيا التعليم بأنواعها في العملية التعليمية', 4],
                            'score8'  => ['يوظف المنصات التعليمية الإلكترونية لتعزيز تعلم الطلبة', 4],
                            'score9'  => ['يوفر مناخاً تعليمياً آمناً وداعماً يمتاز بالمرونة والابتكار', 6],
                            'score10' => ['يوظف القياس والتقويم التربوي بأنواعه', 6],
                            'score11' => ['يكلف الطلبة بمهمات تعزز مهارات البحث العلمي', 4],
                            'score12' => ['يتمكن من ربط المبحث بسياقات حياتية وتعليمية متنوعة', 6],
                            'score13' => ['يوظف طرق التدريس بما ينسجم مع احتياجات الطلبة وقدراتهم', 6],
                            'score14' => ['يدمج الطلبة ذوي الاحتياجات الخاصة في العملية التعليمية', 3],
                            'score15' => ['يتمكن من أساسيات اللغة العربية والعلوم والرياضيات', 3],
                            'score16' => ['يعمق معرفته بالمحتوى العلمي ويوجهها نحو زيادة الإلمام', 4],
                            'score17' => ['يستثمر التغذية الراجعة في تطوير أدائه', 3],
                            'score18' => ['يوظف مهارات الاتصال والتواصل في العملية التعليمية', 3],
                            'score19' => ['يحرص على استمرارية نموه المهني ويعد أبحاثاً في تخصصه', 4],
                            'score20' => ['يلتزم بالأنظمة والقوانين واللوائح التربوية', 2],
                            'score21' => ['يشارك في اللجان المختلفة والأنشطة التربوية', 2],
                            'score22' => ['يحرص على بناء علاقات إنسانية مع الأطراف ذات العلاقة', 2],
                        ];
                    @endphp

                    {{-- Live Total --}}
                    <div class="mb-6 p-4 bg-blue-50 rounded-xl text-center">
                        <span class="text-gray-600 text-sm">المجموع الكلي:</span>
                        <span id="total-display" class="text-3xl font-bold text-blue-600 mr-2">
                            {{ $grades->total }}
                        </span>
                        <span class="text-gray-500">/ 100</span>
                    </div>

                    {{-- Score Fields --}}
                    <div class="space-y-3">
                        @foreach($scores as $field => [$label, $max])
                            <div class="flex items-center gap-4 bg-gray-50 rounded-xl px-4 py-3">
                                {{-- Score Input --}}
                                <div class="flex items-center gap-2 min-w-fit">
                                    <input
                                        type="number"
                                        name="{{ $field }}"
                                        value="{{ old($field, $grades->$field) }}"
                                        min="0"
                                        max="{{ $max }}"
                                        class="score-input w-16 text-center px-2 py-1 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 font-bold"
                                        required>
                                    <span class="text-gray-400 text-sm">/ {{ $max }}</span>
                                </div>
                                {{-- Label --}}
                                <span class="text-gray-700 text-sm text-right flex-1">{{ $label }}</span>
                                {{-- Number --}}
                                <span class="text-gray-400 text-xs min-w-fit">{{ $loop->iteration }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex gap-4 mt-6">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-lg transition">
                            💾 حفظ الدرجات
                        </button>
                        <a href="{{ route('teachers.show', $teacher->Teacher_id) }}"
                            class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-6 rounded-lg transition">
                            إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Live Total Calculator --}}
    <script>
        const inputs = document.querySelectorAll('.score-input');
        const totalDisplay = document.getElementById('total-display');

        function updateTotal() {
            let total = 0;
            inputs.forEach(input => {
                total += parseInt(input.value) || 0;
            });
            totalDisplay.textContent = total;
        }

        inputs.forEach(input => {
            input.addEventListener('input', updateTotal);
        });
    </script>

</x-app-layout>