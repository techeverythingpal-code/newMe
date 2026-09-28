<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex gap-2">
                <a href="{{ route('teachers.export') }}"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2 px-5 rounded-lg transition">
                    ⬇️ تصدير Excel
                </a>
                <button type="button" onclick="document.getElementById('teachers-import-modal').classList.remove('hidden')"
                    class="bg-amber-100 hover:bg-amber-200 text-amber-700 font-bold py-2 px-5 rounded-lg transition">
                    ⬆️ استيراد Excel
                </button>
                <a href="{{ route('teachers.create') }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-5 rounded-lg transition shadow">
                    + إضافة معلم
                </a>
            </div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                👨‍🏫 إدارة المعلمين
            </h2>
        </div>
    </x-slot>

    <!-- Import Modal -->
    <div id="teachers-import-modal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50" dir="rtl">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl">
            <h3 class="font-bold text-lg mb-3">⬆️ استيراد المعلمين من Excel</h3>
            <p class="text-sm text-gray-500 mb-4">
                الأعمدة المطلوبة: <span class="font-mono">Teacher_id, Teacher_Name, school_id, supervisor_id, date, teacher_qualify, teacher_major</span>
            </p>
            <form action="{{ route('teachers.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                    class="w-full border border-gray-300 rounded-lg p-2 mb-4 text-sm">
                <div class="flex gap-2 justify-end">
                    <button type="button" onclick="document.getElementById('teachers-import-modal').classList.add('hidden')"
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

            <div class="mb-4">
                <input type="text" id="teacher-search" placeholder="🔍 بحث بالاسم أو المدرسة..."
                    class="w-full max-w-sm border border-gray-300 rounded-lg p-2 text-sm">
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl">
                <table class="w-full text-right text-sm">
                    <thead>
                        <tr class="bg-blue-50 text-blue-700 border-b border-blue-100">
                            <th class="px-6 py-4">#</th>
                            <th class="px-6 py-4">رقم المعلم</th>
                            <th class="px-6 py-4">اسم المعلم</th>
                            <th class="px-6 py-4">المدرسة</th>
                            <th class="px-6 py-4">المشرف</th>
                            <th class="px-6 py-4">التخصص</th>
                            <th class="px-6 py-4">المؤهل</th>
                            <th class="px-6 py-4">تاريخ التعيين</th>
                            <th class="px-6 py-4">المجموع</th>
                            <th class="px-6 py-4">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="teachers-tbody">
                        <tr>
                            <td colspan="10" class="px-6 py-10 text-center text-gray-400">جارِ التحميل...</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700 text-sm">
                    ← العودة للوحة التحكم
                </a>
                <div class="flex items-center gap-3 text-sm text-gray-600">
                    <button id="prev-page" type="button" class="bg-gray-100 hover:bg-gray-200 disabled:opacity-40 font-bold py-1.5 px-4 rounded-lg transition">
                        السابق
                    </button>
                    <span id="page-info">صفحة 1</span>
                    <button id="next-page" type="button" class="bg-gray-100 hover:bg-gray-200 disabled:opacity-40 font-bold py-1.5 px-4 rounded-lg transition">
                        التالي
                    </button>
                </div>
            </div>

        </div>
    </div>

    <script>
        (function () {
            const tbody      = document.getElementById('teachers-tbody');
            const searchBox  = document.getElementById('teacher-search');
            const prevBtn    = document.getElementById('prev-page');
            const nextBtn    = document.getElementById('next-page');
            const pageInfo   = document.getElementById('page-info');

            let currentPage = 1;
            let lastPage    = 1;
            let searchTimer = null;

            const editUrlTemplate    = "{{ route('teachers.edit', ['teacher' => '__ID__']) }}";
            const showUrlTemplate    = "{{ route('teachers.show', ['teacher' => '__ID__']) }}";
            const destroyUrlTemplate = "{{ route('teachers.destroy', ['teacher' => '__ID__']) }}";
            const dataUrl            = "{{ route('dashboard.teachers-data') }}";
            const csrfToken          = document.querySelector('meta[name="csrf-token"]').content;

            function assessmentBadge(assessment) {
                const colors = {
                    green: 'bg-green-100 text-green-700',
                    blue: 'bg-blue-100 text-blue-700',
                    yellow: 'bg-yellow-100 text-yellow-700',
                    orange: 'bg-orange-100 text-orange-700',
                    red: 'bg-red-100 text-red-700',
                    gray: 'bg-gray-100 text-gray-700',
                };
                const cls = colors[assessment?.color] || colors.gray;
                return `<span class="${cls} px-3 py-1 rounded-full text-xs font-bold">${assessment?.label ?? '—'}</span>`;
            }

            function renderRows(teachers, offset) {
                if (!teachers.length) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="10" class="px-6 py-10 text-center text-gray-400">
                                <div class="text-5xl mb-3">👨‍🏫</div>
                                <div>لا يوجد معلمون بعد</div>
                                <a href="{{ route('teachers.create') }}"
                                    class="mt-3 inline-block bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-5 rounded-lg transition">
                                    + أضف أول معلم
                                </a>
                            </td>
                        </tr>`;
                    return;
                }

                tbody.innerHTML = teachers.map((t, i) => `
                    <tr class="border-b border-gray-100 hover:bg-blue-50 transition">
                        <td class="px-6 py-4 text-gray-400">${offset + i + 1}</td>
                        <td class="px-6 py-4 font-bold text-blue-600">${t.id}</td>
                        <td class="px-6 py-4 font-medium text-gray-800">${t.name}</td>
                        <td class="px-6 py-4 text-gray-600">${t.school}</td>
                        <td class="px-6 py-4 text-gray-600">${t.supervisor}</td>
                        <td class="px-6 py-4 text-gray-600">${t.major ?? ''}</td>
                        <td class="px-6 py-4 text-gray-600">${t.qualify ?? ''}</td>
                        <td class="px-6 py-4 text-gray-600">${t.date ?? ''}</td>
                        <td class="px-6 py-4">${assessmentBadge(t.assessment)}</td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2 justify-end">
                                <a href="${showUrlTemplate.replace('__ID__', t.id)}"
                                    class="bg-green-100 hover:bg-green-200 text-green-700 font-bold py-1 px-3 rounded-lg text-xs transition">
                                    👁️ عرض
                                </a>
                                <a href="${editUrlTemplate.replace('__ID__', t.id)}"
                                    class="bg-blue-100 hover:bg-blue-200 text-blue-700 font-bold py-1 px-3 rounded-lg text-xs transition">
                                    ✏️ تعديل
                                </a>
                                <button type="button" data-id="${t.id}"
                                    class="delete-teacher-btn bg-red-100 hover:bg-red-200 text-red-700 font-bold py-1 px-3 rounded-lg text-xs transition">
                                    🗑️ حذف
                                </button>
                            </div>
                        </td>
                    </tr>
                `).join('');

                tbody.querySelectorAll('.delete-teacher-btn').forEach(btn => {
                    btn.addEventListener('click', () => deleteTeacher(btn.dataset.id));
                });
            }

            function deleteTeacher(id) {
                if (!confirm('هل أنت متأكد من حذف هذا المعلم؟')) return;

                fetch(destroyUrlTemplate.replace('__ID__', id), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-HTTP-Method-Override': 'DELETE',
                        'Accept': 'application/json',
                    },
                }).then(() => load());
            }

            function load() {
                tbody.innerHTML = `<tr><td colspan="10" class="px-6 py-10 text-center text-gray-400">جارِ التحميل...</td></tr>`;

                const params = new URLSearchParams({
                    page: currentPage,
                    search: searchBox.value.trim(),
                });

                fetch(`${dataUrl}?${params.toString()}`, {
                    headers: { 'Accept': 'application/json' },
                })
                    .then(res => res.json())
                    .then(json => {
                        lastPage = json.last_page;
                        currentPage = json.current_page;
                        pageInfo.textContent = `صفحة ${currentPage} من ${lastPage} (${json.total} معلم)`;
                        prevBtn.disabled = currentPage <= 1;
                        nextBtn.disabled = currentPage >= lastPage;
                        renderRows(json.data, (currentPage - 1) * json.per_page);
                    });
            }

            searchBox.addEventListener('input', () => {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(() => {
                    currentPage = 1;
                    load();
                }, 350);
            });

            prevBtn.addEventListener('click', () => {
                if (currentPage > 1) { currentPage--; load(); }
            });

            nextBtn.addEventListener('click', () => {
                if (currentPage < lastPage) { currentPage++; load(); }
            });

            load();
        })();
    </script>
</x-app-layout>