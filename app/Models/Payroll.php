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
        'net_salary'
    ];

    // This function was missing!
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}