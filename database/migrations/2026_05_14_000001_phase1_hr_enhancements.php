<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add new columns to employees table
        Schema::table('employees', function (Blueprint $table) {
            $table->string('photo_path')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('personal_email')->nullable();
            $table->string('birth_certificate_path')->nullable();

            // Government ID proof attachments
            $table->string('nbi_clearance_path')->nullable();
            $table->string('tin_proof_path')->nullable();
            $table->string('sss_proof_path')->nullable();
            $table->string('philhealth_proof_path')->nullable();
            $table->string('pagibig_proof_path')->nullable();

            // Bank account details
            $table->string('bank_name')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_proof_path')->nullable();

            // Employment
            $table->date('joining_date')->nullable();

            // Additional leave credits
            $table->unsignedInteger('birthday_leave_credits')->default(1);
            $table->unsignedInteger('solo_parent_leave_credits')->default(0);
            $table->boolean('is_solo_parent')->default(false);
            $table->decimal('incentive_hours_credits', 8, 2)->default(0);

            // Emergency contact
            $table->string('emergency_contact_person')->nullable();
            $table->string('emergency_contact_number')->nullable();
        });

        // 2. Add missing columns to employee_trainings
        Schema::table('employee_trainings', function (Blueprint $table) {
            $table->string('license_no')->nullable()->after('title');
            $table->date('expiry_date')->nullable()->after('end_date');
        });

        // 3. Add Transcript of Records to employee_education
        Schema::table('employee_education', function (Blueprint $table) {
            $table->string('tor_path')->nullable()->after('diploma_path');
        });

        // 4. Employment History (new table)
        Schema::create('employee_employment_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('from_date');   // "Month Year" stored as string e.g. "January 2020"
            $table->string('to_date')->nullable(); // null = Present
            $table->string('company_name');
            $table->string('designation');
            $table->string('coe_path')->nullable();
            $table->timestamps();
        });

        // 5. Annual Health Exams (APE + Drug Test)
        Schema::create('employee_health_exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('exam_type'); // 'APE' or 'DrugTest'
            $table->year('exam_year');
            $table->text('result_notes')->nullable();
            $table->string('result_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_health_exams');
        Schema::dropIfExists('employee_employment_history');

        Schema::table('employee_education', function (Blueprint $table) {
            $table->dropColumn('tor_path');
        });

        Schema::table('employee_trainings', function (Blueprint $table) {
            $table->dropColumn(['license_no', 'expiry_date']);
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'photo_path', 'marital_status', 'personal_email', 'birth_certificate_path',
                'nbi_clearance_path', 'tin_proof_path', 'sss_proof_path', 'philhealth_proof_path', 'pagibig_proof_path',
                'bank_name', 'bank_account_name', 'bank_account_number', 'bank_proof_path',
                'joining_date',
                'birthday_leave_credits', 'solo_parent_leave_credits', 'is_solo_parent', 'incentive_hours_credits',
                'emergency_contact_person', 'emergency_contact_number',
            ]);
        });
    }
};
