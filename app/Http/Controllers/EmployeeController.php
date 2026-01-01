<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Schedule;

class EmployeeController extends Controller
{
    // List all employees
    public function index() {
        $employees = Employee::with('user')->get();
        return view('employees.index', compact('employees'));
    }

    // Show Create Form
    public function create()
{
    // Make sure this line is here!
    $schedules = \App\Models\Schedule::all(); 
    
    return view('employees.create', compact('schedules'));
}

public function edit($id)
{
    $employee = Employee::find($id);
    $schedules = Schedule::all(); // Fetch here too
    return view('employees.edit', compact('employee', 'schedules'));
}

    public function store(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'employee_code' => 'required|string|max:255|unique:employees,employee_code',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'job_position' => 'required|string|max:255',
            'schedule_id' => 'required|exists:schedules,id',
            'basic_salary' => 'required|numeric|min:0',
            'role' => 'required|in:employee,guard,admin',
            'password' => 'required|string|min:6',
        ]);

        // Create the User account first
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Create the Employee record linked to the User
        Employee::create([
            'user_id' => $user->id,
            'employee_code' => $request->employee_code,
            'position' => $request->job_position,
            'basic_salary' => $request->basic_salary,
            'schedule_id' => $request->schedule_id,
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee created successfully!');
    }

    // Show Edit Form
    // public function edit($id) {
    //     $employee = Employee::with('user')->findOrFail($id);
    //     return view('employees.edit', compact('employee'));
    // }

    public function update(Request $request, $id)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'employee_code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'position' => 'required|string|max:255',
            'schedule_id' => 'required|exists:schedules,id',
        ]);

        // Find the employee
        $employee = Employee::findOrFail($id);

        // Update the User model (name and email)
        $employee->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update the Employee model (employee_code, position, schedule_id)
        $employee->update([
            'employee_code' => $request->employee_code,
            'position' => $request->position,
            'schedule_id' => $request->schedule_id,
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully!');
    }

    // --- NEW: SHOW ID CARD ---
    public function showIdCard($id) {
        $employee = Employee::with('user')->findOrFail($id);
        return view('employees.id_card', compact('employee'));
    }
}