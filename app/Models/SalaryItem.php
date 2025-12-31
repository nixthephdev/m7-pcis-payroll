<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryItem extends Model
{
    use HasFactory;

    // This was the missing part!
    protected $fillable = [
        'employee_id', 
        'name', 
        'amount', 
        'type'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}