@php
    $fields = array_keys($criteria);
    $cursor = 0;
@endphp
<div class="report-page" dir="rtl">
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
            <td class="value">{{ $academicYear }}</td>
        </tr>
    </table>

    <table class="scores-table">
        <thead>
            <tr>
                <th style="width:9%">المجال</th>
                <th>مؤشرات الاداء</th>
                <th style="width:7%">العلامة القصوى</th>
                <th style="width:7%">المعدل</th>
            </tr>
        </thead>
        <tbody>
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