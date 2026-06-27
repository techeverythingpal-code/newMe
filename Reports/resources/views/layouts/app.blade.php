<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            #print-outlet { display: none; }

            @media print {
                @page { size: A4 portrait; margin: 0.5cm; }
                body { padding: 0; margin: 0; background: #fff; }

                body.printing-record > *:not(#print-outlet) { display: none !important; }
                body.printing-record #print-outlet { display: block !important; }

                .report-page { max-width: 800px; margin: 0 auto; padding: 10px; font-family: 'Tahoma','Arial',sans-serif; color:#111; }
                .header-row { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px; }
                .header-block { font-size: 13px; line-height: 1.6; }
                .header-block.left { text-align: left; }
                .header-block.right { text-align: right; }
                .header-block.center { text-align: center; }
                .header-logo { text-align: center; }
                .header-logo img { width: 60px; height: 60px; }
                h1.report-title { text-align: center; font-size: 18px; margin: 10px 0 14px; border-bottom: 2px solid #333; padding-bottom: 8px; }
                table.info-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; font-size: 13px; }
                table.info-table td { border: 1px solid #333; padding: 4px 10px; }
                table.info-table td.label { font-weight: bold; background: #f3f4f6; width: 16%; }
                table.info-table td.value { width: 34%; }
                table.scores-table { width: 100%; border-collapse: collapse; font-size: 12.5px; }
                table.scores-table th, table.scores-table td { border: 1px solid #333; padding: 3px 8px; text-align: center; font-size: 11.5px; }
                table.scores-table thead th { background: #e5e7eb; font-weight: bold; }
                table.scores-table td.indicator-cell { text-align: right; }
                table.scores-table td.group-cell { font-weight: bold; background: #f9fafb; }
                table.scores-table tfoot td { font-weight: bold; background: #f3f4f6; }
                .footer-row { display: flex; justify-content: space-between; margin-top: 14px; font-size: 13px; }
                .notes-line { margin-top: 14px; font-size: 13px; }
                .signature-line { margin-top: 10px; font-size: 13px; text-align: left; }
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div id="print-outlet"></div>

        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
        {{ $header }}
    </div>
</header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <script>
            function waitForImages(container) {
                const imgs = container.querySelectorAll('img');
                return Promise.all(Array.from(imgs).map(img => {
                    if (img.complete) return Promise.resolve();
                    return new Promise(resolve => {
                        img.addEventListener('load', resolve);
                        img.addEventListener('error', resolve);
                    });
                }));
            }

            function escapeHtmlGlobal(str) {
                const div = document.createElement('div');
                div.textContent = str ?? '';
                return div.innerHTML;
            }

            function numberToArabicWordsJs(num) {
                const ones = ['', 'واحد', 'اثنان', 'ثلاثة', 'أربعة', 'خمسة', 'ستة', 'سبعة', 'ثمانية', 'تسعة'];
                const tens = ['', 'عشرة', 'عشرون', 'ثلاثون', 'أربعون', 'خمسون', 'ستون', 'سبعون', 'ثمانون', 'تسعون'];
                const teens = ['عشرة', 'أحد عشر', 'اثنا عشر', 'ثلاثة عشر', 'أربعة عشر', 'خمسة عشر', 'ستة عشر', 'سبعة عشر', 'ثمانية عشر', 'تسعة عشر'];

                if (num === 0) return 'صفر';
                if (num === 100) return 'مئة';
                if (num < 10) return ones[num];
                if (num < 20) return teens[num - 10];

                const t = Math.floor(num / 10);
                const o = num % 10;
                if (o === 0) return tens[t];
                return ones[o] + ' و' + tens[t];
            }

            function buildReportHtml(t, criteria, groups) {
                const today = new Date().toISOString().slice(0, 10);
                const scores = t.scores || {};

                let cursor = 0;
                let rowsHtml = '';
                for (const groupLabel in groups) {
                    const count = groups[groupLabel];
                    const groupFields = criteria.slice(cursor, cursor + count);
                    cursor += count;

                    groupFields.forEach((c, i) => {
                        const score = scores[c.field] ?? 0;
                        rowsHtml += `
                            <tr>
                                <td>${score}</td>
                                <td>${c.max}</td>
                                <td class="indicator-cell">${escapeHtmlGlobal(c.label)}</td>
                                ${i === 0 ? `<td class="group-cell" rowspan="${groupFields.length}">${escapeHtmlGlobal(groupLabel)}</td>` : ''}
                            </tr>`;
                    });
                }

                return `
                <div class="report-page" dir="rtl">
                    <div class="header-row">
                        <div class="header-block right">
                            مديرية التربية والتعليم : ${escapeHtmlGlobal(t.directorate)}<br>
                            المدرسة : ${escapeHtmlGlobal(t.school)}<br>
                            الرقم الوطني : ${escapeHtmlGlobal(t.school_id)}
                        </div>
                        <div class="header-logo">
                            <img src="{{ asset('images/logo.png') }}" alt="شعار دولة فلسطين">
                        </div>
                        <div class="header-block center">
                            دولة فلسطين<br>
                            وزارة التربية والتعليم العالي<br>
                            الادارة العامة للإشراف التربوي
                        </div>
                    </div>

                    <h1 class="report-title">تقرير الاداء السنوي للمعلم / خاص بالمشرف التربوي</h1>

                    <table class="info-table">
                        <tr>
                            <td class="label">اسم المعلم /ة</td>
                            <td class="value">${escapeHtmlGlobal(t.name)}</td>
                            <td class="label">رقم الهوية</td>
                            <td class="value">${t.id}</td>
                        </tr>
                        <tr>
                            <td class="label">المؤهل</td>
                            <td class="value">${escapeHtmlGlobal(t.qualify)}</td>
                            <td class="label">التخصص</td>
                            <td class="value">${escapeHtmlGlobal(t.major)}</td>
                        </tr>
                        <tr>
                            <td class="label">تاريخ التعيين</td>
                            <td class="value">${today}</td>
                            <td class="label">العام الدراسي</td>
                            <td class="value">${escapeHtmlGlobal(t.academic_year) || '—'}</td>
                        </tr>
                    </table>

                    <table class="scores-table">
                        <thead>
                            <tr>
                                <th style="width:7%">المعدل</th>
                                <th style="width:7%">العلامة القصوى</th>
                                <th>مؤشرات الاداء</th>
                                <th style="width:14%">المجال</th>
                            </tr>
                        </thead>
                        <tbody>${rowsHtml}</tbody>
                        <tfoot>
                            <tr>
                                <td>${t.total}</td>
                                <td>100</td>
                                <td colspan="2">المجموع بالارقام</td>
                            </tr>
                        </tfoot>
                    </table>

                    <div class="footer-row">
                        <div>المجموع بالحروف : ${numberToArabicWordsJs(t.total)}</div>
                        <div>التاريخ : ${today}</div>
                    </div>

                    <div class="notes-line">
                        ملحوظات المشرف وتوصياته : ${escapeHtmlGlobal(t.supervisor_note) || '-'}
                    </div>

                    <div class="signature-line">
                        اسم المشرف وتوقيعه : ${escapeHtmlGlobal(window.currentSupervisorName || '')}
                    </div>
                </div>`;
            }

            window.printTeacherReport = async function (teacherId) {
                const outlet = document.getElementById('print-outlet');
                const t = window.allTeachersData.find(x => x.id === teacherId);
                if (!t) { alert('لم يتم العثور على بيانات المعلم'); return; }

                outlet.innerHTML = buildReportHtml(t, window.scoreCriteriaData, window.scoreGroupsData);

                await waitForImages(outlet);

                document.body.classList.add('printing-record');
                window.print();
                document.body.classList.remove('printing-record');
                outlet.innerHTML = '';
            };
        </script>
    </body>
    </body>
</html>
