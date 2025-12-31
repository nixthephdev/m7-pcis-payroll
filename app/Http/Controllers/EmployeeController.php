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

    // Show the form to add a new employee
    public function create() {
        return view('employees.create');
    }

    // Save the new employee
    public function store(Request $request) {
        // 1. Validate Input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'position' => 'required|string',
            'salary' => 'required|numeric',
            'password' => 'required|min:8'
        ]);

        // 2. Create the User Login Account
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'employee' // Default role
        ]);

        // 3. Create the Employee Profile
        Employee::create([
            'user_id' => $user->id,
            'position' => $request->position,
            'basic_salary' => $request->salary
        ]);

        return redirect()->route('employees.index')->with('message', 'New Employee Added Successfully!');
    }

    // 1. Show Edit Form
    public function edit($id) {
        $employee = Employee::with('user')->findOrFail($id);
        return view('employees.edit', compact('employee'));
    }

    // 2. Save Changes
    public function update(Request $request, $id) {
        $employee = Employee::findOrFail($id);
        $user = $employee->user;

        // Validate
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id, // Allow own email
            'position' => 'required|string',
            'salary' => 'required|numeric',
            'joined_date' => 'required|date' // New field
        ]);

        // Update User Table (Name/Email)
        $user->update([
            'name' => $request->name,
            'email' => $request->email
        ]);

        // Update Employee Table (Position/Salary/Date)
        $employee->update([
            'position' => $request->position,
            'basic_salary' => $request->salary,
            'created_at' => $request->joined_date // We use created_at as joined date
        ]);

        return redirect()->route('employees.index')->with('message', 'Employee details updated successfully.');
    }
}