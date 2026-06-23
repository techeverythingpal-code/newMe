<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('DROP SEQUENCE IF EXISTS teacher_grades_id_seq');
        DB::statement('CREATE SEQUENCE teacher_grades_id_seq');
        DB::statement('SELECT setval(\'teacher_grades_id_seq\', COALESCE((SELECT MAX(id) FROM teacher_grades), 1))');
        DB::statement('ALTER TABLE teacher_grades ALTER COLUMN id SET DEFAULT nextval(\'teacher_grades_id_seq\')');
        DB::statement('ALTER SEQUENCE teacher_grades_id_seq OWNED BY teacher_grades.id');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE teacher_grades ALTER COLUMN id DROP DEFAULT');
        DB::statement('DROP SEQUENCE IF EXISTS teacher_grades_id_seq');
    }
};