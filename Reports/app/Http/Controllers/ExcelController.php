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

        foreach (range('A', $sheet->getHighestColumn()) as $col) {
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
        set_time_limit(120);
        
        $rows = $this->readSpreadsheet($request);

        if (empty($rows)) {
            return back()->with('error', 'الملف فارغ أو لا يحتوي على بيانات صالحة.');
        }

        // Collect all non-empty IDs from the file in one pass
    $idsInFile = array_filter(array_map(fn ($r) => $r['School_ID'] ?? null, $rows));

    // ONE query to find which of those IDs already exist, instead of one query per row
    $existingIds = School::whereIn('School_ID', $idsInFile)
        ->pluck('School_ID')
        ->flip(); // flip for fast isset() lookups




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

            if ($id && isset($existingIds[$id])) {
            $errors[] = "السطر {$excelRow}: المدرسة برقم {$id} موجودة بالفعل.";
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

        $headers = ['SuperVisor_id', 'SuperVisor_Name', 'SuperVisor_Major', 'role'];

        $rows = $supervisors->map(function ($s) {
            return [
                $s->SuperVisor_id,
                $s->SuperVisor_Name,
                $s->SuperVisor_Major,
                $s->role,
            ];
        })->toArray();

        return $this->downloadSpreadsheet($headers, $rows, 'supervisors_export_' . now()->format('Y_m_d_His') . '.xlsx');
    }



    public function importSupervisors(Request $request)
{

set_time_limit(120);
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

    foreach ($rows as $row) {
        $excelRow = $row['_excel_row'];
        $id = $row['SuperVisor_id'] ?? null;
        $name = $row['SuperVisor_Name'] ?? null;
        $major = $row['SuperVisor_Major'] ?? null;
        $role = $row['role'] ?? null;

        if (!$name || !$role) {
            $errors[] = "السطر {$excelRow}: يجب توفر SuperVisor_Name و role.";
            continue;
        }

        if (!in_array($role, ['admin', 'user'])) {
            $errors[] = "السطر {$excelRow}: قيمة role غير صحيحة (admin أو user فقط).";
            continue;
        }

        if ($id && isset($existingIds[$id])) {
            $errors[] = "السطر {$excelRow}: المشرف برقم {$id} موجود بالفعل.";
            continue;
        }

        $data = [
            'SuperVisor_Name' => $name,
            'SuperVisor_Major' => $major,
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
        $teachers = TeacherInfo::with(['school', 'supervisor'])->orderBy('Teacher_id')->get();

        $headers = [
            'Teacher_id', 'Teacher_Name', 'school_id', 'SchoolName',
            'supervisor_id', 'SuperVisor_Name', 'date', 'teacher_qualify', 'teacher_major',
        ];

        $rows = $teachers->map(function ($t) {
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
            ];
        })->toArray();

        return $this->downloadSpreadsheet($headers, $rows, 'teachers_export_' . now()->format('Y_m_d_His') . '.xlsx');
    }

    public function importTeachers(Request $request)
    {
        set_time_limit(120);
        $rows = $this->readSpreadsheet($request);

        if (empty($rows)) {
            return back()->with('error', 'الملف فارغ أو لا يحتوي على بيانات صالحة.');
        }

        // Collect all non-empty IDs from the file in one pass
    $idsInFile = array_filter(array_map(fn ($r) => $r['Teacher_id'] ?? null, $rows));

    // ONE query to find which of those IDs already exist, instead of one query per row
    $existingIds = TeacherInfo::whereIn('Teacher_id', $idsInFile)
        ->pluck('Teacher_id')
        ->flip(); // flip for fast isset() lookups

        



        $errors = [];
        $toInsert = [];

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

            if ($id && isset($existingIds[$id])) {
            $errors[] = "السطر {$excelRow}: المعلم برقم {$id} موجود بالفعل.";
            continue;
        }

            if (!School::where('School_ID', $schoolId)->exists()) {
                $errors[] = "السطر {$excelRow}: المدرسة برقم {$schoolId} غير موجودة.";
                continue;
            }

            if (!SuperVisor::where('SuperVisor_id', $supervisorId)->exists()) {
                $errors[] = "السطر {$excelRow}: المشرف برقم {$supervisorId} غير موجود.";
                continue;
            }

            if (Auth::user()->role === 'user') {
                $supervisorId = Auth::user()->SuperVisor_id;
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