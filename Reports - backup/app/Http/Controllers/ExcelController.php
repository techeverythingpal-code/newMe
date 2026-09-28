<?php

namespace App\Http\Controllers;

use App\Models\Directorate;
use App\Models\School;
use App\Models\SuperVisor;
use App\Models\TeacherInfo;
use App\Models\TeacherGrade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ExcelController extends Controller
{
    /* =========================================================
     |  Shared helpers
     * =========================================================*/

    private function downloadSpreadsheet(array $headers, array $rows, string $filename)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray($headers, null, 'A1');
        $sheet->fromArray($rows, null, 'A2');

        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->getFont()->setBold(true);

        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($sheet->getHighestColumn());


        for ($i = 1; $i <= $highestColumnIndex; $i++) {
            $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function readSpreadsheet(Request $request, string $field = 'file'): array
    {
        $request->validate([
            $field => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $path = $request->file($field)->getRealPath();
        $spreadsheet = IOFactory::load($path);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray(null, true, true, false);

        if (empty($data)) {
            return [];
        }

        $headers = array_map(fn ($h) => trim((string) $h), $data[0]);
        $rows = [];

        for ($i = 1; $i < count($data); $i++) {
            $rowValues = $data[$i];

            if (count(array_filter($rowValues, fn ($v) => $v !== null && $v !== '')) === 0) {
                continue;
            }

            $row = [];
            foreach ($headers as $idx => $header) {
                $row[$header] = $rowValues[$idx] ?? null;
            }
            $row['_excel_row'] = $i + 1;
            $rows[] = $row;
        }

        return $rows;
    }

    private function directorateExists($id): bool
    {
        return Directorate::where('Directorate_id', $id)->exists();
    }

    /* =========================================================
     |  SCHOOLS
     * =========================================================*/

    public function exportSchools()
    {
        $schools = School::with('directorate')->orderBy('School_ID')->get();

        $headers = ['School_ID', 'SchoolName', 'directorate_id', 'Directorate_Name'];

        $rows = $schools->map(function ($school) {
            return [
                $school->School_ID,
                $school->SchoolName,
                $school->directorate_id,
                $school->directorate->Directorate_Name ?? '',
            ];
        })->toArray();

        return $this->downloadSpreadsheet($headers, $rows, 'schools_export_' . now()->format('Y_m_d_His') . '.xlsx');
    }

    public function importSchools(Request $request)
    {
         set_time_limit(240);

        $rows = $this->readSpreadsheet($request);

        if (empty($rows)) {
            return back()->with('error', 'الملف فارغ أو لا يحتوي على بيانات صالحة.');
        }

         $idsInFile = array_filter(array_map(fn ($r) => $r['School_ID'] ?? null, $rows));

    $existingIds = School::whereIn('School_ID', $idsInFile)
        ->pluck('School_ID')
        ->flip();

        $errors = [];
        $toInsert = [];

        foreach ($rows as $row) {
            $excelRow = $row['_excel_row'];
            $schoolId = $row['School_ID'] ?? null;
            $schoolName = $row['SchoolName'] ?? null;
            $directorateId = $row['directorate_id'] ?? null;

            if (!$schoolId || !$schoolName || !$directorateId) {
                $errors[] = "السطر {$excelRow}: يجب توفر School_ID و SchoolName و directorate_id.";
                continue;
            }

             if ($schoolId && isset($existingIds[$schoolId])) {
            $errors[] = "السطر {$excelRow}: المدرسة برقم {$schoolId} موجودة بالفعل.";
            continue;
        }

            if (!$this->directorateExists($directorateId)) {
                $errors[] = "السطر {$excelRow}: المديرية برقم {$directorateId} غير موجودة.";
                continue;
            }

            $toInsert[] = [
                'School_ID' => $schoolId,
                'SchoolName' => $schoolName,
                'directorate_id' => $directorateId,
            ];
        }

        if (!empty($errors)) {
            return back()->withErrors($errors)->with('error', 'تم إيقاف الاستيراد بالكامل بسبب وجود أخطاء.');
        }

        DB::transaction(function () use ($toInsert) {
            foreach ($toInsert as $data) {
                School::create($data);
            }
        });

        return redirect()->route('schools.index')->with('success', count($toInsert) . ' مدرسة تم استيرادها بنجاح.');
    }

    /* =========================================================
     |  SUPERVISORS
     * =========================================================*/

    public function exportSupervisors()
    {
        $supervisors = SuperVisor::orderBy('SuperVisor_id')->get();

        $headers = ['SuperVisor_id', 'SuperVisor_Name', 'SuperVisor_Major', 'directorate_id', 'role'];

        $rows = $supervisors->map(function ($s) {
            return [
                $s->SuperVisor_id,
                $s->SuperVisor_Name,
                $s->SuperVisor_Major,
                $s->directorate_id,
                $s->role,
            ];
        })->toArray();

        return $this->downloadSpreadsheet($headers, $rows, 'supervisors_export_' . now()->format('Y_m_d_His') . '.xlsx');
    }

    public function importSupervisors(Request $request)
    {
        set_time_limit(240);
        $rows = $this->readSpreadsheet($request);

        if (empty($rows)) {
            return back()->with('error', 'الملف فارغ أو لا يحتوي على بيانات صالحة.');
        }

        $idsInFile = array_filter(array_map(fn ($r) => $r['SuperVisor_id'] ?? null, $rows));

    $existingIds = SuperVisor::whereIn('SuperVisor_id', $idsInFile)
        ->pluck('SuperVisor_id')
        ->flip();

        $errors = [];
        $toInsert = [];
        $tempPassword = Hash::make('ChangeMe123');

        $directorateIds = \App\Models\Directorate::pluck('Directorate_id')->flip();

        foreach ($rows as $row) {
            $excelRow = $row['_excel_row'];
            $id = $row['SuperVisor_id'] ?? null;
            $name = $row['SuperVisor_Name'] ?? null;
            $major = $row['SuperVisor_Major'] ?? null;
            $role = $row['role'] ?? null;
            $directorateId = $row['directorate_id'] ?? null;

            if (!$name || !$role) {
                $errors[] = "السطر {$excelRow}: يجب توفر SuperVisor_Name و role.";
                continue;
            }

            if (!in_array($role, ['admin', 'user'])) {
                $errors[] = "السطر {$excelRow}: قيمة role غير صحيحة (admin أو user فقط).";
                continue;
            }

            if (!$directorateId) {
                $errors[] = "السطر {$excelRow}: يجب توفر directorate_id.";
                continue;
            }

            if (!isset($directorateIds[$directorateId])) {
                $errors[] = "السطر {$excelRow}: المديرية برقم {$directorateId} غير موجودة.";
                continue;
            }

            if ($id && isset($existingIds[$id])) {
            $errors[] = "السطر {$excelRow}: المشرف برقم {$id} موجود بالفعل.";
            continue;
        }

            $data = [
                'SuperVisor_Name' => $name,
                'SuperVisor_Major' => $major,
                'directorate_id' => $directorateId,
                'role' => $role,
                'password' => $tempPassword,
            ];

            if ($id) {
                $data['SuperVisor_id'] = $id;
            }

            $toInsert[] = $data;
        }

        if (!empty($errors)) {
            return back()->withErrors($errors)->with('error', 'تم إيقاف الاستيراد بالكامل بسبب وجود أخطاء.');
        }

        DB::transaction(function () use ($toInsert) {
            foreach ($toInsert as $data) {
                SuperVisor::create($data);
            }
        });

        return redirect()->route('supervisors.index')->with(
            'success',
            count($toInsert) . ' مشرف تم استيراده بنجاح. ⚠️ تم تعيين كلمة مرور مؤقتة، يرجى تحديثها لكل مشرف جديد.'
        );
    }

    /* =========================================================
     |  TEACHERS
     * =========================================================*/

    public function exportTeachers()
    {
        $query = TeacherInfo::with(['school', 'supervisor', 'grades'])->orderBy('Teacher_id');

        // Supervisor only exports their own teachers
        if (! Auth::guard('admin')->check()) {
            $query->where('supervisor_id', Auth::guard('web')->user()->SuperVisor_id);
        }

        $teachers = $query->get();

        $headers = [
            'Teacher_id', 'Teacher_Name', 'school_id', 'SchoolName',
            'supervisor_id', 'SuperVisor_Name', 'date', 'teacher_qualify', 'teacher_major',
            'score1', 'score2', 'score3', 'score4', 'score5',
            'score6', 'score7', 'score8', 'score9', 'score10',
            'score11', 'score12', 'score13', 'score14', 'score15',
            'score16', 'score17', 'score18', 'score19', 'score20',
            'score21', 'score22', 'total',
        ];

        $rows = $teachers->map(function ($t) {
            $g = $t->grades;

            return [
                $t->Teacher_id,
                $t->Teacher_Name,
                $t->school_id,
                $t->school->SchoolName ?? '',
                $t->supervisor_id,
                $t->supervisor->SuperVisor_Name ?? '',
                $t->date,
                $t->teacher_qualify,
                $t->teacher_major,
                $g->score1 ?? 0, $g->score2 ?? 0, $g->score3 ?? 0, $g->score4 ?? 0, $g->score5 ?? 0,
                $g->score6 ?? 0, $g->score7 ?? 0, $g->score8 ?? 0, $g->score9 ?? 0, $g->score10 ?? 0,
                $g->score11 ?? 0, $g->score12 ?? 0, $g->score13 ?? 0, $g->score14 ?? 0, $g->score15 ?? 0,
                $g->score16 ?? 0, $g->score17 ?? 0, $g->score18 ?? 0, $g->score19 ?? 0, $g->score20 ?? 0,
                $g->score21 ?? 0, $g->score22 ?? 0, $g->total ?? 0,
            ];
        })->toArray();

        return $this->downloadSpreadsheet($headers, $rows, 'teachers_export_' . now()->format('Y_m_d_His') . '.xlsx');
    }

    public function importTeachers(Request $request)
    {
        set_time_limit(240);
        $rows = $this->readSpreadsheet($request);

        if (empty($rows)) {
            return back()->with('error', 'الملف فارغ أو لا يحتوي على بيانات صالحة.');
        }

        $idsInFile = array_filter(array_map(fn ($r) => $r['Teacher_id'] ?? null, $rows));

    $existingIds = TeacherInfo::whereIn('Teacher_id', $idsInFile)
        ->pluck('Teacher_id')
        ->flip();

        $errors = [];
        $toInsert = [];

        $schoolIdsInFile = array_filter(array_map(fn ($r) => $r['school_id'] ?? null, $rows));
        $supervisorIdsInFile = array_filter(array_map(fn ($r) => $r['supervisor_id'] ?? null, $rows));

        $validSchoolIds = School::whereIn('School_ID', $schoolIdsInFile)
            ->pluck('School_ID')->flip();

        $validSupervisorIds = SuperVisor::whereIn('SuperVisor_id', $supervisorIdsInFile)
            ->pluck('SuperVisor_id')->flip();

        foreach ($rows as $row) {
            $excelRow = $row['_excel_row'];
            $teacherId = $row['Teacher_id'] ?? null;
            $name = $row['Teacher_Name'] ?? null;
            $schoolId = $row['school_id'] ?? null;
            $supervisorId = $row['supervisor_id'] ?? null;
            $date = $row['date'] ?? null;
            $qualify = $row['teacher_qualify'] ?? null;
            $major = $row['teacher_major'] ?? null;

            if (!$teacherId || !$name || !$schoolId || !$supervisorId) {
                $errors[] = "السطر {$excelRow}: يجب توفر Teacher_id و Teacher_Name و school_id و supervisor_id.";
                continue;
            }

            if ($date) {
                $parsedDate = null;

                foreach (['d/m/Y', 'Y-m-d', 'd-m-Y', 'm/d/Y'] as $format) {
                    try {
                        $parsedDate = \Carbon\Carbon::createFromFormat($format, trim($date));
                        break;
                    } catch (\Exception $e) {
                        continue;
                    }
                }

                if (!$parsedDate) {
                    $errors[] = "السطر {$excelRow}: تاريخ غير صالح ({$date}).";
                    continue;
                }

                $date = $parsedDate->format('Y-m-d');
            }

            if ($teacherId && isset($existingIds[$teacherId])) {
            $errors[] = "السطر {$excelRow}: المعلم برقم {$teacherId} موجود بالفعل.";
            continue;
        }

            if (!isset($validSchoolIds[$schoolId])) {
                $errors[] = "السطر {$excelRow}: المدرسة برقم {$schoolId} غير موجودة.";
                continue;
            }

            if (!isset($validSupervisorIds[$supervisorId])) {
                $errors[] = "السطر {$excelRow}: المشرف برقم {$supervisorId} غير موجود.";
                continue;
            }

            if (! Auth::guard('admin')->check()) {
                $supervisorId = Auth::guard('web')->user()->SuperVisor_id;
            }

            $toInsert[] = [
                'Teacher_id' => $teacherId,
                'Teacher_Name' => $name,
                'school_id' => $schoolId,
                'supervisor_id' => $supervisorId,
                'date' => $date,
                'teacher_qualify' => $qualify,
                'teacher_major' => $major,
            ];
        }

        if (!empty($errors)) {
            return back()->withErrors($errors)->with('error', 'تم إيقاف الاستيراد بالكامل بسبب وجود أخطاء.');
        }

        DB::transaction(function () use ($toInsert) {
            foreach ($toInsert as $data) {
                TeacherInfo::create($data);

                TeacherGrade::create([
                    'teacher_id' => $data['Teacher_id'],
                    'score1' => 0, 'score2' => 0, 'score3' => 0, 'score4' => 0,
                    'score5' => 0, 'score6' => 0, 'score7' => 0, 'score8' => 0,
                    'score9' => 0, 'score10' => 0, 'score11' => 0, 'score12' => 0,
                    'score13' => 0, 'score14' => 0, 'score15' => 0, 'score16' => 0,
                    'score17' => 0, 'score18' => 0, 'score19' => 0, 'score20' => 0,
                    'score21' => 0, 'score22' => 0, 'total' => 0,
                ]);
            }
        });

        return redirect()->route('teachers.index')->with('success', count($toInsert) . ' معلم تم استيراده بنجاح.');
    }
}