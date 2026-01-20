<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SalaryHistory extends Model
{
    protected $fillable = ['employee_id', 'previous_salary', 'new_salary', 'effective_date', 'reason'];
}