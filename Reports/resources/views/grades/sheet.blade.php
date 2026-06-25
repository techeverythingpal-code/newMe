<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <a href="{{ route('teachers.index') }}"
                class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-5 rounded-lg transition">
                ← العودة
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📊 جدول إدخال الدرجات
            </h2>
        </div>
    </x-slot>

    <div class="py-8" dir="rtl">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">

            <div class="mb-3 flex items-center gap-3 text-sm">
                <span id="save-indicator" class="hidden items-center gap-2 text-blue-600 font-semibold">
                    <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24" fill="none">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.4 0 0 5.4 0 12h4z"></path>
                    </svg>
                    جاري الحفظ...
                </span>
                <span id="saved-indicator" class="hidden text-green-600 font-semibold">✓ تم الحفظ</span>
                <span id="error-indicator" class="hidden text-red-600 font-semibold">⚠ حدث خطأ في الحفظ</span>
            </div>

            <div class="bg-white shadow-sm rounded-2xl overflow-hidden">
                <div class="sheet-wrap" dir="rtl">

                    <div class="frozen-pane" id="frozenPane">
                        <table class="grades-sheet frozen-table">
                            <colgroup>
                                <col class="col-w-name">
                                <col class="col-w-school">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>اسم المعلم</th>
                                    <th>المدرسة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($teachers as $teacher)
                                    <tr>
                                        <td title="{{ $teacher->Teacher_Name }}">{{ $teacher->Teacher_Name }}</td>
                                        <td title="{{ $teacher->school->SchoolName ?? '' }}">{{ $teacher->school->SchoolName ?? '—' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="text-center text-gray-400">لا يوجد معلمون</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="scroll-pane" id="scrollPane">
                        <table class="grades-sheet scroll-table" dir="rtl">
                            <colgroup>
                                @foreach ($scores as $field => [$label, $max])
                                    <col class="col-w-score">
                                @endforeach
                                <col class="col-w-total">
                            </colgroup>
                            <thead>
                                <tr>
                                    @foreach ($scores as $field => [$label, $max])
                                        <th class="score-header" title="{{ $label }}">
                                            <div class="score-header-inner">
                                                <span class="score-num">{{ $loop->iteration }}</span>
                                                <span class="score-label">{{ $label }}</span>
                                                <span class="score-max">/ {{ $max }}</span>
                                            </div>
                                        </th>
                                    @endforeach
                                    <th class="total-header">المجموع</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($teachers as $teacher)
                                    <tr data-teacher-id="{{ $teacher->Teacher_id }}">
                                        @foreach ($scores as $field => [$label, $max])
                                            <td class="score-cell">
                                                <input
                                                    type="number"
                                                    class="score-input"
                                                    data-field="{{ $field }}"
                                                    min="0"
                                                    max="{{ $max }}"
                                                    value="{{ $teacher->grades->$field ?? 0 }}">
                                            </td>
                                        @endforeach
                                        <td class="total-cell">
                                            <span class="row-total">{{ $teacher->grades->total ?? 0 }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ count($scores) + 1 }}" class="text-center py-8 text-gray-400">
                                            لا يوجد معلمون لعرضهم
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            <p class="text-xs text-gray-400 mt-3">
                * يتم حفظ كل صف تلقائياً بعد تعديل أي درجة فيها (عند الخروج من الحقل).
            </p>
        </div>
    </div>

    <style>
        .sheet-wrap {
            display: flex;
            max-height: 80vh;
            border-top: 1px solid #e5e7eb;
        }

        .frozen-pane {
            overflow-y: auto;
            overflow-x: hidden;
            flex-shrink: 0;
            border-left: 2px solid #d1d5db;
        }
        .frozen-pane::-webkit-scrollbar { display: none; }

        .scroll-pane {
            overflow-x: auto;
            overflow-y: auto;
            flex: 1;
        }

        .grades-sheet {
            border-collapse: separate;
            border-spacing: 0;
            table-layout: fixed;
            font-size: 13px;
        }
        .grades-sheet th,
        .grades-sheet td {
            border: 1px solid #e5e7eb;
            box-sizing: border-box;
            overflow: hidden;
            height: 42px;
        }
        .grades-sheet thead th {
            position: sticky;
            top: 0;
            background-color: #eef2ff;
            color: #374151;
            z-index: 2;
            padding: 6px 4px;
            font-weight: 600;
            height: 70px;
        }
        .frozen-table thead th {
            vertical-align: middle;
        }

        .col-w-name   { width: 170px; }
        .col-w-school { width: 150px; }
        .frozen-table { width: 320px; }
        .frozen-table td {
            padding: 8px 10px;
            text-align: right;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
        .frozen-table td:first-child { font-weight: 700; color: #1f2937; }
        .frozen-table td:last-child  { color: #4b5563; }

        .col-w-score { width: 84px; }
        .col-w-total { width: 84px; }
        .scroll-table { width: max-content; min-width: 100%; }

        .score-header { padding: 4px 2px; }
        .score-header-inner {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            line-height: 1.15;
            white-space: normal;
            overflow-wrap: break-word;
            word-break: break-word;
            font-size: 10.5px;
            max-height: 64px;
            overflow: hidden;
        }
        .score-header .score-num  { font-size: 10px; color: #9ca3af; }
        .score-header .score-label {
            color: #374151;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .score-header .score-max { font-weight: 700; color: #4f46e5; }

        .score-cell { padding: 2px; text-align: center; }
        .score-input {
            width: 100%;
            max-width: 56px;
            text-align: center;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 4px 2px;
            font-weight: 600;
            box-sizing: border-box;
        }
        .score-input:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 2px rgba(79,70,229,0.2);
        }
        .score-input.row-error { border-color: #ef4444; background: #fef2f2; }

        .total-header { background: #e0e7ff !important; }
        .total-cell {
            text-align: center;
            font-weight: 700;
            color: #4338ca;
            background: #eef2ff;
        }

        .row-hover td { background: #f9fafb !important; }
        .frozen-table .row-hover td { background: #f3f4f6 !important; }
        .scroll-table .row-hover .total-cell { background: #e0e7ff !important; }
    </style>

    <script>
        const frozenPane = document.getElementById('frozenPane');
        const scrollPane = document.getElementById('scrollPane');

        let syncingFromFrozen = false;
        let syncingFromScroll = false;

        frozenPane.addEventListener('scroll', () => {
            if (syncingFromScroll) { syncingFromScroll = false; return; }
            syncingFromFrozen = true;
            scrollPane.scrollTop = frozenPane.scrollTop;
        });
        scrollPane.addEventListener('scroll', () => {
            if (syncingFromFrozen) { syncingFromFrozen = false; return; }
            syncingFromScroll = true;
            frozenPane.scrollTop = scrollPane.scrollTop;
        });

        const frozenRows = frozenPane.querySelectorAll('tbody tr');
        const scrollRows = scrollPane.querySelectorAll('tbody tr');
        frozenRows.forEach((row, i) => {
            const partner = scrollRows[i];
            if (!partner) return;
            row.addEventListener('mouseenter', () => { row.classList.add('row-hover'); partner.classList.add('row-hover'); });
            row.addEventListener('mouseleave', () => { row.classList.remove('row-hover'); partner.classList.remove('row-hover'); });
            partner.addEventListener('mouseenter', () => { row.classList.add('row-hover'); partner.classList.add('row-hover'); });
            partner.addEventListener('mouseleave', () => { row.classList.remove('row-hover'); partner.classList.remove('row-hover'); });
        });

        const saveIndicator  = document.getElementById('save-indicator');
        const savedIndicator = document.getElementById('saved-indicator');
        const errorIndicator = document.getElementById('error-indicator');
        let hideTimer = null;

        function showStatus(state) {
            saveIndicator.classList.add('hidden');
            savedIndicator.classList.add('hidden');
            errorIndicator.classList.add('hidden');
            clearTimeout(hideTimer);

            if (state === 'saving') {
                saveIndicator.classList.remove('hidden');
                saveIndicator.classList.add('flex');
            } else if (state === 'saved') {
                savedIndicator.classList.remove('hidden');
                hideTimer = setTimeout(() => savedIndicator.classList.add('hidden'), 1500);
            } else if (state === 'error') {
                errorIndicator.classList.remove('hidden');
                hideTimer = setTimeout(() => errorIndicator.classList.add('hidden'), 3000);
            }
        }

        document.querySelectorAll('.scroll-table tbody tr[data-teacher-id]').forEach(row => {
            const teacherId = row.dataset.teacherId;
            const inputs = row.querySelectorAll('.score-input');
            const totalSpan = row.querySelector('.row-total');

            function liveTotal() {
                let total = 0;
                inputs.forEach(inp => total += parseInt(inp.value) || 0);
                totalSpan.textContent = total;
            }

            inputs.forEach(input => {
                input.addEventListener('input', liveTotal);

                input.addEventListener('change', async () => {
                    const max = parseInt(input.max);
                    let val = parseInt(input.value);
                    if (isNaN(val) || val < 0) val = 0;
                    if (val > max) val = max;
                    input.value = val;
                    liveTotal();

                    const payload = {};
                    inputs.forEach(inp => payload[inp.dataset.field] = parseInt(inp.value) || 0);

                    showStatus('saving');
                    try {
                        const res = await fetch(`/teachers/${teacherId}/grades/quick`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify(payload),
                        });

                        if (!res.ok) throw new Error('save failed');
                        const data = await res.json();
                        totalSpan.textContent = data.total;
                        inputs.forEach(inp => inp.classList.remove('row-error'));
                        showStatus('saved');
                    } catch (err) {
                        inputs.forEach(inp => inp.classList.add('row-error'));
                        showStatus('error');
                    }
                });
            });
        });
    </script>
</x-app-layout>