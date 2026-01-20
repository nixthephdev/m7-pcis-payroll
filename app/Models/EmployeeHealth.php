<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class EmployeeHealth extends Model {
    protected $table = 'employee_health'; // Singular table name in DB
    protected $fillable = ['employee_id', 'condition', 'date_diagnosed', 'medication', 'dosage'];
}