<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeFamily extends Model
{
    // 1. Fix Table Name (Singular)
    protected $table = 'employee_families';

    // 2. Fix Fillable
    protected $fillable = [
        'employee_id', 
        'relation', 
        'name', 
        'birthdate', 
        'occupation'
    ];
}