<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeHealthExam extends Model
{
    protected $table = 'employee_health_exams';

    protected $fillable = [
        'employee_id',
        'exam_type',
        'exam_year',
        'result_notes',
        'result_path',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
