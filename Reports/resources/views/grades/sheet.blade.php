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
        .col-school