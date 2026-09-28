<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center" dir="rtl">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                🏫 تعديل مدرسة
            </h2>
            <a href="{{ route('schools.index') }}"
                class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-5 rounded-lg transition">
                ← العودة
            </a>
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

                <form action="{{ route('schools.update', $school->School_ID) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">رقم المدرسة</label>
                        <input type="text" value="{{ $school->School_ID }}"
                            class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-100 cursor-not-allowed"
                            disabled>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">
                            اسم المدرسة <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="SchoolName"
                            value="{{ old('SchoolName', $school->SchoolName) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500"
                            required>
                        @error('SchoolName')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 font-bold mb-2">
                            المديرية <span class="text-red-500">*</span>
                        </label>
                        <select name="directorate_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500"
                            required>
                            <option value="">-- اختر المديرية --</option>
                            @foreach($directorates as $directorate)
                                <option value="{{ $directorate->Directorate_id }}"
                                    {{ $school->directorate_id == $directorate->Directorate_id ? 'selected' : '' }}>
                                    {{ $directorate->Directorate_Name }}
                                </option>
                            @endforeach
                        </select>
                        @error('directorate_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-4">
                        <button type="submit"
                            class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-2 px-6 rounded-lg transition">
                            حفظ التعديلات
                        </button>
                        <a href="{{ route('schools.index') }}"
                            class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-6 rounded-lg transition">
                            إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>