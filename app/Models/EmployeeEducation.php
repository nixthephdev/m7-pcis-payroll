<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeEducation extends Model
{
    // 1. Fix Table Name
    protected $table = 'employee_education';

    // 2. Fix Fillable (Make sure date_graduated is here!)
    protected $fillable = [
        'employee_id', 
        'level', 
        'school_name', 
        'date_graduated', // <--- THIS WAS LIKELY MISSING
        'diploma_path'
    ];
}