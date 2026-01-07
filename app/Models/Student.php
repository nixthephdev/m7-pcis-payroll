<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    public $timestamps = false; // Keep this false as requested

    protected $fillable = [
        'student_id',
        'full_name', // <--- Changed to full_name
        'email',
        'grade_level',
        'section',
        'guardian_name',
        'guardian_contact',
    ];

    public function attendance()
    {
        return $this->morphMany(Attendance::class, 'attendable');
    }
}