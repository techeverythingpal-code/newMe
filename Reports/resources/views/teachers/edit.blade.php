<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <a href="{{ route('teachers.index') }}"
                class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-5 rounded-lg transition">
                ← العودة
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                👨‍🏫 تعديل بيانات معلم
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

                <form action="{{ route('teachers.update', $teacher->Teacher_id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">رقم المعلم</label>
                        <input type="text" value="{{ $teacher->Teacher_id }}"
                            class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-100 cursor-not-allowed"
                            disabled>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">
                            اسم المعلم <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="Teacher_Name"
                            value="{{ old('Teacher_Name', $teacher->Teacher_Name) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                            required>
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
                                    {{ $teacher->school_id == $school->School_ID ? 'selected' : '' }}>
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
                        <select name="supervisor_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                            required>
                            <option value="">-- اختر المشرف --</option>
                            @foreach($supervisors as $supervisor)
                                <option value="{{ $supervisor->SuperVisor_id }}"
                                    {{ $teacher->supervisor_id == $supervisor->SuperVisor_id ? 'selected' : '' }}>
                                    {{ $supervisor->SuperVisor_Name }}
                                </option>
                            @endforeach
                        </select>
                        @error('supervisor_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">
                            تاريخ التعيين <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="date"
                            value="{{ old('date', $teacher->date) }}"
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
                            <option value="بكالوريوس" {{ $teacher->teacher_qualify == 'بكالوريوس' ? 'selected' : '' }}>بكالوريوس</option>
                            <option value="ماجستير" {{ $teacher->teacher_qualify == 'ماجستير' ? 'selected' : '' }}>ماجستير</option>
                            <option value="دكتوراه" {{ $teacher->teacher_qualify == 'دكتوراه' ? 'selected' : '' }}>دكتوراه</option>
                            <option value="دبلوم" {{ $teacher->teacher_qualify == 'دبلوم' ? 'selected' : '' }}>دبلوم</option>
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
                            value="{{ old('teacher_major', $teacher->teacher_major) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                            required>
                        @error('teacher_major')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-4">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-lg transition">
                            حفظ التعديلات
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