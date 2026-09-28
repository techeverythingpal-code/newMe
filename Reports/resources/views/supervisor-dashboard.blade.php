<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-right">
            لوحة التحكم
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
        <div class="max-w-[1700px] mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

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

            {{-- Row 3: teachers list + grade summary --}}
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-start">

                {{-- Teachers card --}}
                <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm p-6">
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

        </div>
    </div>

    <script>
        // All teacher data, fetched once from the server — filtering happens here in the browser
        const allTeachers = {!! json_encode($teachersData) !!};
        const scoreCriteria = @json($scoreCriteria);
        const scoreGroups   = @json($scoreGroups);
        window.allTeachersData  = allTeachers;
        window.scoreCriteriaData = scoreCriteria;
        window.scoreGroupsData   = scoreGroups;
        window.currentSupervisorName = @json(Auth::guard('web')->user()->SuperVisor_Name ?? '');
        const PAGE_SIZE = 9;
        let currentPage = 1;
        let viewMode = localStorage.getItem('teachersViewMode') || 'cards';

        const routes = {
            show:           id => "{{ url('teachers') }}/" + id,
            justification:  id => "{{ url('teachers') }}/" + id + "/justification",
            supervisorNote: id => "{{ url('teachers') }}/" + id + "/supervisor-note",
            report:         id => "{{ url('teachers') }}/" + id + "/report/print",
            edit:        id => "{{ url('teachers') }}/" + id + "/edit",
            destroy:     id => "{{ url('teachers') }}/" + id,
            resetScores: id => "{{ url('teachers') }}/" + id + "/grades/reset",
        };

        // Academic year selector — saved per-browser so it persists across visits
        const academicYearSelect = document.getElementById('academicYearSelect');
        const savedYear = localStorage.getItem('academicYear');
        if (savedYear) academicYearSelect.value = savedYear;

        academicYearSelect.addEventListener('change', () => {
            localStorage.setItem('academicYear', academicYearSelect.value);
        });

        function getAcademicYear() {
            return academicYearSelect.value;
        }

        routes.reportsBulk = (ids) => {
            const base = "{{ route('teachers.reports.print') }}";
            const yearParam = 'academic_year=' + encodeURIComponent(getAcademicYear());
            if (!ids || ids.length === 0) return base + '?' + yearParam;
            const idsParam = ids.map(id => 'ids[]=' + encodeURIComponent(id)).join('&');
            return base + '?' + idsParam + '&' + yearParam;
        };

        const rangeFromSelect = document.getElementById('rangeFromSelect');
        const rangeToSelect   = document.getElementById('rangeToSelect');

        allTeachers.forEach((t, idx) => {
            const label = (idx + 1) + ' - ' + t.name;

            const opt1 = document.createElement('option');
            opt1.value = idx;
            opt1.textContent = label;
            rangeFromSelect.appendChild(opt1);

            const opt2 = document.createElement('option');
            opt2.value = idx;
            opt2.textContent = label;
            rangeToSelect.appendChild(opt2);
        });

        document.getElementById('printRangeBtn').addEventListener('click', () => {
            const fromIdx = rangeFromSelect.value;
            const toIdx   = rangeToSelect.value;

            if (fromIdx === '' || toIdx === '') {
                alert('يرجى اختيار نطاق المعلمين أولاً');
                return;
            }

            const start = Math.min(Number(fromIdx), Number(toIdx));
            const end   = Math.max(Number(fromIdx), Number(toIdx));
            const ids   = allTeachers.slice(start, end + 1).map(t => t.id);

            window.open(routes.reportsBulk(ids), '_blank');
        });

        // ---- Grade distribution (used by the summary card and the summary report) ----

        function computeSummary() {
            const summary = { scoreA: 0, scoreB: 0, scoreC: 0, scoreD: 0, scoreF: 0 };

            allTeachers.forEach(t => {
                const total = t.total ?? 0;
                if (total >= 85) summary.scoreA++;
                else if (total >= 75) summary.scoreB++;
                else if (total >= 65) summary.scoreC++;
                else if (total >= 55) summary.scoreD++;
                else summary.scoreF++;
            });

            return summary;
        }

        function renderSummaryCard() {
            const s = computeSummary();
            document.getElementById('sumA').textContent = s.scoreA;
            document.getElementById('sumB').textContent = s.scoreB;
            document.getElementById('sumC').textContent = s.scoreC;
            document.getElementById('sumD').textContent = s.scoreD;
            document.getElementById('sumF').textContent = s.scoreF;
            document.getElementById('sumTotal').textContent = allTeachers.length;
        }

        // ---- Report actions (triggered from the sidebar buttons) ----

        function printAll() {
            window.open(routes.reportsBulk([]), '_blank');
        }

        function printSummary() {
            const summary = computeSummary();

            const totalTeachersCount = allTeachers.length;
            const directorateName    = allTeachers[0]?.directorate || '';
            const academicYear       = getAcademicYear();
            const supervisorName     = window.currentSupervisorName || '';
            const dateStr = new Date().toLocaleDateString('ar-EG', {
                year: 'numeric', month: 'long', day: 'numeric',
            });

            const html = `<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>تقرير إحصائي موجز</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Tahoma','Arial',sans-serif; color:#111; background:#f3f4f6; margin:0; padding:20px; }
        .toolbar { max-width:800px; margin:0 auto 14px; display:flex; justify-content:flex-end; gap:8px; }
        .toolbar button {
            background:#5651AB; color:#fff; border:none; padding:8px 18px;
            border-radius:8px; font-weight:bold; cursor:pointer; font-size:14px;
        }
        .toolbar button:hover { background:#45408E; }
        .summary-report-page { max-width: 800px; margin: 0 auto 20px; padding: 20px; background:#fff; }
        .summary-report-header { text-align: center; margin-bottom: 16px; }
        .summary-report-header p { font-size: 13px; margin: 2px 0; }
        .summary-report-header h3 { font-size: 18px; margin: 10px 0 0; border-bottom: 2px solid #333; padding-bottom: 8px; }
        table.summary-table { width: 100%; border-collapse: collapse; font-size: 13px; margin-top: 10px; }
        table.summary-table th, table.summary-table td { border: 1px solid #333; padding: 8px 10px; text-align: right; }
        table.summary-table thead th { background: #e5e7eb; font-weight: bold; text-align: center; }
        table.summary-table td:last-child { text-align: center; width: 20%; }
        table.summary-table tr.total-row td { background: #f3f4f6; }
        .summary-report-footer { display: flex; justify-content: space-between; margin-top: 24px; font-size: 13px; }
        @media print {
            @page { size: A4 portrait; margin: 0.5cm; }
            body { padding: 0; background: #fff; }
            .toolbar { display: none; }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <button onclick="window.print()">🖨️ طباعة التقرير</button>
    </div>
    <div class="summary-report-page">
        <div class="summary-report-header">
            <p>مديرية التربية والتعليم / ${escapeHtml(directorateName)}</p>
            <p>العام الدراسي: ${escapeHtml(academicYear)}</p>
            <h3>تقرير إحصائي موجز لنتائج تقييم المعلمين</h3>
        </div>
        <table class="summary-table">
            <thead>
            <tr><th colspan="2">فئات التقدير وعدد المعلمين</th></tr>
            </thead>
            <tbody>
            <tr>
                <td>عدد المعلمين بتقدير ممتاز (85 فأكثر)</td>
                <td>${summary.scoreA}</td>
            </tr>
            <tr>
                <td>عدد المعلمين بتقدير جيد جداً (75 - 84)</td>
                <td>${summary.scoreB}</td>
            </tr>
            <tr>
                <td>عدد المعلمين بتقدير جيد (65 - 74)</td>
                <td>${summary.scoreC}</td>
            </tr>
            <tr>
                <td>عدد المعلمين بتقدير متوسط (55 - 64)</td>
                <td>${summary.scoreD}</td>
            </tr>
            <tr>
                <td>عدد المعلمين بتقدير مقبول (54 فما دون)</td>
                <td>${summary.scoreF}</td>
            </tr>
            <tr class="total-row">
                <td><strong>العدد الكلي للمعلمين</strong></td>
                <td><strong>${totalTeachersCount}</strong></td>
            </tr>
            </tbody>
        </table>
        <div class="summary-report-footer">
            <p><strong>اسم المشرف:</strong> ${escapeHtml(supervisorName)}</p>
            <p><strong>التاريخ:</strong> ${dateStr}</p>
            <p><strong>التوقيع:</strong> .........................</p>
        </div>
    </div>
</body>
</html>`;

            const win = window.open('', '_blank');
            win.document.write(html);
            win.document.close();
        }

        function printList() {
            const directorateName = allTeachers[0]?.directorate || '';
            const academicYear     = getAcademicYear();
            const supervisorName   = window.currentSupervisorName || '';
            const dateStr = new Date().toLocaleDateString('ar-EG', {
                year: 'numeric', month: 'long', day: 'numeric',
            });

            const columns = [
                { key: 'name',    label: 'اسم المعلم' },
                { key: 'school',  label: 'المدرسة' },
                { key: 'major',   label: 'التخصص' },
                { key: 'qualify', label: 'المؤهل' },
                { key: 'date',    label: 'تاريخ التعيين' },
            ];

            let rowsHTML = '';
            allTeachers.forEach((t, index) => {
                rowsHTML += `<tr>
                    <td>${index + 1}</td>
                    ${columns.map(c => `<td>${escapeHtml(t[c.key])}</td>`).join('')}
                    <td>${t.total}</td>
                    <td>${escapeHtml(t.assessment?.label || '')}</td>
                </tr>`;
            });

            const html = `<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>قائمة المعلمين</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Tahoma','Arial',sans-serif; color:#111; background:#f3f4f6; margin:0; padding:20px; }
        .toolbar { max-width:1000px; margin:0 auto 14px; display:flex; justify-content:flex-end; gap:8px; }
        .toolbar button {
            background:#5651AB; color:#fff; border:none; padding:8px 18px;
            border-radius:8px; font-weight:bold; cursor:pointer; font-size:14px;
        }
        .toolbar button:hover { background:#45408E; }
        .print-report-container { max-width: 1000px; margin: 0 auto 20px; padding: 20px; background:#fff; }
        .report-header { text-align: center; margin-bottom: 16px; }
        .report-title-text { font-size: 13px; margin: 2px 0; }
        .report-header h2 { font-size: 18px; margin: 10px 0 10px; border-bottom: 2px solid #333; padding-bottom: 8px; }
        .report-metadata { display: flex; justify-content: space-between; font-size: 12.5px; margin-top: 8px; }
        table.data-list-table { width: 100%; border-collapse: collapse; font-size: 12px; margin-top: 10px; }
        table.data-list-table th, table.data-list-table td { border: 1px solid #333; padding: 6px 8px; text-align: center; }
        table.data-list-table thead th { background: #e5e7eb; font-weight: bold; }
        table.data-list-table td:nth-child(2) { text-align: right; }
        @media print {
            @page { size: A4 landscape; margin: 0.5cm; }
            body { padding: 0; background: #fff; }
            .toolbar { display: none; }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <button onclick="window.print()">🖨️ طباعة القائمة</button>
    </div>
    <div class="print-report-container">
        <header class="report-header">
            <div class="report-title-text">مديرية التربية والتعليم - ${escapeHtml(directorateName)}</div>
            <div class="report-title-text">قسم التعليم المدرسي</div>
            <h2>قائمة المعلمين</h2>
            <div class="report-metadata">
                <div>العام الدراسي: ${escapeHtml(academicYear)}</div>
                <div>المشرف: ${escapeHtml(supervisorName)}</div>
                <div>تاريخ الطباعة: ${dateStr}</div>
            </div>
        </header>
        <main>
            <table class="data-list-table">
                <thead>
                    <tr>
                        <th>#</th>
                        ${columns.map(c => `<th>${c.label}</th>`).join('')}
                        <th>المجموع</th>
                        <th>التقدير</th>
                    </tr>
                </thead>
                <tbody>
                    ${rowsHTML}
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>`;

            const win = window.open('', '_blank');
            win.document.write(html);
            win.document.close();
        }

        // The sidebar buttons dispatch "dashboard-action" events; run the matching function
        const dashboardActions = { printAll, printSummary, printList };
        window.addEventListener('dashboard-action', (e) => {
            const action = dashboardActions[e.detail];
            if (action) action();
        });

        const searchInput    = document.getElementById('searchInput');
        const schoolFilter   = document.getElementById('schoolFilter');
        const minScoreFilter = document.getElementById('minScoreFilter');
        const maxScoreFilter = document.getElementById('maxScoreFilter');
        const resetBtn       = document.getElementById('resetFilters');
        const cardGrid       = document.getElementById('teachersCardGrid');
        const tableWrap      = document.getElementById('teachersTableWrap');
        const tableBody      = document.getElementById('teachersTableBody');
        const viewCardsBtn   = document.getElementById('viewCardsBtn');
        const viewTableBtn   = document.getElementById('viewTableBtn');
        const emptyState     = document.getElementById('emptyState');
        const paginationEl   = document.getElementById('paginationControls');

        const statCards      = document.querySelectorAll('.stat-card');

        function setActiveCard(card) {
            statCards.forEach(c => c.classList.remove('ring-2', 'ring-brand-500'));
            if (card) card.classList.add('ring-2', 'ring-brand-500');
        }

        function updateViewToggleUI() {
            const activeClasses   = ['bg-white', 'shadow', 'text-brand-600'];
            const inactiveClasses = ['text-gray-500'];

            viewCardsBtn.classList.remove(...activeClasses, ...inactiveClasses);
            viewTableBtn.classList.remove(...activeClasses, ...inactiveClasses);

            (viewMode === 'cards' ? viewCardsBtn : viewTableBtn).classList.add(...activeClasses);
            (viewMode === 'cards' ? viewTableBtn : viewCardsBtn).classList.add(...inactiveClasses);

            cardGrid.classList.toggle('hidden', viewMode !== 'cards');
            tableWrap.classList.toggle('hidden', viewMode !== 'table');
        }

        viewCardsBtn.addEventListener('click', () => {
            viewMode = 'cards';
            localStorage.setItem('teachersViewMode', viewMode);
            updateViewToggleUI();
            renderTable();
        });

        viewTableBtn.addEventListener('click', () => {
            viewMode = 'table';
            localStorage.setItem('teachersViewMode', viewMode);
            updateViewToggleUI();
            renderTable();
        });

        updateViewToggleUI();

        function scrollToList() {
            document.getElementById('teachersCardGrid').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        document.getElementById('cardAllTeachers').addEventListener('click', () => {
            searchInput.value = '';
            schoolFilter.value = '';
            minScoreFilter.value = '';
            maxScoreFilter.value = '';
            currentPage = 1;
            renderTable();
            setActiveCard(document.getElementById('cardAllTeachers'));
            scrollToList();
        });

        document.getElementById('cardAvgTotal').addEventListener('click', () => {
            setActiveCard(document.getElementById('cardAvgTotal'));
            scrollToList();
        });

        document.getElementById('cardHighestScore').addEventListener('click', () => {
            minScoreFilter.value = {{ $highestScore }};
            maxScoreFilter.value = '';
            currentPage = 1;
            renderTable();
            setActiveCard(document.getElementById('cardHighestScore'));
            scrollToList();
        });

        document.getElementById('cardExcellent').addEventListener('click', (e) => {
            const min = e.currentTarget.dataset.minScore;
            minScoreFilter.value = min;
            maxScoreFilter.value = '';
            currentPage = 1;
            renderTable();
            setActiveCard(document.getElementById('cardExcellent'));
            scrollToList();
        });

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

        const assessmentColorClasses = {
            green:  'bg-green-100 text-green-700',
            blue:   'bg-blue-100 text-blue-700',
            yellow: 'bg-yellow-100 text-yellow-700',
            orange: 'bg-orange-100 text-orange-700',
            red:    'bg-red-100 text-red-700',
            gray:   'bg-gray-100 text-gray-500',
        };

        // Soft pastel tones for the teacher cards (cycled by position)
        const cardTones = ['bg-brand-100/70', 'bg-violet-100/60', 'bg-rose-100/60'];

        // Action buttons on the pastel cards: white pills, text color keeps the meaning
        const actionBtn       = 'bg-white/80 hover:bg-white text-brand-700 font-bold py-1 px-3 rounded-lg text-xs transition';
        const actionBtnWarn   = 'bg-white/80 hover:bg-orange-100 text-orange-600 font-bold py-1 px-3 rounded-lg text-xs transition';
        const actionBtnDanger = 'bg-white/80 hover:bg-red-100 text-red-600 font-bold py-1 px-3 rounded-lg text-xs transition';

        function renderTable() {
            const filtered = getFiltered();
            const totalPages = Math.max(1, Math.ceil(filtered.length / PAGE_SIZE));
            if (currentPage > totalPages) currentPage = totalPages;

            const start = (currentPage - 1) * PAGE_SIZE;
            const pageItems = filtered.slice(start, start + PAGE_SIZE);

            emptyState.classList.toggle('hidden', pageItems.length > 0);

            if (viewMode === 'cards') {
                renderCards(pageItems, start);
            } else {
                renderRows(pageItems, start);
            }

            renderPagination(totalPages, filtered.length);
        }

        function renderCards(pageItems, start) {
            cardGrid.innerHTML = '';

            pageItems.forEach((t, index) => {
                const tone = cardTones[(start + index) % cardTones.length];
                const card = document.createElement('div');
                card.className = 'relative rounded-2xl p-4 hover:shadow-md transition ' + tone;
                card.innerHTML = `
                    <div class="absolute -right-9 top-6 w-4 h-4 rounded-full border-4 border-brand-500 ${t.total >= 85 ? 'bg-brand-500' : 'bg-white'}"></div>

                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <div class="font-bold text-gray-800 text-base">${escapeHtml(t.name)}${t.total >= 85 ? ' ⭐' : ''}</div>
                            <div class="text-sm text-gray-500 mt-1">🏫 ${escapeHtml(t.school)}</div>
                            <div class="text-xs text-gray-400 mt-1">🎓 ${escapeHtml(t.major)} · #${start + index + 1}</div>
                        </div>
                        <div class="flex flex-col items-end gap-2 shrink-0">
                            <span class="${assessmentColorClasses[t.assessment.color] || assessmentColorClasses.gray} px-3 py-1 rounded-full text-xs font-bold">
                                ${escapeHtml(t.assessment.label)}
                            </span>
                            <span class="bg-white/80 text-brand-700 px-3 py-1 rounded-full text-xs font-bold" dir="ltr">
                                ${t.total} / 100
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 pt-3 mt-3 border-t border-white/70">
                        ${t.total >= 85 ? `
                        <a href="${routes.justification(t.id)}" class="${actionBtn}">
                            📝 نموذج التبرير
                        </a>` : ''}
                        <button type="button" class="note-toggle-btn ${actionBtn}">
                            🗒️ ملاحظات المشرف
                        </button>
                        <button type="button" onclick="window.open(routes.report(${t.id}) + '?academic_year=' + encodeURIComponent(getAcademicYear()), '_blank')" class="${actionBtn}">
                            🖨️ طباعة
                        </button>
                        <a href="${routes.edit(t.id)}" class="${actionBtn}">
                            ✏️ تعديل
                        </a>
                        <form action="${routes.resetScores(t.id)}" method="POST"
                            onsubmit="return confirm('هل أنت متأكد من حذف درجات هذا المعلم؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="${actionBtnWarn}">
                                🗑️ حذف الدرجات
                            </button>
                        </form>
                        <form action="${routes.destroy(t.id)}" method="POST"
                            onsubmit="return confirm('هل أنت متأكد؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="${actionBtnDanger}">
                                🗑️ حذف
                            </button>
                        </form>
                        <a href="${routes.show(t.id)}" title="عرض"
                            class="mr-auto w-9 h-9 rounded-full bg-brand-500 hover:bg-brand-600 text-white flex items-center justify-center transition">
                            ←
                        </a>
                    </div>

                    <div class="note-box hidden mt-3 pt-3 border-t border-white/70">
                        <textarea class="note-textarea w-full text-xs border border-gray-200 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-brand-300" rows="2" maxlength="250" placeholder="اكتب ملاحظة...">${escapeHtml(t.supervisor_note)}</textarea>
                        <div class="flex justify-between items-center mt-1">
                            <span class="note-counter text-xs text-gray-400">0/250</span>
                            <div class="flex items-center gap-2">
                                <span class="note-status text-xs text-gray-400"></span>
                                <button type="button" class="note-save-btn ${actionBtn}">
                                    حفظ الملاحظة
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                cardGrid.appendChild(card);

                const noteBox    = card.querySelector('.note-box');
                const noteToggle = card.querySelector('.note-toggle-btn');
                const noteSave   = card.querySelector('.note-save-btn');
                const noteText   = card.querySelector('.note-textarea');
                const noteStatus = card.querySelector('.note-status');
                const noteCounter = card.querySelector('.note-counter');

                function updateNoteCounter() {
                    const len = noteText.value.length;
                    noteCounter.textContent = len + '/250';
                    noteCounter.classList.toggle('text-red-500', len >= 250);
                    noteCounter.classList.toggle('text-gray-400', len < 250);
                }

                updateNoteCounter();
                noteText.addEventListener('input', updateNoteCounter);

                noteToggle.addEventListener('click', () => noteBox.classList.toggle('hidden'));

                noteSave.addEventListener('click', async () => {
                    noteStatus.textContent = 'جاري الحفظ...';
                    try {
                        const res = await fetch(routes.supervisorNote(t.id), {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({ supervisor_note: noteText.value }),
                        });
                        if (!res.ok) throw new Error();
                        noteStatus.textContent = '✓ تم الحفظ';
                        t.supervisor_note = noteText.value;
                        setTimeout(() => noteStatus.textContent = '', 1500);
                    } catch {
                        noteStatus.textContent = '⚠ خطأ في الحفظ';
                    }
                });
            });
        }

        function renderRows(pageItems, start) {
            tableBody.innerHTML = '';

            pageItems.forEach((t, index) => {
                const row = document.createElement('tr');
                row.className = 'border-b border-gray-100 hover:bg-brand-50 transition' + (t.total >= 85 ? ' bg-brand-50/40' : '');
                row.innerHTML = `
                    <td class="px-4 py-3 text-gray-400">${start + index + 1}</td>
                    <td class="px-4 py-3 font-bold text-brand-600">${escapeHtml(String(t.id))}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">${escapeHtml(t.name)}</td>
                    <td class="px-4 py-3 text-gray-600">${escapeHtml(t.school)}</td>
                    <td class="px-4 py-3 text-gray-600">${escapeHtml(t.major)}</td>
                    <td class="px-4 py-3 text-gray-600">${escapeHtml(t.qualify)}</td>
                    <td class="px-4 py-3 text-gray-600">${escapeHtml(t.date)}</td>
                    <td class="px-4 py-3">
                        <span class="bg-brand-100 text-brand-700 px-3 py-1 rounded-full text-xs font-bold" dir="ltr" style="display:inline-block">
                            ${t.total} / 100
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex gap-1.5 justify-end items-center flex-nowrap">
                            <a href="${routes.show(t.id)}" title="عرض"
                                class="bg-brand-100 hover:bg-brand-200 text-brand-700 w-7 h-7 flex items-center justify-center rounded-lg text-xs transition">
                                👁️
                            </a>
                            <a href="${routes.edit(t.id)}" title="تعديل"
                                class="bg-brand-100 hover:bg-brand-200 text-brand-700 w-7 h-7 flex items-center justify-center rounded-lg text-xs transition">
                                ✏️
                            </a>
                            <button type="button" title="طباعة" onclick="window.open(routes.report(${t.id}) + '?academic_year=' + encodeURIComponent(getAcademicYear()), '_blank')"
                                class="bg-gray-100 hover:bg-gray-200 text-gray-700 w-7 h-7 flex items-center justify-center rounded-lg text-xs transition">
                                🖨️
                            </button>
                            <form action="${routes.resetScores(t.id)}" method="POST"
                                onsubmit="return confirm('هل أنت متأكد من حذف درجات هذا المعلم؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="حذف الدرجات"
                                    class="bg-orange-100 hover:bg-orange-200 text-orange-700 w-7 h-7 flex items-center justify-center rounded-lg text-xs transition">
                                    🗑️
                                </button>
                            </form>
                            <form action="${routes.destroy(t.id)}" method="POST"
                                onsubmit="return confirm('هل أنت متأكد؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="حذف"
                                    class="bg-red-100 hover:bg-red-200 text-red-700 w-7 h-7 flex items-center justify-center rounded-lg text-xs transition">
                                    ❌
                                </button>
                            </form>
                        </div>
                    </td>
                `;
                tableBody.appendChild(row);
            });
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

        // Profile card + summary card (filled from the data already on the page)
        function updateProfileYear() {
            document.getElementById('profileYear').textContent = getAcademicYear();
        }

        document.getElementById('profileDirectorate').textContent = allTeachers[0]?.directorate || '—';
        document.getElementById('profileSchools').textContent = new Set(allTeachers.map(t => t.school_id)).size;
        updateProfileYear();
        academicYearSelect.addEventListener('change', updateProfileYear);
        renderSummaryCard();

        renderTable();
    </script>
</x-app-layout>