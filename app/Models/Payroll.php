<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 
        'pay_date', 
        'gross_salary', 
        'deductions', 
        'net_salary',
        'status',
        'period' // <--- Added comma on previous line and added 'period' here
    ];

    // This function links Payroll to Employee
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}