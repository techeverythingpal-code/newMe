<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add contact + principal details to schools.
     * All columns are nullable so existing rows are not affected.
     */
    public function up(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->string('principal_name')->nullable(); // اسم مدير المدرسة
            $table->string('address')->nullable();        // العنوان
            $table->string('phone', 30)->nullable();      // الهاتف
            $table->string('email')->nullable();          // البريد الإلكتروني
            $table->text('notes')->nullable();            // معلومات / ملاحظات
        });
    }

    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn(['principal_name', 'address', 'phone', 'email', 'notes']);
        });
    }
};