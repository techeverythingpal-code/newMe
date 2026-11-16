<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * directorates.Directorate_id was created with ->unique()->primary(), which
 * left two indexes on the same column: the primary key and a separate unique
 * index. The primary key already guarantees uniqueness, so the extra index
 * is dropped.
 *
 * Every step checks the current state first, so it is safe to run again.
 */
return new class extends Migration
{
    private const INDEX = 'directorates_directorate_id_unique';

    /** Tables whose directorate_id column points at directorates.Directorate_id */
    private const CHILD_TABLES = ['schools', 'super_visors'];

    public function up(): void
    {
        if (DB::connection()->getDriverName() === 'pgsql') {
            if (! $this->pgsqlUniqueExists()) {
                return;
            }

            // On Postgres a foreign key can be tied to the unique index itself,
            // so the foreign keys come off first and go back on afterwards
            // (they then attach to the primary key).
            foreach (self::CHILD_TABLES as $table) {
                DB::statement("ALTER TABLE {$table} DROP CONSTRAINT IF EXISTS {$table}_directorate_id_foreign");
            }

            DB::statement('ALTER TABLE directorates DROP CONSTRAINT ' . self::INDEX);

            foreach (self::CHILD_TABLES as $table) {
                DB::statement("
                    ALTER TABLE {$table}
                    ADD CONSTRAINT {$table}_directorate_id_foreign
                    FOREIGN KEY (directorate_id) REFERENCES directorates(\"Directorate_id\")
                ");
            }

            return;
        }

        // MySQL / MariaDB: the foreign keys keep working through the primary key.
        if ($this->mysqlIndexExists()) {
            DB::statement('ALTER TABLE directorates DROP INDEX ' . self::INDEX);
        }
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'pgsql') {
            if (! $this->pgsqlUniqueExists()) {
                DB::statement('ALTER TABLE directorates ADD CONSTRAINT ' . self::INDEX . ' UNIQUE ("Directorate_id")');
            }

            return;
        }

        if (! $this->mysqlIndexExists()) {
            DB::statement('ALTER TABLE directorates ADD UNIQUE KEY ' . self::INDEX . ' (Directorate_id)');
        }
    }

    private function mysqlIndexExists(): bool
    {
        return (bool) DB::selectOne("
            SELECT 1 AS found FROM information_schema.statistics
            WHERE table_schema = DATABASE()
              AND table_name = 'directorates'
              AND index_name = ?
            LIMIT 1
        ", [self::INDEX]);
    }

    private function pgsqlUniqueExists(): bool
    {
        return (bool) DB::selectOne("
            SELECT 1 AS found FROM information_schema.table_constraints
            WHERE table_schema = current_schema()
              AND table_name = 'directorates'
              AND constraint_name = ?
            LIMIT 1
        ", [self::INDEX]);
    }
};