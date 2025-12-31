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
}