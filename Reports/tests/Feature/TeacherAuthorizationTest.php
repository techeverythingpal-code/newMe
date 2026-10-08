<?php

namespace Tests\Feature;

use App\Models\Directorate;
use App\Models\School;
use App\Models\SuperVisor;
use App\Models\TeacherGrade;
use App\Models\TeacherInfo;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class TeacherAuthorizationTest extends TestCase
{
    private const SUP_A = 1;
    private const SUP_B = 2;
    private const TEACHER_A = 101; // belongs to supervisor A
    private const TEACHER_B = 201; // belongs to supervisor B

    private User $admin;
    private SuperVisor $supA;
    private SuperVisor $supB;

    protected function setUp(): void
    {
        parent::setUp();

        // Safety net: never run against a real (MySQL) database.
        if (config('database.default') !== 'sqlite'
            || config('database.connections.sqlite.database') !== ':memory:') {
            $this->fail('Refusing to run: tests must use in-memory SQLite (see phpunit.xml).');
        }

        $this->withoutVite();
        $this->createTestSchema();
        $this->seedData();
    }

    // ------------------------------------------------------------------
    // Request lists (shared by the data providers below)
    // ------------------------------------------------------------------

    private static function requestsFor(int $teacherId): array
    {
        return [
            'show'                => ['GET',    "/teachers/$teacherId", []],
            'edit'                => ['GET',    "/teachers/$teacherId/edit", []],
            'update'              => ['PUT',    "/teachers/$teacherId", self::teacherPayload()],
            'destroy'             => ['DELETE', "/teachers/$teacherId", []],
            'grades edit'         => ['GET',    "/teachers/$teacherId/grades/edit", []],
            'grades update'       => ['PATCH',  "/teachers/$teacherId/grades", self::gradesPayload()],
            'grades quick update' => ['PATCH',  "/teachers/$teacherId/grades/quick", self::gradesPayload()],
            'grades reset'        => ['DELETE', "/teachers/$teacherId/grades/reset", []],
            'print report'        => ['GET',    "/teachers/$teacherId/report/print", []],
            'justification show'  => ['GET',    "/teachers/$teacherId/justification", []],
            'justification store' => ['POST',   "/teachers/$teacherId/justification", ['strengths' => 'x']],
            'supervisor note'     => ['PATCH',  "/teachers/$teacherId/supervisor-note", ['supervisor_note' => 'x']],
        ];
    }

    public static function otherSupervisorsTeacherRequests(): array
    {
        return self::requestsFor(self::TEACHER_B);
    }

    public static function ownTeacherRequests(): array
    {
        return self::requestsFor(self::TEACHER_A);
    }

    private static function teacherPayload(int $supervisorId = self::SUP_A): array
    {
        return [
            'Teacher_Name'    => 'Changed Name',
            'supervisor_id'   => $supervisorId,
            'school_id'       => 1,
            'date'            => '2020-01-01',
            'teacher_qualify' => 'BA',
            'teacher_major'   => 'Math',
        ];
    }

    private static function gradesPayload(): array
    {
        $data = [];
        for ($i = 1; $i <= 22; $i++) {
            $data["score{$i}"] = 1;
        }
        return $data;
    }

    // ------------------------------------------------------------------
    // Supervisor A vs supervisor B's teacher
    // ------------------------------------------------------------------

    #[DataProvider('otherSupervisorsTeacherRequests')]
    public function test_supervisor_cannot_touch_another_supervisors_teacher(
        string $method, string $uri, array $payload
    ): void {
        $this->actingAs($this->supA, 'web')
            ->call($method, $uri, $payload)
            ->assertForbidden();
    }

    #[DataProvider('ownTeacherRequests')]
    public function test_supervisor_can_still_use_their_own_teacher(
        string $method, string $uri, array $payload
    ): void {
        $response = $this->actingAs($this->supA, 'web')->call($method, $uri, $payload);

        // Page rendering may depend on tables this test does not build,
        // so the point here is only: access is NOT denied.
        $this->assertNotSame(403, $response->getStatusCode());
    }

    public function test_failed_attempts_leave_the_other_supervisors_data_unchanged(): void
    {
        $this->actingAs($this->supA, 'web');

        $this->call('PUT', '/teachers/' . self::TEACHER_B, self::teacherPayload());
        $this->call('PATCH', '/teachers/' . self::TEACHER_B . '/grades', self::gradesPayload());
        $this->call('PATCH', '/teachers/' . self::TEACHER_B . '/grades/quick', self::gradesPayload());
        $this->call('DELETE', '/teachers/' . self::TEACHER_B . '/grades/reset');
        $this->call('DELETE', '/teachers/' . self::TEACHER_B);

        $this->assertDatabaseHas('teacher_infos', [
            'Teacher_id'    => self::TEACHER_B,
            'Teacher_Name'  => 'Teacher of B',
            'supervisor_id' => self::SUP_B,
        ]);
        $this->assertDatabaseHas('teacher_grades', [
            'teacher_id' => self::TEACHER_B,
            'total'      => 5,
        ]);
    }

    public function test_supervisor_cannot_reassign_their_teacher_to_someone_else(): void
    {
        $this->actingAs($this->supA, 'web')
            ->call('PUT', '/teachers/' . self::TEACHER_A, self::teacherPayload(self::SUP_B));

        $teacher = TeacherInfo::find(self::TEACHER_A);

        $this->assertSame('Changed Name', $teacher->Teacher_Name); // edit worked
        $this->assertSame(self::SUP_A, (int) $teacher->supervisor_id); // owner unchanged
    }

    public function test_supervisor_can_delete_their_own_teacher(): void
    {
        $this->actingAs($this->supA, 'web')
            ->call('DELETE', '/teachers/' . self::TEACHER_A);

        $this->assertDatabaseMissing('teacher_infos', ['Teacher_id' => self::TEACHER_A]);
    }

    public function test_supervisor_can_save_their_own_grades(): void
    {
        $this->actingAs($this->supA, 'web')
            ->patchJson('/teachers/' . self::TEACHER_A . '/grades/quick', self::gradesPayload())
            ->assertOk()
            ->assertJson(['success' => true, 'total' => 22]);
    }

    // ------------------------------------------------------------------
    // Admin
    // ------------------------------------------------------------------

    #[DataProvider('otherSupervisorsTeacherRequests')]
    public function test_admin_is_not_blocked_on_any_teacher(
        string $method, string $uri, array $payload
    ): void {
        $response = $this->actingAs($this->admin, 'admin')->call($method, $uri, $payload);

        $this->assertNotSame(403, $response->getStatusCode());
    }

    public function test_admin_can_reassign_a_teacher(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->call('PUT', '/teachers/' . self::TEACHER_B, self::teacherPayload(self::SUP_A))
            ->assertRedirect(route('teachers.index'));

        $this->assertSame(
            self::SUP_A,
            (int) TeacherInfo::find(self::TEACHER_B)->supervisor_id
        );
    }

    public function test_admin_gets_403_instead_of_a_crash_on_reset_all(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->call('DELETE', '/teachers-grades/reset-all')
            ->assertForbidden();
    }

    // ------------------------------------------------------------------
    // Guests and admin-only pages
    // ------------------------------------------------------------------

    #[DataProvider('otherSupervisorsTeacherRequests')]
    public function test_guest_is_sent_to_login(string $method, string $uri, array $payload): void
    {
        $this->call($method, $uri, $payload)->assertRedirect(route('login'));
    }

    public function test_supervisor_cannot_open_admin_only_pages(): void
    {
        foreach (['/supervisors', '/schools', '/directorates'] as $uri) {
            $this->actingAs($this->supA, 'web')
                ->get($uri)
                ->assertRedirect(route('dashboard'));
        }
    }

    public function test_guest_cannot_open_admin_only_pages(): void
    {
        foreach (['/supervisors', '/schools', '/directorates'] as $uri) {
            $this->get($uri)->assertRedirect(route('login'));
        }
    }

    // ------------------------------------------------------------------
    // Bulk print / reset-all scoping
    // ------------------------------------------------------------------

    public function test_bulk_print_does_not_include_other_supervisors_teachers(): void
    {
        // A single value (not an array) must not crash, and must not leak.
        $this->actingAs($this->supA, 'web')
            ->get('/teachers/reports/print?ids=' . self::TEACHER_B)
            ->assertNotFound();

        $this->actingAs($this->supA, 'web')
            ->get('/teachers/reports/print?ids[]=' . self::TEACHER_B)
            ->assertNotFound();
    }

    public function test_reset_all_only_touches_the_current_supervisors_teachers(): void
    {
        $this->actingAs($this->supA, 'web')
            ->call('DELETE', '/teachers-grades/reset-all');

        $this->assertDatabaseHas('teacher_grades', ['teacher_id' => self::TEACHER_A, 'total' => 0]);
        $this->assertDatabaseHas('teacher_grades', ['teacher_id' => self::TEACHER_B, 'total' => 5]);
    }

    // ------------------------------------------------------------------
    // Test database (built here so MySQL-only migrations are not needed)
    // ------------------------------------------------------------------

    private function createTestSchema(): void
    {
        Schema::create('users', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('email')->unique();
            $t->timestamp('email_verified_at')->nullable();
            $t->string('password');
            $t->rememberToken();
            $t->timestamps();
        });

        Schema::create('directorates', function (Blueprint $t) {
            $t->integer('Directorate_id')->primary();
            $t->string('Directorate_Name')->nullable();
            $t->timestamps();
        });

        Schema::create('super_visors', function (Blueprint $t) {
            $t->unsignedBigInteger('SuperVisor_id')->primary();
            $t->string('SuperVisor_Name')->nullable();
            $t->string('SuperVisor_Major')->nullable();
            $t->string('role')->default('user');
            $t->integer('directorate_id')->nullable();
            $t->string('password');
            $t->rememberToken();
            $t->timestamps();
        });

        Schema::create('schools', function (Blueprint $t) {
            $t->integer('School_ID')->primary();
            $t->string('SchoolName')->nullable();
            $t->integer('directorate_id');
            $t->timestamps();
        });

        Schema::create('teacher_infos', function (Blueprint $t) {
            $t->increments('id');
            $t->unsignedBigInteger('supervisor_id');
            $t->string('Teacher_Name');
            $t->integer('Teacher_id')->unique();
            $t->integer('school_id');
            $t->date('date');
            $t->string('teacher_qualify');
            $t->string('teacher_major');
            $t->string('supervisor_note', 255)->nullable();
            $t->string('academic_year')->nullable();
            $t->timestamps();
        });

        Schema::create('teacher_grades', function (Blueprint $t) {
            $t->increments('id');
            $t->integer('teacher_id')->unique();
            for ($i = 1; $i <= 22; $i++) {
                $t->integer("score{$i}")->default(0);
            }
            $t->integer('total')->default(0);
            $t->timestamps();
        });

        Schema::create('teacher_justifications', function (Blueprint $t) {
            $t->integer('teacher_id')->primary();
            $t->text('strengths')->nullable();
            $t->text('weaknesses')->nullable();
            $t->text('recommendations')->nullable();
            $t->text('preparer_opinion')->nullable();
            $t->text('approver_notes')->nullable();
            $t->timestamps();
        });
    }

    private function seedData(): void
    {
        $this->admin = User::factory()->create();

        Directorate::create(['Directorate_id' => 1, 'Directorate_Name' => 'Test directorate']);
        School::create(['School_ID' => 1, 'SchoolName' => 'Test school', 'directorate_id' => 1]);

        $this->supA = SuperVisor::create([
            'SuperVisor_id'   => self::SUP_A,
            'SuperVisor_Name' => 'Supervisor A',
            'directorate_id'  => 1,
            'password'        => Hash::make('secret-a'),
        ]);
        $this->supB = SuperVisor::create([
            'SuperVisor_id'   => self::SUP_B,
            'SuperVisor_Name' => 'Supervisor B',
            'directorate_id'  => 1,
            'password'        => Hash::make('secret-b'),
        ]);

        $this->makeTeacher(self::TEACHER_A, self::SUP_A, 'Teacher of A');
        $this->makeTeacher(self::TEACHER_B, self::SUP_B, 'Teacher of B');
    }

    private function makeTeacher(int $teacherId, int $supervisorId, string $name): void
    {
        TeacherInfo::create([
            'Teacher_id'      => $teacherId,
            'Teacher_Name'    => $name,
            'supervisor_id'   => $supervisorId,
            'school_id'       => 1,
            'date'            => '2020-01-01',
            'teacher_qualify' => 'BA',
            'teacher_major'   => 'Science',
        ]);

        // Non-zero starting grades, so "unchanged" and "reset" are detectable.
        $grades = ['teacher_id' => $teacherId, 'total' => 5];
        for ($i = 1; $i <= 22; $i++) {
            $grades["score{$i}"] = 0;
        }
        $grades['score1'] = 5;
        TeacherGrade::create($grades);
    }
}