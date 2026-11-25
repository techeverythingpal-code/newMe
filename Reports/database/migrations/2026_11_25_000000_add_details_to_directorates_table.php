<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add contact + manager details to directorates.
     * All columns are nullable so existing rows are not affected.
     */
    public function up(): void
    {
        Schema::table('directorates', function (Blueprint $table) {
            $table->string('address')->nullable();        // العنوان
            $table->string('phone', 30)->nullable();      // الهاتف
            $table->string('email')->nullable();          // البريد الإلكتروني
            $table->string('manager_name')->nullable();   // اسم مدير المديرية
            $table->text('notes')->nullable();            // معلومات / ملاحظات
        });
    }

    public function down(): void
    {
        Schema::table('directorates', function (Blueprint $table) {
            $table->dropColumn(['address', 'phone', 'email', 'manager_name', 'notes']);
        });
    }
};