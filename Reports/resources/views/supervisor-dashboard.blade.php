<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-right">
            لوحة التحكم - {{ auth()->user()->SuperVisor_Name }}
        </h2>
    </x-slot>

    <div class="py-6" dir="rtl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Stats Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">

                <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-5 text-white shadow-lg">
                    <div class="text-4xl mb-2">👨‍🏫</div>
                    <div class="text-3xl font-bold">{{ $totalTeachers }}</div>
                    <div class="text-sm opacity-80 mt-1">إجمالي المعلمين</div>
                </div>

                <div class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl p-5 text-white shadow-lg">
                    <div class="text-4xl mb-2">📊</div>
                    <div class="text-3xl font-bold">{{ number_format($avgTotal, 1) }}</div>
                    <div class="text-sm opacity-80 mt-1">متوسط الدرجات</div>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl p-5 text-white shadow-lg">
                    <div class="text-4xl mb-2">🏆</div>
                    <div class="text-3xl font-bold">{{ $highestScore }}</div>
                    <div class="text-sm opacity-80 mt-1">أعلى درجة</div>
                </div>

            </div>

           
           

            {{-- My Teachers Table --}}
            <div class="bg-white rounded-2xl shadow p-5">
               <div class="flex justify-between items-center mb-4">
                    <div class="flex gap-2">
                        <a href="{{ route('teachers.create') }}"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg text-sm transition">
                            + إضافة معلم
                        </a>
                        <a href="{{ route('teachers.export') }}"
                            class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-2 px-4 rounded-lg text-sm transition">
                            📥 تصدير Excel
                        </a>
                        <form action="{{ route('teacher-grades.reset-all') }}" method="POST"
                            onsubmit="return confirm('هل أنت متأكد من حذف درجات جميع معلميك؟ لا يمكن التراجع عن هذا الإجراء.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg text-sm transition">
                                🗑️ حذف كل الدرجات
                            </button>
                        </form>
                    </div>
                    <h3 class="font-semibold text-gray-700">معلمون</h3>
                </div>

                {{-- Filters (instant, client-side — no page reload) --}}
                <div class="grid grid-cols-1 md:grid-cols-5 gap-3 mb-5">
                    <input type="text" id="searchInput"
                        placeholder="بحث (اسم، تخصص، مؤهل...)"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm md:col-span-2">

                    <select id="schoolFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="">كل المدارس</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->School_ID }}">{{ $school->SchoolName }}</option>
                        @endforeach
                    </select>

                    <input type="number" id="minScoreFilter"
                        placeholder="من (المجموع)"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm">

                    <input type="number" id="maxScoreFilter"
                        placeholder="إلى (المجموع)"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm">

                    <div class="md:col-span-5 flex gap-2 justify-end">
                        <button id="resetFilters" type="button"
                            class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded-lg text-sm transition">
                            إعادة تعيين
                        </button>
                    </div>
                </div>

                <div id="teachersCardGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    {{-- Cards are rendered by JavaScript --}}
                </div>

                <div id="emptyState" class="hidden p-5 text-center text-gray-400">
                    لا يوجد معلمون مطابقون لهذا البحث
                </div>

                <div id="paginationControls" class="mt-4 flex items-center justify-between text-sm text-gray-600"></div>
            </div>

        </div>
    </div>

   

    <script>
        // All teacher data, fetched once from the server — filtering happens here in the browser
        const allTeachers = {!! json_encode($teachersData) !!};
        const PAGE_SIZE = 10;
        let currentPage = 1;

        const routes = {
            show:        id => "{{ url('teachers') }}/" + id,
            edit:        id => "{{ url('teachers') }}/" + id + "/edit",
            destroy:     id => "{{ url('teachers') }}/" + id,
            resetScores: id => "{{ url('teachers') }}/" + id + "/grades/reset",
        };

        const searchInput    = document.getElementById('searchInput');
        const schoolFilter   = document.getElementById('schoolFilter');
        const minScoreFilter = document.getElementById('minScoreFilter');
        const maxScoreFilter = document.getElementById('maxScoreFilter');
        const resetBtn       = document.getElementById('resetFilters');
        const cardGrid       = document.getElementById('teachersCardGrid');
        const emptyState     = document.getElementById('emptyState');
        const paginationEl   = document.getElementById('paginationControls');

        function getFiltered() {
            const search    = searchInput.value.trim().toLowerCase();
            const schoolId  = schoolFilter.value;
            const minScore  = minScoreFilter.value !== '' ? parseFloat(minScoreFilter.value) : null;
            const maxScore  = maxScoreFilter.value !== '' ? parseFloat(maxScoreFilter.value) : null;

            return allTeachers.filter(t => {
                if (search) {
                    const haystack = [t.name, t.major, t.qualify].join(' ').toLowerCase();
                    if (!haystack.includes(search)) return false;
                }
                if (schoolId && String(t.school_id) !== String(schoolId)) return false;
                if (minScore !== null && t.total < minScore) return false;
                if (maxScore !== null && t.total > maxScore) return false;
                return true;
            });
        }

        function escapeHtml(str) {
            const div = document.createElement('div');
            div.textContent = str ?? '';
            return div.innerHTML;
        }

        function renderTable() {
            const filtered = getFiltered();
            const totalPages = Math.max(1, Math.ceil(filtered.length / PAGE_SIZE));
            if (currentPage > totalPages) currentPage = totalPages;

            const start = (currentPage - 1) * PAGE_SIZE;
            const pageItems = filtered.slice(start, start + PAGE_SIZE);

            cardGrid.innerHTML = '';
            emptyState.classList.toggle('hidden', pageItems.length > 0);

            pageItems.forEach((t, index) => {
                const card = document.createElement('div');
                card.className = 'bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition p-4 flex flex-col gap-3';
                card.innerHTML = `
                    <div class="flex items-start justify-between">
                        <span class="text-xs text-gray-400">#${start + index + 1}</span>
                        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">
                            ${t.total} / 100
                        </span>
                    </div>

                    <div>
                        <div class="font-bold text-gray-800 text-base">${escapeHtml(t.name)}</div>
                        <div class="text-sm text-gray-500 mt-1">🏫 ${escapeHtml(t.school)}</div>
                        <div class="text-sm text-gray-500">🎓 ${escapeHtml(t.major)}</div>
                    </div>

                    <div class="flex flex-wrap gap-2 mt-auto pt-2 border-t border-gray-100">
                        <a href="${routes.show(t.id)}"
                            class="bg-green-100 hover:bg-green-200 text-green-700 font-bold py-1 px-3 rounded-lg text-xs transition">
                            👁️ عرض
                        </a>
                        <a href="${routes.edit(t.id)}"
                            class="bg-blue-100 hover:bg-blue-200 text-blue-700 font-bold py-1 px-3 rounded-lg text-xs transition">
                            ✏️ تعديل
                        </a>
                        <form action="${routes.resetScores(t.id)}" method="POST"
                            onsubmit="return confirm('هل أنت متأكد من حذف درجات هذا المعلم؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-orange-100 hover:bg-orange-200 text-orange-700 font-bold py-1 px-3 rounded-lg text-xs transition">
                                🗑️ حذف الدرجات
                            </button>
                        </form>
                        <form action="${routes.destroy(t.id)}" method="POST"
                            onsubmit="return confirm('هل أنت متأكد؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-100 hover:bg-red-200 text-red-700 font-bold py-1 px-3 rounded-lg text-xs transition">
                                🗑️ حذف
                            </button>
                        </form>
                    </div>
                `;
                cardGrid.appendChild(card);
            });

            renderPagination(totalPages, filtered.length);
        }

        function renderPagination(totalPages, totalCount) {
            if (totalCount === 0) {
                paginationEl.innerHTML = '';
                return;
            }

            paginationEl.innerHTML = `
                <span>إجمالي النتائج: ${totalCount}</span>
                <div class="flex gap-1">
                    <button data-page="${currentPage - 1}" ${currentPage === 1 ? 'disabled' : ''}
                        class="page-btn px-3 py-1 rounded-lg border border-gray-300 ${currentPage === 1 ? 'opacity-40 cursor-not-allowed' : 'hover:bg-gray-100'}">
                        السابق
                    </button>
                    <span class="px-2 py-1">صفحة ${currentPage} من ${totalPages}</span>
                    <button data-page="${currentPage + 1}" ${currentPage === totalPages ? 'disabled' : ''}
                        class="page-btn px-3 py-1 rounded-lg border border-gray-300 ${currentPage === totalPages ? 'opacity-40 cursor-not-allowed' : 'hover:bg-gray-100'}">
                        التالي
                    </button>
                </div>
            `;

            paginationEl.querySelectorAll('.page-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const page = parseInt(btn.dataset.page);
                    if (page >= 1 && page <= totalPages) {
                        currentPage = page;
                        renderTable();
                    }
                });
            });
        }

        function applyFilters() {
            currentPage = 1;
            renderTable();
        }

        searchInput.addEventListener('input', applyFilters);
        schoolFilter.addEventListener('change', applyFilters);
        minScoreFilter.addEventListener('input', applyFilters);
        maxScoreFilter.addEventListener('input', applyFilters);

        resetBtn.addEventListener('click', () => {
            searchInput.value = '';
            schoolFilter.value = '';
            minScoreFilter.value = '';
            maxScoreFilter.value = '';
            applyFilters();
        });

        renderTable();
    </script>
</x-app-layout>