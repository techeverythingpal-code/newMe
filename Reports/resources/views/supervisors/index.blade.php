<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center" dir="rtl">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                👤 إدارة المشرفين
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('supervisors.export') }}"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2 px-5 rounded-lg transition">
                    ⬇️ تصدير Excel
                </a>
                <button type="button" onclick="document.getElementById('supervisors-import-modal').classList.remove('hidden')"
                    class="bg-amber-100 hover:bg-amber-200 text-amber-700 font-bold py-2 px-5 rounded-lg transition">
                    ⬆️ استيراد Excel
                </button>
                <a href="{{ route('supervisors.create') }}"
                    class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-2 px-5 rounded-lg transition">
                    + إضافة مشرف
                </a>
            </div>
        </div>
    </x-slot>
    <!-- Import Modal -->
    <div id="supervisors-import-modal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50" dir="rtl">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl">
            <h3 class="font-bold text-lg mb-3">⬆️ استيراد المشرفين من Excel</h3>
            <p class="text-sm text-gray-500 mb-4">
              الأعمدة المطلوبة: <span class="font-mono">SuperVisor_Name, SuperVisor_Major, directorate_id, role</span><br>
               <span class="text-gray-400">عمود اختياري: <span class="font-mono">SuperVisor_id</span> (إذا تُرك فارغًا سيتم توليده تلقائيًا)</span>
                <br>
                <span class="text-amber-600">⚠️ سيتم تعيين كلمة مرور مؤقتة لكل مشرف جديد، يجب تحديثها بعد الاستيراد.</span>
            </p>
            <form action="{{ route('supervisors.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                    class="w-full border border-gray-300 rounded-lg p-2 mb-4 text-sm">
                <div class="flex gap-2 justify-end">
                    <button type="button" onclick="document.getElementById('supervisors-import-modal').classList.add('hidden')"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded-lg transition">
                        إلغاء
                    </button>
                    <button type="submit"
                        class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-2 px-4 rounded-lg transition">
                        استيراد
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="py-8" dir="rtl">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    ❌ {{ session('error') }}
                </div>
            @endif
            @if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl">
                <table class="w-full text-right text-sm">
                    <thead>
                        <tr class="bg-purple-50 text-purple-700 border-b border-purple-100">
                            <th class="px-6 py-4">#</th>
                            <th class="px-6 py-4">اسم المشرف</th>
                            <th class="px-6 py-4">التخصص</th>
                            <th class="px-6 py-4">المديرية</th>
                            <th class="px-6 py-4">الصلاحية</th>
                            <th class="px-6 py-4">عدد المعلمين</th>
                            <th class="px-6 py-4">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($supervisors as $supervisor)
                            <tr class="border-b border-gray-100 hover:bg-purple-50 transition">
                                <td class="px-6 py-4 text-gray-400">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 font-medium text-gray-800">
                                    {{ $supervisor->SuperVisor_Name }}
                                    @if($supervisor->SuperVisor_id === auth()->id())
                                        <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full mr-1">أنت</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $supervisor->SuperVisor_Major }}</td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $supervisor->directorate->Directorate_Name ?? '—' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($supervisor->role === 'admin')
                                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">
                                            مدير
                                        </span>
                                    @else
                                        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">
                                            مشرف
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-bold">
                                        {{ $supervisor->teachers_count }} معلم
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2 justify-end">
                                        <a href="{{ route('supervisors.edit', $supervisor->SuperVisor_id) }}"
                                            class="bg-blue-100 hover:bg-blue-200 text-blue-700 font-bold py-1 px-4 rounded-lg text-xs transition">
                                            ✏️ تعديل
                                        </a>
                                        @if($supervisor->SuperVisor_id !== auth()->id())
                                            <form action="{{ route('supervisors.destroy', $supervisor->SuperVisor_id) }}"
                                                method="POST"
                                                onsubmit="return confirm('هل أنت متأكد من حذف هذا المشرف؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="bg-red-100 hover:bg-red-200 text-red-700 font-bold py-1 px-4 rounded-lg text-xs transition">
                                                    🗑️ حذف
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                                    <div class="text-5xl mb-3">👤</div>
                                    <div>لا يوجد مشرفون بعد</div>
                                    <a href="{{ route('supervisors.create') }}"
    class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-2 px-5 rounded-lg transition shadow">
    + إضافة مشرف
</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700 text-sm">
                    ← العودة للوحة التحكم
                </a>
            </div>

        </div>
    </div>
</x-app-layout>