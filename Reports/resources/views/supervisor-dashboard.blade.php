<x-app-layout>
    @php $section = $section ?? 'main'; @endphp
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-right">
            {{ $section === 'manage' ? 'إدارة المعلمين' : ($section === 'reports' ? 'التقارير والطباعة' : 'لوحة التحكم') }}
        </h2>
    </x-slot>

<style>
    .rtl-select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%23666'%3E%3Cpath d='M5.5 7l4.5 4.5L14.5 7' stroke='%23666' stroke-width='1.5' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: left 10px center;
    background-size: 14px;
    padding-left: 32px;
}

.view-toggle-btn {
    color: #6b7280;
}

.view-toggle-btn.bg-white {
    color: #45408E;
}

/* Vertical timeline line behind the teacher cards */
#teachersCardGrid::before {
    content: '';
    position: absolute;
    right: 7px;
    top: 1.5rem;
    bottom: 1.5rem;
    width: 2px;
    border-radius: 2px;
    background: #AAA7D2;
}
</style>

    <div class="py-6" dir="rtl">
        <div class="max-w-[1700px] mx-auto px-4 sm:px-6 lg:px-8">

            {{-- ===== Section: main dashboard (profile, print range, grade summary) ===== --}}
            <div id="dashMain" class="space-y-6 {{ $section === 'main' ? '' : 'hidden' }}">

            {{-- Row 1: profile card + academic year / range print --}}
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

                {{-- Profile card --}}
                <div class="xl:col-span-2 relative bg-white rounded-2xl shadow-sm p-6">
                    <a href="{{ route('profile.edit') }}" title="تعديل الملف الشخصي"
                        class="absolute top-5 left-5 text-gray-400 hover:text-brand-600 transition">✏️</a>

                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                        <div class="w-28 h-28 shrink-0 rounded-full bg-brand-500 text-white flex items-center justify-center text-5xl font-bold ring-4 ring-brand-100">
                            {{ mb_substr(Auth::guard('web')->user()->SuperVisor_Name ?? '', 0, 1) }}
                        </div>

                        <div class="flex-1 text-center sm:text-right">
                            <div class="flex items-center gap-2 justify-center sm:justify-start">
                                <h3 class="text-xl font-bold text-gray-800">{{ Auth::guard('web')->user()->SuperVisor_Name }}</h3>
                                <span class="text-xs font-bold bg-brand-100 text-brand-700 px-2.5 py-1 rounded-full">مشرف</span>
                            </div>

                            <dl class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-2 text-sm">
                                <div class="flex gap-2">
                                    <dt class="text-gray-400">المديرية:</dt>
                                    <dd id="profileDirectorate" class="font-semibold text-gray-700">—</dd>
                                </div>
                                <div class="flex gap-2">
                                    <dt class="text-gray-400">عدد المدارس:</dt>
                                    <dd id="profileSchools" class="font-semibold text-gray-700">0</dd>
                                </div>
                                <div class="flex gap-2">
                                    <dt class="text-gray-400">عدد المعلمين:</dt>
                                    <dd class="font-semibold text-gray-700">{{ $totalTeachers }}</dd>
                                </div>
                                <div class="flex gap-2">
                                    <dt class="text-gray-400">العام الدراسي:</dt>
                                    <dd id="profileYear" class="font-semibold text-gray-700">—</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                {{-- Academic year + range print --}}
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">العام الدراسي والطباعة</h3>

                    <label class="block text-xs font-bold text-gray-500 mb-1">العام الدراسي</label>
                    <select id="academicYearSelect" class="rtl-select w-full rounded-xl border-gray-200 bg-gray-50 px-3 py-2 text-sm">
                        <option value="2026/2027">2026/2027</option>
                        <option value="2027/2028">2027/2028</option>
                        <option value="2028/2029">2028/2029</option>
                        <option value="2029/2030">2029/2030</option>
                    </select>

                    <div class="mt-4 text-xs font-bold text-gray-500 mb-1">طباعة نطاق من المعلمين</div>
                    <div class="space-y-2">
                        <select id="rangeFromSelect" class="rtl-select w-full rounded-xl border-gray-200 bg-gray-50 px-3 py-2 text-sm">
                            <option value="">-- من المعلم --</option>
                        </select>
                        <select id="rangeToSelect" class="rtl-select w-full rounded-xl border-gray-200 bg-gray-50 px-3 py-2 text-sm">
                            <option value="">-- إلى المعلم --</option>
                        </select>
                    </div>

                    <button type="button" id="printRangeBtn"
                        class="mt-4 w-full bg-brand-500 hover:bg-brand-600 text-white font-bold py-2.5 rounded-xl text-sm transition">
                        🖨️ طباعة النطاق
                    </button>
                </div>
            </div>

            {{-- Row 2: grade summary --}}
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-start">
                {{-- Grade summary card --}}
                <div class="bg-gradient-to-br from-brand-500 to-brand-700 rounded-2xl shadow-sm p-6 text-white xl:sticky xl:top-6">
                    <h3 class="text-lg font-bold leading-snug">ملخص التقديرات</h3>
                    <p class="text-sm text-white/70 mt-1">توزيع المعلمين حسب التقدير</p>

                    <ul class="mt-5 space-y-3 text-sm">
                        <li class="flex items-center justify-between">
                            <span class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-white/70"></span>ممتاز (85 فأكثر)</span>
                            <span id="sumA" class="font-bold">0</span>
                        </li>
                        <li class="flex items-center justify-between">
                            <span class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-white/70"></span>جيد جداً (75 - 84)</span>
                            <span id="sumB" class="font-bold">0</span>
                        </li>
                        <li class="flex items-center justify-between">
                            <span class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-white/70"></span>جيد (65 - 74)</span>
                            <span id="sumC" class="font-bold">0</span>
                        </li>
                        <li class="flex items-center justify-between">
                            <span class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-white/70"></span>متوسط (55 - 64)</span>
                            <span id="sumD" class="font-bold">0</span>
                        </li>
                        <li class="flex items-center justify-between">
                            <span class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-white/70"></span>مقبول (54 فما دون)</span>
                            <span id="sumF" class="font-bold">0</span>
                        </li>
                    </ul>

                    <div class="mt-5 pt-4 border-t border-white/20 flex items-center justify-between font-bold">
                        <span>الإجمالي</span>
                        <span id="sumTotal">0</span>
                    </div>
                </div>
            </div>

            </div>{{-- /dashMain --}}

            {{-- ===== Section: teachers management ===== --}}
            <div id="dashManage" class="space-y-6 {{ $section === 'manage' ? '' : 'hidden' }}">
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h3 class="font-bold text-gray-800 mb-4">إدارة المعلمين</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                        <a href="{{ route('teachers.create') }}"
                            class="flex items-center gap-3 rounded-xl border border-gray-200 bg-gray-50 px-5 py-4 text-sm font-medium text-gray-700 hover:border-brand-500 hover:bg-brand-50 transition">
                            <span class="text-2xl">➕</span><span>إضافة معلم</span>
                        </a>
                        <a href="{{ route('teachers.export') }}"
                            class="flex items-center gap-3 rounded-xl border border-gray-200 bg-gray-50 px-5 py-4 text-sm font-medium text-gray-700 hover:border-brand-500 hover:bg-brand-50 transition">
                            <span class="text-2xl">📥</span><span>تصدير Excel</span>
                        </a>
                        <form action="{{ route('teacher-grades.reset-all') }}" method="POST"
                            onsubmit="return confirm('هل أنت متأكد من حذف درجات جميع معلميك؟ لا يمكن التراجع عن هذا الإجراء.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-700 hover:bg-red-100 transition text-right">
                                <span class="text-2xl">🗑️</span><span>حذف كل الدرجات</span>
                            </button>
                        </form>
                    </div>
                </div>

            {{-- Row 2: stat tiles (also work as filters) --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

                <button type="button" id="cardAllTeachers"
                    class="stat-card bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4 text-right hover:shadow-md transition cursor-pointer">
                    <span class="w-12 h-12 shrink-0 rounded-full bg-brand-100 flex items-center justify-center text-2xl">👨‍🏫</span>
                    <span>
                        <span class="block text-2xl font-bold text-gray-800">{{ $totalTeachers }}</span>
                        <span class="block text-sm text-gray-500">إجمالي المعلمين</span>
                    </span>
                </button>

                <button type="button" id="cardAvgTotal"
                    class="stat-card bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4 text-right hover:shadow-md transition cursor-pointer">
                    <span class="w-12 h-12 shrink-0 rounded-full bg-violet-100 flex items-center justify-center text-2xl">📊</span>
                    <span>
                        <span class="block text-2xl font-bold text-gray-800">{{ number_format($avgTotal, 1) }}</span>
                        <span class="block text-sm text-gray-500">متوسط الدرجات</span>
                    </span>
                </button>

                <button type="button" id="cardHighestScore"
                    class="stat-card bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4 text-right hover:shadow-md transition cursor-pointer">
                    <span class="w-12 h-12 shrink-0 rounded-full bg-rose-100 flex items-center justify-center text-2xl">🏆</span>
                    <span>
                        <span class="block text-2xl font-bold text-gray-800">{{ $highestScore }}</span>
                        <span class="block text-sm text-gray-500">أعلى درجة</span>
                    </span>
                </button>

                <button type="button" id="cardExcellent" data-min-score="85"
                    class="stat-card bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4 text-right hover:shadow-md transition cursor-pointer">
                    <span class="w-12 h-12 shrink-0 rounded-full bg-brand-200 flex items-center justify-center text-2xl">⭐</span>
                    <span>
                        <span class="block text-2xl font-bold text-gray-800">{{ $excellentCount }}</span>
                        <span class="block text-sm text-gray-500">تقدير ممتاز</span>
                    </span>
                </button>

            </div>

            {{-- Teachers list (cards / table view) --}}
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
                    <h3 class="text-lg font-bold text-gray-800">المعلمون</h3>
                    <div class="flex bg-gray-100 rounded-lg p-1">
                        <button type="button" id="viewCardsBtn"
                            class="view-toggle-btn px-3 py-1.5 rounded-md text-sm font-bold transition">
                            🔲 بطاقات
                        </button>
                        <button type="button" id="viewTableBtn"
                            class="view-toggle-btn px-3 py-1.5 rounded-md text-sm font-bold transition">
                            🗂️ قائمة
                        </button>
                    </div>
                </div>

                {{-- Filters (instant, client-side — no page reload) --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-6">
                    <input type="text" id="searchInput"
                        placeholder="بحث (اسم، تخصص، مؤهل...)"
                        class="rounded-xl border-gray-200 bg-gray-50 px-3 py-2 text-sm md:col-span-2 focus:border-brand-500 focus:ring-brand-500">

                    <select id="schoolFilter" class="rounded-xl border-gray-200 bg-gray-50 px-3 py-2 text-sm focus:border-brand-500 focus:ring-brand-500">
                        <option value="">كل المدارس</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->School_ID }}">{{ $school->SchoolName }}</option>
                        @endforeach
                    </select>

                    {{-- Hidden — driven internally by the stat tiles, not user-editable --}}
                    <input type="hidden" id="minScoreFilter">
                    <input type="hidden" id="maxScoreFilter">

                    <button id="resetFilters" type="button"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded-xl text-sm transition">
                        إعادة تعيين
                    </button>
                </div>

                <div id="teachersCardGrid" class="relative pr-9 space-y-4">
                    {{-- Cards are rendered by JavaScript --}}
                </div>

                <div id="teachersTableWrap" class="hidden overflow-x-auto">
                    <table class="w-full text-right text-sm">
                        <thead>
                            <tr class="bg-brand-50 text-brand-700 border-b border-brand-100">
                                <th class="px-4 py-3">#</th>
                                <th class="px-4 py-3">رقم المعلم</th>
                                <th class="px-4 py-3">اسم المعلم</th>
                                <th class="px-4 py-3">المدرسة</th>
                                <th class="px-4 py-3">التخصص</th>
                                <th class="px-4 py-3">المؤهل</th>
                                <th class="px-4 py-3">تاريخ التعيين</th>
                                <th class="px-4 py-3">المجموع</th>
                                <th class="px-4 py-3">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody id="teachersTableBody">
                            {{-- Rows are rendered by JavaScript --}}
                        </tbody>
                    </table>
                </div>

                <div id="emptyState" class="hidden p-5 text-center text-gray-400">
                    لا يوجد معلمون مطابقون لهذا البحث
                </div>

                <div id="paginationControls" class="mt-5 flex items-center justify-between text-sm text-gray-600"></div>
            </div>
            </div>

            {{-- ===== Section: reports & printing (handled by the page's script via "dashboard-action") ===== --}}
            <div id="dashReports" class="{{ $section === 'reports' ? '' : 'hidden' }}">
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h3 class="font-bold text-gray-800 mb-4">التقارير والطباعة</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                        <button type="button"
                            onclick="window.dispatchEvent(new CustomEvent('dashboard-action', { detail: 'printAll' }))"
                            class="flex items-center gap-3 rounded-xl border border-gray-200 bg-gray-50 px-5 py-4 text-sm font-medium text-gray-700 hover:border-brand-500 hover:bg-brand-50 transition text-right">
                            <span class="text-2xl">🖨️</span><span>طباعة الكل</span>
                        </button>
                        <button type="button"
                            onclick="window.dispatchEvent(new CustomEvent('dashboard-action', { detail: 'printSummary' }))"
                            class="flex items-center gap-3 rounded-xl border border-gray-200 bg-gray-50 px-5 py-4 text-sm font-medium text-gray-700 hover:border-brand-500 hover:bg-brand-50 transition text-right">
                            <span class="text-2xl">📊</span><span>التقرير الموجز</span>
                        </button>
                        <button type="button"
                            onclick="window.dispatchEvent(new CustomEvent('dashboard-action', { detail: 'printList' }))"
                            class="flex items-center gap-3 rounded-xl border border-gray-200 bg-gray-50 px-5 py-4 text-sm font-medium text-gray-700 hover:border-brand-500 hover:bg-brand-50 transition text-right">
                            <span class="text-2xl">📄</span><span>قائمة المعلمين</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Data only — all behavior lives in resources/js/pages/supervisor-dashboard.js
        window.__SUPERVISOR_DASHBOARD__ = {
            teachers:       @json($teachersData),
            scoreCriteria:  @json($scoreCriteria),
            scoreGroups:    @json($scoreGroups),
            supervisorName: @json(Auth::guard('web')->user()->SuperVisor_Name ?? ''),
            highestScore:   @json($highestScore),
            csrf:           @json(csrf_token()),
            urls: {
                teachers:    @json(url('teachers')),
                reportsBulk: @json(route('teachers.reports.print')),
            },
        };
    </script>
    @vite('resources/js/pages/supervisor-dashboard.js')
</x-app-layout>