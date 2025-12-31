<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'position', 'basic_salary'];

    // This links the Employee to the User (Name/Email)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // This links the Employee to their Payrolls
    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }
}