<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center" dir="rtl">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                🏫 إضافة مدرسة جديدة
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

                <form action="{{ route('schools.store') }}" method="POST">
                    @csrf

                    {{-- School ID --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">
                            رقم المدرسة <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="School_ID"
                            value="{{ old('School_ID') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500"
                            placeholder="مثال: 2001" required>
                        @error('School_ID')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- School Name --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">
                            اسم المدرسة <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="SchoolName"
                            value="{{ old('SchoolName') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500"
                            placeholder="مثال: مدرسة النجاح الأساسية" required>
                        @error('SchoolName')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Directorate --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">
                            المديرية <span class="text-red-500">*</span>
                        </label>
                        <select name="directorate_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500"
                            required>
                            <option value="">-- اختر المديرية --</option>
                            @foreach($directorates as $directorate)
                                <option value="{{ $directorate->Directorate_id }}"
                                    {{ old('directorate_id') == $directorate->Directorate_id ? 'selected' : '' }}>
                                    {{ $directorate->Directorate_Name }}
                                </option>
                            @endforeach
                        </select>
                        @error('directorate_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ===== Optional details ===== --}}
                    <h3 class="text-gray-500 font-bold mt-6 mb-3 border-b pb-2">بيانات إضافية (اختيارية)</h3>

                    {{-- Principal Name --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">اسم مدير المدرسة</label>
                        <input type="text" name="principal_name"
                            value="{{ old('principal_name') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500">
                        @error('principal_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Address --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">العنوان</label>
                        <input type="text" name="address"
                            value="{{ old('address') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500">
                        @error('address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">الهاتف</label>
                        <input type="tel" name="phone" dir="ltr"
                            value="{{ old('phone') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500 text-right"
                            placeholder="09-2xxxxxx">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">البريد الإلكتروني</label>
                        <input type="email" name="email" dir="ltr"
                            value="{{ old('email') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500 text-right">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Notes --}}
                    <div class="mb-6">
                        <label class="block text-gray-700 font-bold mb-2">معلومات / ملاحظات</label>
                        <textarea name="notes" rows="4"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Buttons --}}
                    <div class="flex gap-4">
                        <button type="submit"
                            class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-2 px-6 rounded-lg transition">
                            حفظ المدرسة
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