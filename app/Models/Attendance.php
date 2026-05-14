<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendable_id',
        'attendable_type',
        'date',
        'time_in',
        'time_out',
        'status',
        'tardy_minutes',
        'undertime_minutes',
        'overtime_minutes',
        'overtime_type',
    ];

    // Polymorphic Relationship
    public function attendable()
    {
        return $this->morphTo();
    }
}