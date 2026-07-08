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
                @php
                    $colorClasses = [
                        'green'  => 'bg-green-100 text-green-700',
                        'blue'   => 'bg-blue-100 text-blue-700',
                        'yellow' => 'bg-yellow-100 text-yellow-700',
                        'orange' => 'bg-orange-100 text-orange-700',
                        'red'    => 'bg-red-100 text-red-700',
                    ];
                    $assessment = $teacher->grades->assessment;
                @endphp
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <div class="flex items-center gap-2">
                        <span class="bg-blue-100 text-blue-700 px-4 py-1 rounded-full font-bold">
                            المجموع: {{ $teacher->grades->total }} / 100
                        </span>
                        <span class="{{ $colorClasses[$assessment['color']] }} px-4 py-1 rounded-full font-bold text-sm">
                            {{ $assessment['label'] }}
                        </span>
                    </div>
                    <h3 class="text-lg font-bold text-blue-700">📊 الدرجات التفصيلية</h3>
                </div>

                <div class="grid grid-cols-2 gap-3 text-sm">
                   

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