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
        $employee = Employee::with(['user', 'education', 'family', 'trainings', 'health', 'salaryHistory'])->findOrFail($id);
        return view('employees.edit', compact('employee'));
        
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

    public function updatePersonal(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        
        // 1. Update User Name ONLY (Do not touch email here)
        $employee->user->update([
            'name' => $request->first_name . ' ' . $request->last_name,
            // 'email' => $request->email  <-- REMOVE THIS LINE
        ]);

        // 2. Update Employee Details (201 File)
        $employee->update($request->all());

        return redirect()->back()
    ->with('message', 'Personal details updated successfully.')
    ->with('active_tab', 'personal'); // <--- ADD THIS
    }


    public function updateSalary(Request $request, $id)
    {
        $request->validate([
            'new_salary' => 'required|numeric',
            'effective_date' => 'required|date',
            'reason' => 'required|string'
        ]);

        $employee = Employee::findOrFail($id);
        $oldSalary = $employee->basic_salary;

        // 1. Record History
        \App\Models\SalaryHistory::create([
            'employee_id' => $employee->id,
            'previous_salary' => $oldSalary,
            'new_salary' => $request->new_salary,
            'effective_date' => $request->effective_date,
            'reason' => $request->reason,
        ]);

        // 2. Update Current Salary
        $employee->update(['basic_salary' => $request->new_salary]);

       return redirect()->back()
    ->with('message', 'Salary updated and history recorded.')
    ->with('active_tab', 'salary'); // <--- ADD THIS
    }
    
    public function storeEducation(Request $request, $id)
    {
        // 1. Add Validation (Make date required)
        $request->validate([
            'school_name' => 'required',
            'level' => 'required',
            'date_graduated' => 'required|date', // <--- Added Required
            'diploma' => 'nullable|file|mimes:pdf,jpg,png|max:2048'
        ]);

        // 2. Handle File Upload
        $path = null;
        if($request->hasFile('diploma')){
            $path = $request->file('diploma')->store('diplomas', 'public');
        }

        // 3. Create Record
        \App\Models\EmployeeEducation::create([
            'employee_id' => $id,
            'level' => $request->level,
            'school_name' => $request->school_name,
            'date_graduated' => $request->date_graduated,
            'diploma_path' => $path
        ]);

        // 4. Return with Success Message
        return redirect()->back()
    ->with('message', 'Education added successfully.')
    ->with('active_tab', 'education'); // <--- ADD THIS

    }
    public function storeFamily(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'relation' => 'required',
            'birthdate' => 'nullable|date',
            'occupation' => 'nullable|string'
        ]);

        \App\Models\EmployeeFamily::create([
            'employee_id' => $id,
            'name' => $request->name,
            'relation' => $request->relation,
            'birthdate' => $request->birthdate,
            'occupation' => $request->occupation
        ]);

        return redirect()->back()
    ->with('message', 'Family member added successfully.')
    ->with('active_tab', 'family'); // <--- ADD THIS
    }
    
    // --- STORE TRAINING / LICENSE ---
    public function storeTraining(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string',
            'type' => 'required|string', // License or Training
            'start_date' => 'nullable|date',
            'certificate' => 'nullable|file|mimes:pdf,jpg,png|max:2048'
        ]);

        $path = null;
        if($request->hasFile('certificate')){
            $path = $request->file('certificate')->store('certificates', 'public');
        }

        \App\Models\EmployeeTraining::create([
            'employee_id' => $id,
            'title' => $request->title,
            'type' => $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'certificate_path' => $path
        ]);

        return redirect()->back()->with('message', 'Training/License added successfully.')->with('active_tab', 'training');
    }

    // --- STORE HEALTH RECORD ---
    public function storeHealth(Request $request, $id)
    {
        $request->validate([
            'condition' => 'required|string',
            'date_diagnosed' => 'nullable|date',
        ]);

        \App\Models\EmployeeHealth::create([
            'employee_id' => $id,
            'condition' => $request->condition,
            'date_diagnosed' => $request->date_diagnosed,
            'medication' => $request->medication,
            'dosage' => $request->dosage
        ]);

        return redirect()->back()->with('message', 'Health record added successfully.')->with('active_tab', 'health');
    }
}