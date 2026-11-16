<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * super_visors.role ('admin' | 'user') never granted any access: admin rights
 * come only from logging in through the users table (the admin guard).
 * The column is dropped so there is a single source of admin rights.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('super_visors', 'role')) {
            Schema::table('super_visors', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }
    }

    /**
     * Restores the column. Every supervisor comes back as 'user';
     * the old per-row values are not recoverable.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('super_visors', 'role')) {
            Schema::table('super_visors', function (Blueprint $table) {
                $table->enum('role', ['admin', 'user'])->default('user')->after('SuperVisor_Major');
            });
        }
    }
};