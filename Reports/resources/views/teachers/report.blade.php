<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تقرير الاداء السنوي - {{ $teacher->Teacher_Name }}</title>
    <style>
        @page {
            size: A4;
            margin: 15mm 12mm;
        }
        @font-face {
            font-family: 'Amiri';
            src: url('{{ public_path('fonts/Amiri-Regular.ttf') }}') format('truetype');
            font-weight: normal;
        }
        @font-face {
            font-family: 'Amiri';
            src: url('{{ public_path('fonts/Amiri-Bold.ttf') }}') format('truetype');
            font-weight: bold;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Amiri', sans-serif;
            direction: rtl;
            color: #111;
            margin: 0;
            padding: 0;
        }
        .report-page {
            max-width: 800px;
            margin: 0 auto;
            padding: 10px;
        }

        /* Header */
        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
        }
        .header-block { font-size: 13px; line-height: 1.6; }
        .header-block.left  { text-align: center; }
        .header-block.right { text-align: center; }
        .header-logo { text-align: center; }
        .header-logo img { width: 60px; height: 60px; }

        h1.report-title {
            text-align: center;
            font-size: 18px;
            margin: 10px 0 14px;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
        }

        /* Teacher info table */
        table.info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 13px;
        }
        table.info-table td {
            border: 1px solid #333;
            padding: 6px 10px;
        }
        table.info-table td.label {
            font-weight: bold;
            background: #f3f4f6;
            width: 16%;
        }
        table.info-table td.value {
            width: 34%;
        }

        /* Scores table */
        table.scores-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12.5px;
        }
        table.scores-table th,
        table.scores-table td {
            border: 1px solid #333;
            padding: 5px 8px;
            text-align: center;
        }
        table.scores-table thead th {
            background: #e5e7eb;
            font-weight: bold;
        }
        table.scores-table td.indicator-cell {
            text-align: right;
        }
        table.scores-table td.group-cell {
            font-weight: bold;
            background: #f9fafb;
            writing-mode: horizontal-tb;
        }
        table.scores-table tfoot td {
            font-weight: bold;
            background: #f3f4f6;
        }

        .footer-row {
            display: flex;
            justify-content: space-between;
            margin-top: 14px;
            font-size: 13px;
        }
        .notes-line {
            margin-top: 14px;
            font-size: 13px;
        }
        .signature-line {
            margin-top: 30px;
            font-size: 13px;
            text-align: left;
        }

        /* Print controls (hidden when actually printing) */
        .print-controls {
            text-align: center;
            margin: 16px 0;
        }
        .print-controls button {
            background: #2563eb;
            color: white;
            border: none;
            padding: 8px 24px;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
        }

        @media print {
            .print-controls { display: none; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>

    

    <div class="report-page">

        <div class="header-row">
            

            <div class="header-block " right>
                دولة فلسطين<br>
                وزارة التربية والتعليم العالي<br>
                الادارة العامة للإشراف التربوي
            </div>
            <div class="header-logo">
                <img src="{{ public_path('images/logo.png') }}" alt="شعار دولة فلسطين">
            </div>

            <div class="header-block " left>
               مديرية التربية والتعليم : {{ $teacher->school->directorate->Directorate_Name ?? '' }}<br>
                المدرسة : {{ $teacher->school->SchoolName ?? '' }}<br>
                الرقم الوطني : {{ $teacher->school->School_ID ?? '' }}
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
                <td class="value">{{ \Carbon\Carbon::now()->format('Y-m-d') }}</td>
                <td class="label">العام الدراسي</td>
                <td class="value">{{ $teacher->academic_year ?? '—' }}</td>
            </tr>
        </table>

        <table class="scores-table">
            <thead>
                <tr>
                    <th style="width:14%">المجال</th>
                    <th>مؤشرات الاداء</th>
                    <th style="width:7%">العلامة القصوى</th>
                    <th style="width:7%">المعدل</th>
                    
                    
                    
                </tr>
            </thead>
            <tbody>
                @foreach ($groupedRows as $groupLabel => $rows)
                    @foreach ($rows as $i => $row)
                        <tr>
                            @if ($i === 0)
                                <td class="group-cell" rowspan="{{ count($rows) }}">{{ $groupLabel }}</td>
                            @endif
                            <td class="indicator-cell">{{ $row['label'] }}</td>
                            <td>{{ $row['max'] }}</td>
                            <td>{{ $row['score'] }}</td>
                            
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">المجموع بالارقام</td>
                    <td>100</td>
                    <td>{{ $total }}</td>
                    
                    
                </tr>
            </tfoot>
        </table>

        <div class="footer-row">
            <div>التاريخ : {{ \Carbon\Carbon::now()->format('Y-m-d') }}</div>
            <div>المجموع بالحروف : {{ $totalWords }}</div>
            
        </div>

        <div class="notes-line">
            ملحوظات المشرف وتوصياته : {{ $teacher->supervisor_note ?? '-' }}
        </div>

        <div class="signature-line" >
            اسم المشرف وتوقيعه : {{ $teacher->supervisor->SuperVisor_Name ?? '' }}
        </div>

    </div>

</body>
</html>