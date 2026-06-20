<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('teacher_infos', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('supervisor_id');
        $table->string('Teacher_Name');
        $table->integer('Teacher_id')->unique();
        $table->integer('school_id');
        $table->date('date'); // تاريخ التعيين
        $table->string('teacher_qualify'); // المؤهل
        $table->string('teacher_major'); // التخصص

        $table->foreign('supervisor_id')->references('SuperVisor_id')->on('super_visors');
        $table->foreign('school_id')->references('School_ID')->on('schools');
        
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_infos');
    }
};
