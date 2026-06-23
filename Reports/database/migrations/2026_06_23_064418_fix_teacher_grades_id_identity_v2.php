<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // No-op: teacher_grades.id is already a proper identity column.
    }

    public function down(): void
    {
        // No-op.
    }
};