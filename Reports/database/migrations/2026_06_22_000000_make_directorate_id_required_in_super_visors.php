<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Safety check: fail loudly if any existing supervisor has no directorate set
        $missing = DB::table('super_visors')->whereNull('directorate_id')->count();
        if ($missing > 0) {
            throw new \Exception("Cannot make directorate_id required: {$missing} supervisor(s) have no directorate set. Please assign a directorate to all supervisors first.");
        }

        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE super_visors ALTER COLUMN directorate_id SET NOT NULL');
        } else {
            DB::statement('ALTER TABLE super_visors MODIFY directorate_id INT NOT NULL');
        }
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE super_visors ALTER COLUMN directorate_id DROP NOT NULL');
        } else {
            DB::statement('ALTER TABLE super_visors MODIFY directorate_id INT NULL');
        }
    }
};