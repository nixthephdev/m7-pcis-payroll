<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{
    public function index() {
        $students = Student::all(); 
        return view('students.index', compact('students'));
    }

    public function create() {
        return view('students.create');
    }

    public function store(Request $request) {
        $request->validate([
            'student_id' => 'required|unique:students,student_id',
            'full_name'  => 'required|string',
            'grade_level'=> 'required|string',
            'section'    => 'required|string',
            'guardian_name' => 'required|string',
            'guardian_contact' => 'required|string',
        ]);

        Student::create([
            'student_id' => $request->student_id,
            'full_name'  => $request->full_name, // Direct save
            'email'      => $request->email,
            'grade_level'=> $request->grade_level,
            'section'    => $request->section,
            'guardian_name' => $request->guardian_name,
            'guardian_contact' => $request->guardian_contact,
        ]);

        return redirect()->route('students.index')->with('message', 'Student added successfully.');
    }

    public function edit($id) {
        $student = Student::findOrFail($id);
        return view('students.edit', compact('student'));
    }

    public function update(Request $request, $id) {
        $student = Student::findOrFail($id);

        $request->validate([
            'student_id' => 'required|unique:students,student_id,' . $id,
            'full_name'  => 'required|string',
            'grade_level'=> 'required|string',
            'section'    => 'required|string',
            'guardian_name' => 'required|string',
            'guardian_contact' => 'required|string',
        ]);

        $student->update([
            'student_id' => $request->student_id,
            'full_name'  => $request->full_name, // Direct update
            'email'      => $request->email,
            'grade_level'=> $request->grade_level,
            'section'    => $request->section,
            'guardian_name' => $request->guardian_name,
            'guardian_contact' => $request->guardian_contact,
        ]);

        return redirect()->route('students.index')->with('message', 'Student updated successfully.');
    }

    public function destroy($id) {
        $student = Student::findOrFail($id);
        $student->delete();
        return redirect()->route('students.index')->with('message', 'Student deleted successfully.');
    }
}