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
        'basic_salary',
        'schedule_id',
        'vacation_credits', // <--- MUST BE HERE
        'sick_credits',     // <--- MUST BE HERE
        'created_at'
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
}
