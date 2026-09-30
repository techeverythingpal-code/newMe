<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teacher_infos', function (Blueprint $table) {
            if (! Schema::hasColumn('teacher_infos', 'supervisor_note')) {
                $table->string('supervisor_note', 255)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('teacher_infos', function (Blueprint $table) {
            if (Schema::hasColumn('teacher_infos', 'supervisor_note')) {
                $table->dropColumn('supervisor_note');
            }
        });
    }
};
