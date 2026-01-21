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
        'status',
        // IMPORTANT: Add these two lines
        'supervisor_id', 
        'schedule_id',
        // Leave credits
        'vacation_credits',
        'sick_credits',
        // 201 File info
        'middle_name', 'birthdate', 'birthplace', 'address', 'contact_number',
        'tin_no', 'sss_no', 'pagibig_no', 'philhealth_no',
        'special_interests', 'hobbies', 'mental_health'
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
}
