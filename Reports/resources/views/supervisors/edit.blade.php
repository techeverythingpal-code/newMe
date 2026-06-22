<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center" dir="rtl">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                👤 تعديل مشرف
            </h2>
            <a href="{{ route('supervisors.index') }}"
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

                <form action="{{ route('supervisors.update', $supervisor->SuperVisor_id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">
                            اسم المشرف <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="SuperVisor_Name"
                            value="{{ old('SuperVisor_Name', $supervisor->SuperVisor_Name) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500"
                            required>
                        @error('SuperVisor_Name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">
                            التخصص <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="SuperVisor_Major"
                            value="{{ old('SuperVisor_Major', $supervisor->SuperVisor_Major) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500"
                            required>
                        @error('SuperVisor_Major')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">
                            المديرية
                        </label>
                        <select name="directorate_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500">
                            <option value="">-- اختر المديرية --</option>
                            @foreach ($directorates as $directorate)
                                <option value="{{ $directorate->Directorate_id }}"
                                    {{ old('directorate_id', $supervisor->directorate_id) == $directorate->Directorate_id ? 'selected' : '' }}>
                                    {{ $directorate->Directorate_Name }}
                                </option>
                            @endforeach
                        </select>
                        @error('directorate_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">
                            الصلاحية <span class="text-red-500">*</span>
                        </label>
                        <select name="role"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500"
                            required>
                            <option value="admin" {{ $supervisor->role == 'admin' ? 'selected' : '' }}>مدير</option>
                            <option value="user" {{ $supervisor->role == 'user' ? 'selected' : '' }}>مشرف</option>
                        </select>
                        @error('role')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">
                            كلمة المرور الجديدة
                            <span class="text-gray-400 text-xs font-normal">(اتركها فارغة إذا لم تريد تغييرها)</span>
                        </label>
                        <input type="password" name="password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500">
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 font-bold mb-2">تأكيد كلمة المرور</label>
                        <input type="password" name="password_confirmation"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500">
                    </div>

                    <div class="flex gap-4">
                        <button type="submit"
                            class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-2 px-6 rounded-lg transition">
                            حفظ التعديلات
                        </button>
                        <a href="{{ route('supervisors.index') }}"
                            class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-6 rounded-lg transition">
                            إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>