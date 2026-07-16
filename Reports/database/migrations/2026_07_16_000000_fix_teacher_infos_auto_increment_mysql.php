<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::connection()->getDriverName() !== 'mysql') {
            return;
        }

        DB::statement('ALTER TABLE teacher_infos MODIFY id INT UNSIGNED NOT NULL AUTO_INCREMENT');

        $maxId = DB::table('teacher_infos')->max('id') ?? 0;
        DB::statement('ALTER TABLE teacher_infos AUTO_INCREMENT = ' . ($maxId + 1));
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() !== 'mysql') {
            return;
        }

        DB::statement('ALTER TABLE teacher_infos MODIFY id INT UNSIGNED NOT NULL');
    }
};