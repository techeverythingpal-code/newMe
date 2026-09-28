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
        Schema::create('teacher_grades', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('teacher_id')->unique();
        $table->foreign('teacher_id')->references('Teacher_id')->on('teacher_infos');
        $table->integer('score1');
        $table->integer('score2');
        $table->integer('score3');
        $table->integer('score4');
        $table->integer('score5');
        $table->integer('score6');
        $table->integer('score7');
        $table->integer('score8');
        $table->integer('score9');
        $table->integer('score10');
        $table->integer('score11');
        $table->integer('score12');
        $table->integer('score13');
        $table->integer('score14');
        $table->integer('score15');
        $table->integer('score16');
        $table->integer('score17');
        $table->integer('score18');
        $table->integer('score19');
        $table->integer('score20');
        $table->integer('score21');
        $table->integer('score22');
        $table->integer('total');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_grades');
    }
};
