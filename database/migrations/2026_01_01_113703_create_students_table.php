<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            // Link to User table (for login/photo)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Student Specifics
            $table->string('student_id')->unique(); // e.g., S-2025-001
            $table->string('grade_level'); // Grade 10, Grade 11
            $table->string('section'); // Section A
            $table->string('guardian_name');
            $table->string('guardian_contact');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
};
