<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'leave_type',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'total_hours',
        'reason',
        'status',
        'supervisor_status',
        'is_paid',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}