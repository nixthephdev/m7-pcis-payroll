<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Schedule;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    // List all employees
    public function index() {
        $employees = Employee::with('user')->get();
        return view('employees.index', compact('employees'));
    }

    // Show Create Form
    public function create() {
        $schedules = Schedule::all(); 
        
        // Fetch potential supervisors (Heads/Coordinators)
        $supervisors = Employee::with('user')
            ->where(function($query) {
                $query->where('position', 'LIKE', '%Head%')
                      ->orWhere('position', 'LIKE', '%Coordinator%')
                      ->orWhere('position', 'LIKE', '%Manager%')
                      ->orWhere('position', 'LIKE', '%Principal%')
                      ->orWhere('position', 'LIKE', '%Supervisor%');
            })
            ->get();

        return view('employees.create', compact('schedules', 'supervisors'));
    }

    // Store New Employee
    public function store(Request $request) {
        $request->validate([
            'employee_code' => 'required|string|unique:employees',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'role' => 'required|in:employee,guard,admin',
            'job_position' => 'required|string', // Form uses job_position
            'basic_salary' => 'required|numeric', // Form uses basic_salary
            'password' => 'required|min:8',
            'schedule_id' => 'required|exists:schedules,id',
            'vacation_credits' => 'nullable|integer',
            'sick_credits' => 'nullable|integer',
            'supervisor_id' => 'nullable|exists:employees,id',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Create User
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role
            ]);

            // 2. Create Employee
            Employee::create([
                'user_id' => $user->id,
                'employee_code' => $request->employee_code,
                'position' => $request->job_position, // Corrected Mapping
                'basic_salary' => $request->basic_salary, // Corrected Mapping
                'schedule_id' => $request->schedule_id,
                'vacation_credits' => $request->vacation_credits ?? 15,
                'sick_credits' => $request->sick_credits ?? 15,
                'supervisor_id' => $request->supervisor_id,
            ]);

            AuditLog::record('Created Employee', 'Added new employee: ' . $request->name);
        });

        return redirect()->route('employees.index')->with('message', 'New Employee Added Successfully!');
    }

    // Show Edit Form
    public function edit($id) {
        $employee = Employee::with('user')->findOrFail($id);
        
        // 1. Get Supervisors (Heads/Coordinators only)
        $supervisors = Employee::with('user')
            ->where('id', '!=', $id)
            ->where(function($query) {
                $query->where('position', 'LIKE', '%Head%')
                      ->orWhere('position', 'LIKE', '%Coordinator%')
                      ->orWhere('position', 'LIKE', '%Manager%')
                      ->orWhere('position', 'LIKE', '%Principal%')
                      ->orWhere('position', 'LIKE', '%Supervisor%');
            })
            ->get();
        
        // Fallback if empty
        if ($supervisors->isEmpty()) {
             $supervisors = Employee::with('user')->where('id', '!=', $id)->get();
        }

        // 2. Get Schedules
        $schedules = Schedule::all(); 

        return view('employees.edit', compact('employee', 'supervisors', 'schedules'));
    }

    // Update Employee
    public function update(Request $request, $id) {
        $employee = Employee::findOrFail($id);
        $user = $employee->user;

        $request->validate([
            'employee_code' => 'required|string',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'position' => 'required|string',
            'salary' => 'required|numeric', // Edit form uses name="salary"
            'joined_date' => 'required|date',
            'schedule_id' => 'required|exists:schedules,id',
            'vacation_credits' => 'required|integer',
            'sick_credits' => 'required|integer',
            'supervisor_id' => 'nullable|exists:employees,id'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email
        ]);

        $employee->update([
            'employee_code' => $request->employee_code,
            'position' => $request->position,
            'basic_salary' => $request->salary, // Maps "salary" input to "basic_salary" column
            'schedule_id' => $request->schedule_id,
            'vacation_credits' => $request->vacation_credits,
            'sick_credits' => $request->sick_credits,
            'created_at' => $request->joined_date,
            'supervisor_id' => $request->supervisor_id
        ]);

        AuditLog::record('Updated Employee', 'Updated profile of ' . $request->name);

        return redirect()->route('employees.index')->with('message', 'Employee details updated successfully.');
    }

    // Show ID Card
    public function showIdCard($id) {
        $employee = Employee::with('user')->findOrFail($id);
        return view('employees.id_card', compact('employee'));
    }
}