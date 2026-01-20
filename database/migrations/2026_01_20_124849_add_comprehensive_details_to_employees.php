<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Update Main Employees Table
        Schema::table('employees', function (Blueprint $table) {
            $table->string('middle_name')->nullable()->after('id'); // Assuming first/last name are in Users table, or here.
            $table->date('birthdate')->nullable();
            $table->string('birthplace')->nullable();
            $table->text('address')->nullable();
            $table->string('contact_number')->nullable();
            
            // Govt IDs
            $table->string('tin_no')->nullable();
            $table->string('sss_no')->nullable();
            $table->string('pagibig_no')->nullable();
            $table->string('philhealth_no')->nullable();
            
            // Personal
            $table->text('special_interests')->nullable();
            $table->text('hobbies')->nullable();
        });

        // 2. Education Table (One-to-Many)
        Schema::create('employee_education', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('level'); // Primary, Secondary, Tertiary, Post Grad, PhD
            $table->string('school_name');
            $table->date('date_graduated')->nullable();
            $table->string('diploma_path')->nullable(); // File Upload
            $table->timestamps();
        });

        // 3. Family Background (One-to-Many)
        Schema::create('employee_families', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('relation'); // Mother, Father, Sibling, Spouse, Child
            $table->string('name');
            $table->date('birthdate')->nullable();
            $table->string('birthplace')->nullable();
            $table->string('occupation')->nullable();
            $table->timestamps();
        });

        // 4. Trainings & Licenses (One-to-Many)
        Schema::create('employee_trainings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('title'); // License Name or Training Topic
            $table->string('type'); // 'License' or 'Training'
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable(); // For inclusive dates
            $table->string('certificate_path')->nullable(); // File Upload
            $table->timestamps();
        });

        // 5. Health Records (One-to-Many)
        Schema::create('employee_health', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('condition');
            $table->date('date_diagnosed')->nullable();
            $table->string('medication')->nullable();
            $table->string('dosage')->nullable();
            $table->timestamps();
        });

        // 6. Salary History (Crucial for Tracking Increments)
        Schema::create('salary_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->decimal('previous_salary', 10, 2);
            $table->decimal('new_salary', 10, 2);
            $table->date('effective_date');
            $table->string('reason')->nullable(); // e.g., "Annual Increment", "Promotion"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Drop tables in reverse order
        Schema::dropIfExists('salary_histories');
        Schema::dropIfExists('employee_health');
        Schema::dropIfExists('employee_trainings');
        Schema::dropIfExists('employee_families');
        Schema::dropIfExists('employee_education');
        
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'middle_name', 'birthdate', 'birthplace', 'address', 'contact_number',
                'tin_no', 'sss_no', 'pagibig_no', 'philhealth_no',
                'special_interests', 'hobbies'
            ]);
        });
    }
};