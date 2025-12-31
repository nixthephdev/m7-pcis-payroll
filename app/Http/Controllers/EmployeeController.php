<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    // List all employees
    public function index() {
        $employees = Employee::with('user')->get();
        return view('employees.index', compact('employees'));
    }

    // Show Create Form
    public function create() {
        return view('employees.create');
    }

    // Store New Employee
    public function store(Request $request) {
        $request->validate([
            'employee_code' => 'required|string|unique:employees', // <--- Validate Unique
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'position' => 'required|string',
            'salary' => 'required|numeric',
            'password' => 'required|min:8'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'employee'
        ]);

        Employee::create([
            'user_id' => $user->id,
            'employee_code' => $request->employee_code, // <--- Save Code
            'position' => $request->position,
            'basic_salary' => $request->salary
        ]);

        return redirect()->route('employees.index')->with('message', 'New Employee Added Successfully!');
    }

    // Show Edit Form
    public function edit($id) {
        $employee = Employee::with('user')->findOrFail($id);
        return view('employees.edit', compact('employee'));
    }

    // Update Employee
    public function update(Request $request, $id) {
        $employee = Employee::findOrFail($id);
        $user = $employee->user;

        $request->validate([
            'employee_code' => 'required|string|unique:employees,employee_code,'.$employee->id, // Ignore self
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'position' => 'required|string',
            'salary' => 'required|numeric',
            'joined_date' => 'required|date'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email
        ]);

        $employee->update([
            'employee_code' => $request->employee_code, // <--- Update Code
            'position' => $request->position,
            'basic_salary' => $request->salary,
            'created_at' => $request->joined_date
        ]);

        return redirect()->route('employees.index')->with('message', 'Employee details updated successfully.');
    }

    // --- NEW: SHOW ID CARD ---
    public function showIdCard($id) {
        $employee = Employee::with('user')->findOrFail($id);
        return view('employees.id_card', compact('employee'));
    }
}