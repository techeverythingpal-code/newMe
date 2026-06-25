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
                <div class="sheet-scroll">
                    <table class="grades-sheet" dir="rtl">
                        <colgroup>
                            <col class="col-w-name">
                            <col class="col-w-school">
                            @foreach ($scores as $field => [$label, $max])
                                <col class="col-w-score">
                            @endforeach
                            <col class="col-w-total">
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="sticky-col col-name">اسم المعلم</th>
                                <th class="sticky-col col-school">المدرسة</th>
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
                                    <td class="sticky-col col-name" title="{{ $teacher->Teacher_Name }}">
                                        {{ $teacher->Teacher_Name }}
                                    </td>
                                    <td class="sticky-col col-school" title="{{ $teacher->school->SchoolName ?? '' }}">
                                        {{ $teacher->school->SchoolName ?? '—' }}
                                    </td>
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
                                    <td colspan="{{ count($scores) + 3 }}" class="text-center py-8 text-gray-400">
                                        لا يوجد معلمون لعرضهم
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <p class="text-xs text-gray-400 mt-3">
                * يتم حفظ كل صف تلقائياً بعد تعديل أي درجة فيها (عند الخروج من الحقل).
            </p>
        </div>
    </div>

    <style>
        .sheet-scroll {
            overflow-x: auto;
            overflow-y: auto;
            max-height: 80vh;
            position: relative;
        }

        .grades-sheet {
            border-collapse: separate;
            border-spacing: 0;
            table-layout: fixed;
            width: max-content;
            min-width: 100%;
            font-size: 13px;
        }
        .grades-sheet th,
        .grades-sheet td {
            border: 1px solid #e5e7eb;
            box-sizing: border-box;
            overflow: hidden;
        }

        .col-w-name   { width: 170px; }
        .col-w-school { width: 150px; }
        .col-w-score  { width: 84px; }
        .col-w-total  { width: 84px; }

        .grades-sheet thead th {
            position: sticky;
            top: 0;
            background: #eef2ff;
            color: #374151;
            z-index: 3;
            padding: 6px 4px;
            font-weight: 600;
        }

        .sticky-col {
            position: sticky;
            background: #ffffff;
            padding: 8px 10px;
            text-align: right;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .col-name {
            right: 0;
            z-index: 2;
            font-weight: 700;
            color: #1f2937;
        }
        .col-school {
            right: 170px;
            z-index: 1;
            color: #4b5563;
        }
        thead .col-name,
        thead .col-school {
            z-index: 4;
            background: #eef2ff;
        }

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

        tbody tr:hover td:not(.sticky-col) { background: #f9fafb; }
        tbody tr:hover .total-cell { background: #e0e7ff; }
        tbody tr:hover .sticky-col { background: #f3f4f6; }
    </style>

    <script>
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

        document.querySelectorAll('tbody tr[data-teacher-id]').forEach(row => {
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