<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // super_visors.SuperVisor_id: bigserial -> plain integer, manual entry
        DB::statement('ALTER TABLE super_visors ALTER COLUMN "SuperVisor_id" DROP DEFAULT');
        DB::statement('ALTER TABLE super_visors ALTER COLUMN "SuperVisor_id" TYPE integer');
        DB::statement('DROP SEQUENCE IF EXISTS super_visors_SuperVisor_id_seq');

        // teacher_infos.id: serial -> plain integer, manual entry
        DB::statement('ALTER TABLE teacher_infos ALTER COLUMN "id" DROP DEFAULT');
        DB::statement('DROP SEQUENCE IF EXISTS teacher_infos_id_seq');

        // teacher_grades.id: serial -> plain integer, manual entry
        DB::statement('ALTER TABLE teacher_grades ALTER COLUMN "id" DROP DEFAULT');
        DB::statement('DROP SEQUENCE IF EXISTS teacher_grades_id_seq');
    }

    public function down(): void
    {
        // Restore auto-increment behavior
        DB::statement('CREATE SEQUENCE IF NOT EXISTS super_visors_SuperVisor_id_seq');
        DB::statement('ALTER TABLE super_visors ALTER COLUMN "SuperVisor_id" TYPE bigint');
        DB::statement('ALTER TABLE super_visors ALTER COLUMN "SuperVisor_id" SET DEFAULT nextval(\'super_visors_SuperVisor_id_seq\')');

        DB::statement('CREATE SEQUENCE IF NOT EXISTS teacher_infos_id_seq');
        DB::statement('ALTER TABLE teacher_infos ALTER COLUMN "id" SET DEFAULT nextval(\'teacher_infos_id_seq\')');

        DB::statement('CREATE SEQUENCE IF NOT EXISTS teacher_grades_id_seq');
        DB::statement('ALTER TABLE teacher_grades ALTER COLUMN "id" SET DEFAULT nextval(\'teacher_grades_id_seq\')');
    }
};