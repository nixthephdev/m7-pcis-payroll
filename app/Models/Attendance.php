<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'time_in',
        'time_out',
        'status'
    ];

    // This function was missing!
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}