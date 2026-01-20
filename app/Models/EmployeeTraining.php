<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class EmployeeTraining extends Model {
    protected $table = 'employee_trainings';
    protected $fillable = ['employee_id', 'title', 'type', 'start_date', 'end_date', 'certificate_path'];
}