<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_id',
        'grade_level',
        'section',
        'guardian_name',
        'guardian_contact'
    ];

    // Link to User (Name, Email, Avatar)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // Link to Attendance (Polymorphic)
    public function attendances()
    {
        return $this->morphMany(Attendance::class, 'attendable');
    }
}