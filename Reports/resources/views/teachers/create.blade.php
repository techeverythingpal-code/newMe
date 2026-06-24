<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <a href="{{ route('teachers.index') }}"
                class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-5 rounded-lg transition">
                ← العودة
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                👨‍🏫 إضافة معلم جديد
            </h2>
        </div>
    </x-slot>

    <div class="py-8" dir="rtl">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
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

                <form action="{{ route('teachers.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">
                            رقم المعلم <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="Teacher_id"
                            value="{{ old('Teacher_id') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                            placeholder="مثال: 12345" required>
                        @error('Teacher_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">
                            اسم المعلم <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="Teacher_Name"
                            value="{{ old('Teacher_Name') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                            placeholder="مثال: أحمد محمد علي" required>
                        @error('Teacher_Name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">
                            المدرسة <span class="text-red-500">*</span>
                        </label>
                        <select name="school_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                            required>
                            <option value="">-- اختر المدرسة --</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->School_ID }}"
                                    {{ old('school_id') == $school->School_ID ? 'selected' : '' }}>
                                    {{ $school->SchoolName }}
                                </option>
                            @endforeach
                        </select>
                        @error('school_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">
                            المشرف <span class="text-red-500">*</span>
                        </label>

                        @if($currentSupervisor)
                            {{-- Logged-in supervisor: locked to themselves --}}
                            <input type="text" value="{{ $currentSupervisor->SuperVisor_Name }}" disabled
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-600">
                            <input type="hidden" name="supervisor_id" value="{{ $currentSupervisor->SuperVisor_id }}">
                        @else
                            {{-- Admin: free choice --}}
                            <select name="supervisor_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                required>
                                <option value="">-- اختر المشرف --</option>
                                @foreach($supervisors as $supervisor)
                                    <option value="{{ $supervisor->SuperVisor_id }}"
                                        {{ old('supervisor_id') == $supervisor->SuperVisor_id ? 'selected' : '' }}>
                                        {{ $supervisor->SuperVisor_Name }}
                                    </option>
                                @endforeach
                            </select>
                        @endif

                        @error('supervisor_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">
                            تاريخ التعيين <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="date"
                            value="{{ old('date') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                            required>
                        @error('date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">
                            المؤهل العلمي <span class="text-red-500">*</span>
                        </label>
                        <select name="teacher_qualify"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                            required>
                            <option value="">-- اختر المؤهل --</option>
                            <option value="بكالوريوس" {{ old('teacher_qualify') == 'بكالوريوس' ? 'selected' : '' }}>بكالوريوس</option>
                            <option value="ماجستير" {{ old('teacher_qualify') == 'ماجستير' ? 'selected' : '' }}>ماجستير</option>
                            <option value="دكتوراه" {{ old('teacher_qualify') == 'دكتوراه' ? 'selected' : '' }}>دكتوراه</option>
                            <option value="دبلوم" {{ old('teacher_qualify') == 'دبلوم' ? 'selected' : '' }}>دبلوم</option>
                        </select>
                        @error('teacher_qualify')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 font-bold mb-2">
                            التخصص <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="teacher_major"
                            value="{{ old('teacher_major') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                            placeholder="مثال: رياضيات" required>
                        @error('teacher_major')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-4">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-lg transition">
                            حفظ المعلم
                        </button>
                        <a href="{{ route('teachers.index') }}"
                            class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-6 rounded-lg transition">
                            إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>