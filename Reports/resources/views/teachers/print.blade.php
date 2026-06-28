<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>تقرير الاداء السنوي - {{ $teacher->Teacher_Name }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Tahoma','Arial',sans-serif; color:#111; background:#f3f4f6; margin:0; padding:10px; }
        .toolbar { max-width:800px; margin:0 auto 14px; display:flex; justify-content:flex-end; gap:8px; }
        .toolbar button {
            background:#2563eb; color:#fff; border:none; padding:8px 18px;
            border-radius:8px; font-weight:bold; cursor:pointer; font-size:14px;
        }
        .toolbar button:hover { background:#1d4ed8; }
        .report-page { max-width: 800px; margin: 0 auto; padding: 20px; background:#fff; }
        .header-row { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px; margin-top:10px; }
        .header-block { font-size: 13px; line-height: 1.6; }
        .header-block.right { text-align: right; }
        .header-block.center { text-align: center; }
        .header-logo { text-align: center; }
        .header-logo img { width: 60px; }
        h1.report-title { text-align: center; font-size: 18px; margin: 10px 0 14px; border-bottom: 2px solid #333; padding-bottom: 8px; }
        table.info-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; font-size: 13px; }
        table.info-table td { border: 1px solid #333; padding: 4px 10px; }
        table.info-table td.label { font-weight: bold; background: #f3f4f6; width: 16%; }
        table.info-table td.value { width: 34%; }
        table.scores-table { width: 100%; border-collapse: collapse; font-size: 12.5px; }
        table.scores-table th, table.scores-table td { border: 1px solid #333; padding: 3px 8px; text-align: center; font-size: 11.5px; }
        table.scores-table thead th { background: #e5e7eb; font-weight: bold; }
        table.scores-table td.indicator-cell { text-align: right; }
        table.scores-table td.group-cell { font-weight: bold;background: #f9fafb;vertical-align: middle; }
        table.scores-table tfoot td { font-weight: bold; background: #f3f4f6; }
        .footer-row { display: flex; justify-content: space-between; margin-top: 14px; font-size: 13px; }
        .notes-line { margin-top: 14px; font-size: 13px; }
        .signature-line { margin-top: 10px; font-size: 13px; text-align: left; }

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

    <div class="report-page">
        <div class="header-row">
            <div class="header-block center">
                دولة فلسطين<br>
                وزارة التربية والتعليم العالي<br>
                الادارة العامة للإشراف التربوي
            </div>
            <div class="header-logo">
                <img src="{{ asset('images/logo.png') }}" alt="شعار دولة فلسطين">
            </div>
            <div class="header-block right">
                مديرية التربية والتعليم : {{ $teacher->school->directorate->Directorate_Name ?? '-' }}<br>
                المدرسة : {{ $teacher->school->SchoolName ?? '-' }}<br>
                الرقم الوطني : {{ $teacher->school->School_ID ?? '-' }}
            </div>
        </div>

        <h1 class="report-title">تقرير الاداء السنوي للمعلم / خاص بالمشرف التربوي</h1>

        <table class="info-table">
            <tr>
                <td class="label">اسم المعلم /ة</td>
                <td class="value">{{ $teacher->Teacher_Name }}</td>
                <td class="label">رقم الهوية</td>
                <td class="value">{{ $teacher->Teacher_id }}</td>
            </tr>
            <tr>
                <td class="label">المؤهل</td>
                <td class="value">{{ $teacher->teacher_qualify }}</td>
                <td class="label">التخصص</td>
                <td class="value">{{ $teacher->teacher_major }}</td>
            </tr>
            <tr>
                <td class="label">تاريخ التعيين</td>
                <td class="value">{{ $teacher->date }}</td>
                <td class="label">العام الدراسي</td>
                <td class="value">{{ $teacher->academic_year ?? '—' }}</td>
            </tr>
        </table>

        <table class="scores-table">
            <thead>
                <tr>
                    <th style="width:10%">المجال</th>
                    <th>مؤشرات الاداء</th>
                    <th style="width:7%">العلامة القصوى</th>
                    <th style="width:7%">المعدل</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $fields = array_keys($criteria);
                    $cursor = 0;
                @endphp
                @foreach($groups as $groupLabel => $count)
                    @php
                        $groupFields = array_slice($fields, $cursor, $count);
                        $cursor += $count;
                    @endphp
                    @foreach($groupFields as $i => $field)
                        @php [$label, $max] = $criteria[$field]; @endphp
                        <tr>
                            @if($i === 0)
                                <td class="group-cell" rowspan="{{ $count }}">{{ $groupLabel }}</td>
                            @endif
                            <td class="indicator-cell">{{ $label }}</td>
                            <td>{{ $max }}</td>
                            <td>{{ $teacher->grades->$field ?? 0 }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">المجموع بالارقام</td>
                    <td>100</td>
                    <td>{{ $teacher->grades->total ?? 0 }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="footer-row">
            <div>التاريخ : {{ now()->format('Y-m-d') }}</div>
            <div>المجموع بالحروف : {{ \App\Http\Controllers\TeacherGradeController::numberToArabicWords($teacher->grades->total ?? 0) }}</div>
        </div>

        <div class="notes-line">
            ملحوظات المشرف وتوصياته : {{ $teacher->supervisor_note ?? '-' }}
        </div>

        <div class="signature-line">
            اسم المشرف وتوقيعه : {{ $teacher->supervisor->SuperVisor_Name ?? '' }}
        </div>
    </div>

</body>
</html>