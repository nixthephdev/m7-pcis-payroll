<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeEmploymentHistory extends Model
{
    protected $table = 'employee_employment_history';

    protected $fillable = [
        'employee_id',
        'from_date',
        'to_date',
        'company_name',
        'designation',
        'coe_path',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
