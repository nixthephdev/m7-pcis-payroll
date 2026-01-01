<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendable_id',   // <--- This was likely missing or named employee_id
        'attendable_type', // <--- This too
        'date',
        'time_in',
        'time_out',
        'status'
    ];

    // Polymorphic Relationship
    public function attendable()
    {
        return $this->morphTo();
    }
}