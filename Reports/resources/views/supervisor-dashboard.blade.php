<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-right">
            لوحة التحكم - {{ auth()->user()->SuperVisor_Name }}
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
    color: #2563eb;
}
</style>

    <div class="py-6" dir="rtl">
        <div class="max-w-[1700px] mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Stats Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">

                <button type="button" id="cardAllTeachers"
                    class="stat-card bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-5 text-white shadow-lg text-right hover:scale-[1.02] transition cursor-pointer">
                    <div class="text-4xl mb-2">👨‍🏫</div>
                    <div class="text-3xl font-bold">{{ $totalTeachers }}</div>
                    <div class="text-sm opacity-80 mt-1">إجمالي المعلمين</div>
                </button>

                <button type="button" id="cardAvgTotal"
                    class="stat-card bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl p-5 text-white shadow-lg text-right hover:scale-[1.02] transition cursor-pointer">
                    <div class="text-4xl mb-2">📊</div>
                    <div class="text-3xl font-bold">{{ number_format($avgTotal, 1) }}</div>
                    <div class="text-sm opacity-80 mt-1">متوسط الدرجات</div>
                </button>

                <button type="button" id="cardHighestScore"
                    class="stat-card bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl p-5 text-white shadow-lg text-right hover:scale-[1.02] transition cursor-pointer">
                    <div class="text-4xl mb-2">🏆</div>
                    <div class="text-3xl font-bold">{{ $highestScore }}</div>
                    <div class="text-sm opacity-80 mt-1">أعلى درجة</div>
                </button>

                <button type="button" id="cardExcellent" data-min-score="85"
                    class="stat-card bg-gradient-to-br from-green-500 to-green-700 rounded-2xl p-5 text-white shadow-lg text-right hover:scale-[1.02] transition cursor-pointer">
                    <div class="text-4xl mb-2">⭐</div>
                    <div class="text-3xl font-bold">{{ $excellentCount }}</div>
                    <div class="text-sm opacity-80 mt-1">تقدير ممتاز</div>
                </button>

            </div>

            {{-- My Teachers Table --}}
            <div class="bg-white rounded-2xl shadow p-5">
               <div class="flex justify-between items-center mb-4">
                    
                    <div class="flex items-center gap-3">
                    <h3 class="font-semibold text-gray-700">عرض بيانات المعلمين : </h3>
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
                
                </div>

                {{-- Academic year + bulk print --}}
                <div class="flex flex-wrap items-center gap-3 mb-4 bg-gray-50 rounded-lg p-4">
    <span class="text-sm font-bold text-gray-600">العام الدراسي:</span>
    <select id="academicYearSelect" class="rtl-select border border-gray-300 rounded-lg px-3 py-2 text-sm min-w-[120px]">
        <option value="2026/2027">2026/2027</option>
        <option value="2027/2028">2027/2028</option>
        <option value="2028/2029">2028/2029</option>
        <option value="2029/2030">2029/2030</option>
    </select>

    <span class="text-sm font-bold text-gray-600 mr-4">اطبع النطاق:</span>
    <span class="text-sm text-gray-500">من المعلم رقم:</span>
    <select id="rangeFromSelect" class=" rtl-select border border-gray-300 rounded-lg px-3 py-2 text-sm min-w-[180px]">
        <option value="">-- اختر --</option>
    </select>
    <span class="text-sm text-gray-500">إلى المعلم رقم:</span>
    <select id="rangeToSelect" class="border rtl-select border-gray-300 rounded-lg px-3 py-2 text-sm min-w-[180px]">
        <option value="">-- اختر --</option>
    </select>
    <button type="button" id="printRangeBtn"
        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-5 rounded-lg text-sm transition">
        🖨️ طباعة النطاق
    </button>
    <button type="button" id="printAllBtn"
        class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-5 rounded-lg text-sm transition">
        🖨️ طباعة الكل
    </button>
    <button type="button" id="printSummaryBtn"
        class="bg-teal-500 hover:bg-teal-600 text-white font-bold py-2 px-5 rounded-lg text-sm transition">
        📊 طباعة التقرير الموجز
    </button>
    <button type="button" id="printTeachersListBtn"
        class="bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-2 px-5 rounded-lg text-sm transition">
        🖨️ طباعة قائمة المعلمين
    </button>
</div>

                {{-- Filters (instant, client-side — no page reload) --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-5">
    <input type="text" id="searchInput"
        placeholder="بحث (اسم، تخصص، مؤهل...)"
        class="border border-gray-300 rounded-lg px-3 py-2 text-sm md:col-span-2">

    <select id="schoolFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
        <option value="">كل المدارس</option>
        @foreach($schools as $school)
            <option value="{{ $school->School_ID }}">{{ $school->SchoolName }}</option>
        @endforeach
    </select>

    {{-- Hidden — driven internally by the stat cards, not user-editable --}}
    <input type="hidden" id="minScoreFilter">
    <input type="hidden" id="maxScoreFilter">

    
        <button id="resetFilters" type="button"
            class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded-lg text-sm transition">
            إعادة تعيين
        </button>
    
</div>

                <div id="teachersCardGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    {{-- Cards are rendered by JavaScript --}}
                </div>

                <div id="teachersTableWrap" class="hidden overflow-x-auto">
                    <table class="w-full text-right text-sm">
                        <thead>
                            <tr class="bg-blue-50 text-blue-700 border-b border-blue-100">
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

                <div id="paginationControls" class="mt-4 flex items-center justify-between text-sm text-gray-600"></div>
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

        document.getElementById('printAllBtn').addEventListener('click', () => {
            window.open(routes.reportsBulk([]), '_blank');
        });

        document.getElementById('printSummaryBtn').addEventListener('click', () => {
            const summary = { scoreA: 0, scoreB: 0, scoreC: 0, scoreD: 0, scoreF: 0 };

            allTeachers.forEach(t => {
                const total = t.total ?? 0;
                if (total >= 85) summary.scoreA++;
                else if (total >= 75) summary.scoreB++;
                else if (total >= 65) summary.scoreC++;
                else if (total >= 55) summary.scoreD++;
                else summary.scoreF++;
            });

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
            background:#2563eb; color:#fff; border:none; padding:8px 18px;
            border-radius:8px; font-weight:bold; cursor:pointer; font-size:14px;
        }
        .toolbar button:hover { background:#1d4ed8; }
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
                <td>عدد المعلمين بتقدير متوسط (55 -