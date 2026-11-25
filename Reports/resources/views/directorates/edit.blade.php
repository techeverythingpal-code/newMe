<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            تعديل مديرية
        </h2>
    </x-slot>

    <div class="py-12" dir="rtl">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <h3 class="font-bold mb-2">حدثت أخطاء:</h3>
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('directorates.update', $directorate->Directorate_id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    {{-- Directorate ID (read-only) --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">رقم المديرية</label>
                        <input
                            type="text"
                            value="{{ $directorate->Directorate_id }}"
                            class="w-full px-4 py-2 border border-gray-200 rounded bg-gray-100 cursor-not-allowed"
                            disabled
                        >
                    </div>

                    {{-- Directorate Name --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">
                            اسم المديرية <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="Directorate_Name"
                            value="{{ old('Directorate_Name', $directorate->Directorate_Name) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                            required
                        >
                        @error('Directorate_Name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ===== Optional details ===== --}}
                    <h3 class="text-gray-500 font-bold mt-6 mb-3 border-b pb-2">بيانات إضافية (اختيارية)</h3>

                    {{-- Manager Name --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">اسم مدير المديرية</label>
                        <input
                            type="text"
                            name="manager_name"
                            value="{{ old('manager_name', $directorate->manager_name) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                        >
                        @error('manager_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- Address --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">العنوان</label>
                        <input
                            type="text"
                            name="address"
                            value="{{ old('address', $directorate->address) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                        >
                        @error('address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- Phone --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">الهاتف</label>
                        <input
                            type="tel"
                            name="phone"
                            dir="ltr"
                            value="{{ old('phone', $directorate->phone) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 text-right"
                        >
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- Email --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">البريد الإلكتروني</label>
                        <input
                            type="email"
                            name="email"
                            dir="ltr"
                            value="{{ old('email', $directorate->email) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 text-right"
                        >
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- Notes --}}
                    <div class="mb-6">
                        <label class="block text-gray-700 font-bold mb-2">معلومات / ملاحظات</label>
                        <textarea
                            name="notes"
                            rows="4"
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                        >{{ old('notes', $directorate->notes) }}</textarea>
                        @error('notes')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Buttons --}}
                    <div class="flex gap-4">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                            حفظ التعديلات
                        </button>
                        <a href="{{ route('directorates.show', $directorate->Directorate_id) }}"
                            class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-6 rounded">
                            إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>