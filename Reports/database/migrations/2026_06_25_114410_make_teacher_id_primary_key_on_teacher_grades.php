<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Drop the foreign key that currently references teacher_infos
        //    (name may differ; this looks it up dynamically to avoid guessing wrong)
        $fk = DB::selectOne("
            SELECT tc.constraint_name
            FROM information_schema.table_constraints tc
            JOIN information_schema.key_column_usage kcu
              ON tc.constraint_name = kcu.constraint_name
            WHERE tc.table_name = 'teacher_grades'
              AND tc.constraint_type = 'FOREIGN KEY'
              AND kcu.column_name = 'teacher_id'
            LIMIT 1
        ");

        if ($fk) {
            DB::statement("ALTER TABLE teacher_grades DROP CONSTRAINT \"{$fk->constraint_name}\"");
        }

        // 2) Drop the old primary key (on id) and the id column itself
        DB::statement('ALTER TABLE teacher_grades DROP CONSTRAINT IF EXISTS teacher_grades_pkey');
        Schema::table('teacher_grades', function ($table) {
            $table->dropColumn('id');
        });

        // 3) Drop the old unique constraint on teacher_id (it becomes redundant once it's PK)
        DB::statement('ALTER TABLE teacher_grades DROP CONSTRAINT IF EXISTS teacher_grades_teacher_id_unique');

        // 4) Make teacher_id the real primary key
        DB::statement('ALTER TABLE teacher_grades ADD PRIMARY KEY (teacher_id)');

        // 5) Re-add the foreign key to teacher_infos
        DB::statement('
            ALTER TABLE teacher_grades
            ADD CONSTRAINT teacher_grades_teacher_id_foreign
            FOREIGN KEY (teacher_id) REFERENCES teacher_infos("Teacher_id")
        ');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE teacher_grades DROP CONSTRAINT IF EXISTS teacher_grades_teacher_id_foreign');
        DB::statement('ALTER TABLE teacher_grades DROP CONSTRAINT IF EXISTS teacher_grades_pkey');

        Schema::table('teacher_grades', function ($table) {
            $table->increments('id')->first();
        });

        DB::statement('ALTER TABLE teacher_grades ADD CONSTRAINT teacher_grades_teacher_id_unique UNIQUE (teacher_id)');
        DB::statement('
            ALTER TABLE teacher_grades
            ADD CONSTRAINT teacher_grades_teacher_id_foreign
            FOREIGN KEY (teacher_id) REFERENCES teacher_infos("Teacher_id")
        ');
    }
};