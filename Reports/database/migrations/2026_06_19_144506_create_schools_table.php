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
        Schema::create('schools', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('School_ID')->primary();
        $table->string('SchoolName')->nullable();
        $table->integer('directorate_id');
        $table->foreign('directorate_id')->references('Directorate_id')->on('directorates');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
