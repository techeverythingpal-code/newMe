<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Allow SuperVisor_id to be set manually instead of auto-generated
        DB::statement('ALTER TABLE super_visors ALTER COLUMN "SuperVisor_id" DROP IDENTITY IF EXISTS');

        // If a supervisor's ID is changed later, automatically update any
        // linked teacher records so they keep pointing to the right supervisor
        Schema::table('teacher_infos', function (Blueprint $table) {
            $table->dropForeign(['supervisor_id']);
            $table->foreign('supervisor_id')
                ->references('SuperVisor_id')->on('super_visors')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('teacher_infos', function (Blueprint $table) {
            $table->dropForeign(['supervisor_id']);
            $table->foreign('supervisor_id')->references('SuperVisor_id')->on('super_visors');
        });
    }
};