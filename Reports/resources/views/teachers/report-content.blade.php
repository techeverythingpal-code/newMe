<div class="report-page" dir="rtl">

        <div class="header-row">
            
<div class="header-block " right>
               مديرية التربية والتعليم : {{ $teacher->school->directorate->Directorate_Name ?? '' }}<br>
                المدرسة : {{ $teacher->school->SchoolName ?? '' }}<br>
                الرقم الوطني : {{ $teacher->school->School_ID ?? '' }}
            </div>
            
            <div class="header-logo">
                <img src="{{ asset('images/logo.png') }}" alt="شعار دولة فلسطين">
            </div>
            <div class="header-block " center>
                دولة فلسطين<br>
                وزارة التربية والتعليم العالي<br>
                الادارة العامة للإشراف التربوي
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
                    <th style="width:7%">المعدل</th>
                    <th style="width:7%">العلامة القصوى</th>
                    <th>مؤشرات الاداء</th>
                    <th style="width:14%">المجال</th>
                    
                </tr>
            </thead>
            <tbody>
                @foreach ($groupedRows as $groupLabel => $rows)

                    @foreach ($rows as $i => $row)
                        <tr>
                            <td>{{ $row['score'] }}</td>
                            <td>{{ $row['max'] }}</td>
                            <td class="indicator-cell">{{ $row['label'] }}</td>
                            @if ($i === 0)
                                <td class="group-cell" rowspan="{{ count($rows) }}">{{ $groupLabel }}</td>
                            @endif
                            
                            
                            
                            
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    
                    
                    <td>{{ $total }}</td>
                    <td>100</td>
                    <td colspan="2">المجموع بالارقام</td>
                    
                    
                </tr>
            </tfoot>
        </table>

        <div class="footer-row">
            
            <div>المجموع بالحروف : {{ $totalWords }}</div>
            <div>التاريخ : {{ \Carbon\Carbon::now()->format('Y-m-d') }}</div>
            
        </div>

        <div class="notes-line">
            ملحوظات المشرف وتوصياته : {{ $teacher->supervisor_note ?? '-' }}
        </div>

        <div class="signature-line" >
            اسم المشرف وتوقيعه : {{ $teacher->supervisor->SuperVisor_Name ?? '' }}
        </div>

    </div>