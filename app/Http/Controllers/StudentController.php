<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    // List all students
    public function index() {
        $students = Student::with('user')->get();
        return view('students.index', compact('students'));
    }

    // Show Create Form
    public function create() {
        return view('students.create');
    }

    // Store New Student
    public function store(Request $request) {
        $request->validate([
            'student_id' => 'required|string|unique:students',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'grade_level' => 'required|string',
            'section' => 'required|string',
            'guardian_name' => 'required|string',
            'guardian_contact' => 'required|string',
            'password' => 'required|min:8'
        ]);

        // Create User Account (Role: Student)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student'
        ]);

        // Create Student Profile
        Student::create([
            'user_id' => $user->id,
            'student_id' => $request->student_id,
            'grade_level' => $request->grade_level,
            'section' => $request->section,
            'guardian_name' => $request->guardian_name,
            'guardian_contact' => $request->guardian_contact
        ]);

        return redirect()->route('students.index')->with('message', 'New Student Enrolled Successfully!');
    }
    
    // Generate ID Card
    public function showIdCard($id) {
        $student = Student::with('user')->findOrFail($id);
        return view('students.id_card', compact('student'));
    }

    // Show Edit Form
    public function edit($id) {
        $student = Student::with('user')->findOrFail($id);
        return view('students.edit', compact('student'));
    }

    // Update Student
    public function update(Request $request, $id) {
        $student = Student::findOrFail($id);
        $user = $student->user;

        $request->validate([
            'student_id' => 'required|string|unique:students,student_id,'.$student->id,
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'grade_level' => 'required|string',
            'section' => 'required|string',
            'guardian_name' => 'required|string',
            'guardian_contact' => 'required|string'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email
        ]);

        $student->update([
            'student_id' => $request->student_id,
            'grade_level' => $request->grade_level,
            'section' => $request->section,
            'guardian_name' => $request->guardian_name,
            'guardian_contact' => $request->guardian_contact
        ]);

        return redirect()->route('students.index')->with('message', 'Student details updated successfully.');
    }

}