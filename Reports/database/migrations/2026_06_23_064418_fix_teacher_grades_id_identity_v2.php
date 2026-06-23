<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // A sequence already exists (owned by id) — just find it and wire it
        // up as the column's default again.
        $seq = DB::selectOne("SELECT pg_get_serial_sequence('teacher_grades', 'id') AS seq")->seq;

        if ($seq) {
            DB::statement("SELECT setval('{$seq}', COALESCE((SELECT MAX(id) FROM teacher_grades), 1))");
            DB::statement("ALTER TABLE teacher_grades ALTER COLUMN id SET DEFAULT nextval('{$seq}')");
        }
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE teacher_grades ALTER COLUMN id DROP DEFAULT');
    }
};