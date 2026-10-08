<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('super_visors', function (Blueprint $table) {
            if (! Schema::hasColumn('super_visors', 'must_change_password')) {
                $table->boolean('must_change_password')->default(false);
            }
        });

        // Anyone still on the old shared default password must change it.
        DB::table('super_visors')
            ->select('SuperVisor_id', 'password')
            ->orderBy('SuperVisor_id')
            ->get()
            ->each(function ($row) {
                try {
                    $isDefault = Hash::check('ChangeMe123', $row->password);
                } catch (\Throwable $e) {
                    $isDefault = false; // not a bcrypt hash; leave it alone
                }

                if ($isDefault) {
                    DB::table('super_visors')
                        ->where('SuperVisor_id', $row->SuperVisor_id)
                        ->update(['must_change_password' => true]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('super_visors', function (Blueprint $table) {
            if (Schema::hasColumn('super_visors', 'must_change_password')) {
                $table->dropColumn('must_change_password');
            }
        });
    }
};