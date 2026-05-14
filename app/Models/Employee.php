<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_code',
        'position',
        'department',
        'basic_salary',
        'start_date',
        'joining_date',
        'status',
        'supervisor_id',
        'schedule_id',
        // Leave credits
        'vacation_credits',
        'sick_credits',
        'birthday_leave_credits',
        'solo_parent_leave_credits',
        'is_solo_parent',
        'incentive_hours_credits',
        // Personal info
        'first_name', 'last_name', 'middle_name',
        'photo_path',
        'birthdate', 'birth_certificate_path',
        'birthplace', 'marital_status',
        'personal_email', 'contact_number',
        'address',
        'special_interests', 'hobbies',
        'emergency_contact_person', 'emergency_contact_number',
        'mental_health',
        // Government IDs
        'tin_no', 'sss_no', 'pagibig_no', 'philhealth_no',
        'nbi_clearance_path',
        'tin_proof_path', 'sss_proof_path', 'philhealth_proof_path', 'pagibig_proof_path',
        // Bank
        'bank_name', 'bank_account_name', 'bank_account_number', 'bank_proof_path',
    ];

    // This links the Employee to the User (Name/Email)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // This links the Employee to their Schedule
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    // This links the Employee to their Payrolls
    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    // Link to Salary Items (Allowances/Deductions)
    public function salaryItems()
    {
        return $this->hasMany(SalaryItem::class);
    }

    // Link to Attendance (Polymorphic)
    public function attendances()
    {
        return $this->morphMany(Attendance::class, 'attendable');
    }

        // Who is my boss?
        public function supervisor() {
            return $this->belongsTo(Employee::class, 'supervisor_id');
        }

        // Who works for me?
        public function subordinates() {
            return $this->hasMany(Employee::class, 'supervisor_id');
        }
    // ... existing code ...

    public function education() {
        return $this->hasMany(EmployeeEducation::class);
    }

    public function family() {
        return $this->hasMany(EmployeeFamily::class);
    }

    public function trainings() {
        return $this->hasMany(EmployeeTraining::class);
    }

    public function health() {
        return $this->hasMany(EmployeeHealth::class);
    }

    public function salaryHistory() {
        return $this->hasMany(SalaryHistory::class)->orderBy('effective_date', 'desc');
    }

    public function employmentHistory() {
        return $this->hasMany(EmployeeEmploymentHistory::class)->orderBy('from_date', 'desc');
    }

    public function healthExams() {
        return $this->hasMany(EmployeeHealthExam::class)->orderBy('exam_year', 'desc');
    }
}
