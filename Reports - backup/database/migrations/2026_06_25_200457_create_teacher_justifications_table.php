<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_justifications', function (Blueprint $table) {
            $table->integer('teacher_id')->primary();
            $table->foreign('teacher_id')->references('Teacher_id')->on('teacher_infos');
            $table->text('strengths')->nullable();
            $table->text('weaknesses')->nullable();
            $table->text('recommendations')->nullable();
            $table->text('preparer_opinion')->nullable();
            $table->text('approver_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_justifications');
    }
};