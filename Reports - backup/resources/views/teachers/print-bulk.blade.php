<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>تقارير الاداء السنوي - طباعة جماعية</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Tahoma','Arial',sans-serif; color:#111; background:#f3f4f6; margin:0; padding:20px; }
        .toolbar { max-width:800px; margin:0 auto 14px; display:flex; justify-content:space-between; align-items:center; gap:8px; }
        .toolbar button {
            background:#2563eb; color:#fff; border:none; padding:8px 18px;
            border-radius:8px; font-weight:bold; cursor:pointer; font-size:14px;
        }
        .toolbar button:hover { background:#1d4ed8; }
        .toolbar span { font-size:13px; color:#555; }
        .report-page { max-width: 800px; margin: 0 auto 20px; padding: 20px; background:#fff; }
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
        
        table.scores-table { width: 100%; border-collapse: collapse; font-size: 12.5px; table-layout: fixed; }
        table.scores-table th, table.scores-table td {
            border: 1px solid #333;
            padding: 6px 8px;
            text-align: center;
            vertical-align: middle;
            font-size: 11.5px;
            line-height: 1.3;
        }
        table.scores-table thead th { background: #e5e7eb; font-weight: bold; }
        table.scores-table td.indicator-cell { text-align: right; vertical-align: middle; }
        table.scores-table td.group-cell {
            font-weight: bold;
            background: #f9fafb;
            vertical-align: middle;
            width: 9%;
            white-space: normal;
            word-wrap: break-word;
        }
        table.scores-table tfoot td { font-weight: bold; background: #f3f4f6; }
        .footer-row { display: flex; justify-content: space-between; margin-top: 14px; font-size: 13px; }
        .notes-line { margin-top: 14px; font-size: 13px; }
        .signature-line { margin-top: 10px; font-size: 13px; text-align: left; }

        .notes-line {
            margin-top: 14px;
            font-size: 13px;
            max-height: 40px;
            overflow: hidden;
            line-height: 1.4;
            }

        @media print {
            @page { size: A4 portrait; margin: 0.5cm; }
            body { padding: 0; background: #fff; }
            .toolbar { display: none; }
            .academic-year-input { border: none !important; background: transparent !important; }
            .report-page { page-break-after: always; }
            .report-page:last-of-type { page-break-after: auto; }
        }
    </style>
</head>
<body>

    <div class="toolbar">
        <span>عدد التقارير: {{ $teachers->count() }}</span>
        <button onclick="window.print()">🖨️ طباعة التقارير المختارة</button>
    </div>

    @foreach($teachers as $teacher)
        @include('teachers.partials.report-body', ['teacher' => $teacher, 'criteria' => $criteria, 'groups' => $groups, 'academicYear' => $academicYear])
    @endforeach

</body>
</html>