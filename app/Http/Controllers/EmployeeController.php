<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;
use App\Models\Schedule;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    // --- LIST EMPLOYEES ---
    public function index() {
        $employees = Employee::with('user')->get();
        return view('employees.index', compact('employees'));
    }

    // --- SHOW CREATE FORM ---
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

        // Fallback if no specific heads found, show all employees
        if ($supervisors->isEmpty()) {
            $supervisors = Employee::with('user')->get();
        }

        return view('employees.create', compact('schedules', 'supervisors'));
    }

    // --- STORE NEW EMPLOYEE ---
    // --- STORE NEW EMPLOYEE ---
    public function store(Request $request)
    {
        // 1. Validate (Updated to match your View's input names)
        $request->validate([
            'first_name' => 'required|string|max:255', // Changed from 'name'
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'employee_code' => 'required|string|unique:employees',
            'position' => 'required|string', // Changed from 'job_position' to match HTML name="position"
            'role' => 'required',
            'password' => 'required',
        ]);

        // 2. Create User Account (Combine First + Last name)
        $user = \App\Models\User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // 3. Create Employee Profile
        $employee = \App\Models\Employee::create([
            'user_id' => $user->id,
            'employee_code' => $request->employee_code,
            'position' => $request->position, // Map input 'position' to DB column
            'supervisor_id' => $request->supervisor_id,
            'schedule_id' => $request->schedule_id,
            'basic_salary' => $request->basic_salary ?? 0,
            'vacation_credits' => $request->vacation_credits ?? 0,
            'sick_credits' => $request->sick_credits ?? 0,
            
            // Personal Info (201 File)
            'middle_name' => $request->middle_name,
            'birthdate' => $request->birthdate,
            'contact_number' => $request->contact_number,
            'address' => $request->address,
            'tin_no' => $request->tin_no,
            'sss_no' => $request->sss_no,
            'philhealth_no' => $request->philhealth_no,
            'pagibig_no' => $request->pagibig_no,
            'hobbies' => $request->hobbies,
        ]);

        // 4. Redirect to Edit Page (Education Tab)
        return redirect()->route('employees.edit', $employee->id)
            ->with('message', 'Employee record created! You can now add Education, Family, and other details.')
            ->with('active_tab', 'education'); 
    }

    // --- SHOW EDIT FORM ---
    public function edit($id) {
        $employee = Employee::with(['user', 'education', 'family', 'trainings', 'health', 'salaryHistory'])->findOrFail($id);
        
        // STRICT FILTER: Only show Heads, Coordinators, Principals, Managers
        $supervisors = Employee::with('user')
            ->where('id', '!=', $id) // Exclude the employee themselves
            ->where(function($query) {
                $query->where('position', 'LIKE', '%Head%')        // Covers "Head of IT", "Headmaster"
                      ->orWhere('position', 'LIKE', '%Coordinator%') // Covers "MYP Coordinator"
                      ->orWhere('position', 'LIKE', '%Principal%')
                      ->orWhere('position', 'LIKE', '%Manager%')
                      ->orWhere('position', 'LIKE', '%Director%')
                      ->orWhere('position', 'LIKE', '%Supervisor%');
            })
            ->orderBy('position', 'asc') // Sort nicely
            ->get();

        $schedules = Schedule::all(); 

        return view('employees.edit', compact('employee', 'supervisors', 'schedules'));
    }

    // --- UPDATE EMPLOYMENT DETAILS (Tab 1) ---
    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        
        $request->validate([
            'email' => 'required|email|unique:users,email,'.$employee->user_id,
            'role' => 'required|in:admin,employee,guard',
            'employee_code' => 'required',
            'position' => 'required',
        ]);

        // Update User (Email & Role)
        $employee->user->update([
            'email' => $request->email,
            'role' => $request->role,
        ]);

        // Update Employee (Position, Supervisor, Schedule, Leaves)
        $employee->update([
            'employee_code' => $request->employee_code,
            'position' => $request->position,
            'supervisor_id' => $request->supervisor_id,
            'schedule_id' => $request->schedule_id,
            'vacation_credits' => $request->vacation_credits,
            'sick_credits' => $request->sick_credits,
        ]);

        return redirect()->back()
            ->with('message', 'Employment details updated successfully.')
            ->with('active_tab', 'job'); // Keeps Tab Open
    }

    // --- SHOW ID CARD ---
    public function showIdCard($id) {
        $employee = Employee::with('user')->findOrFail($id);
        return view('employees.id_card', compact('employee'));
    }

    // --- UPDATE PERSONAL INFO (Tab 2) ---
    public function updatePersonal(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        
        // Update User Name
        $employee->user->update([
            'name' => $request->first_name . ' ' . $request->last_name,
        ]);

        // Update 201 File Details
        $employee->update($request->all());

        return redirect()->back()
            ->with('message', 'Personal details updated successfully.')
            ->with('active_tab', 'personal'); // Keeps Tab Open
    }

    // --- UPDATE SALARY (Tab 7) ---
    public function updateSalary(Request $request, $id)
    {
        $request->validate([
            'new_salary' => 'required|numeric',
            'effective_date' => 'required|date',
            'reason' => 'required|string'
        ]);

        $employee = Employee::findOrFail($id);
        $oldSalary = $employee->basic_salary;

        // Record History
        \App\Models\SalaryHistory::create([
            'employee_id' => $employee->id,
            'previous_salary' => $oldSalary,
            'new_salary' => $request->new_salary,
            'effective_date' => $request->effective_date,
            'reason' => $request->reason,
        ]);

        // Update Current Salary
        $employee->update(['basic_salary' => $request->new_salary]);

        return redirect()->back()
            ->with('message', 'Salary updated and history recorded.')
            ->with('active_tab', 'salary'); // Keeps Tab Open
    }
    
    // --- STORE EDUCATION (Tab 3) ---
    public function storeEducation(Request $request, $id)
    {
        $request->validate([
            'school_name' => 'required',
            'level' => 'required',
            'date_graduated' => 'required|date',
            // Changed max:2048 to max:10240 (10MB)
            'diploma' => 'nullable|file|mimes:pdf,jpg,png|max:10240' 
        ]);

        $path = null;
        if($request->hasFile('diploma')){
            $path = $request->file('diploma')->store('diplomas', 'public');
        }

        \App\Models\EmployeeEducation::create([
            'employee_id' => $id,
            'level' => $request->level,
            'school_name' => $request->school_name,
            'date_graduated' => $request->date_graduated,
            'diploma_path' => $path
        ]);

        return redirect()->back()
            ->with('message', 'Education added successfully.')
            ->with('active_tab', 'education'); // Keeps Tab Open
    }

    // --- STORE FAMILY (Tab 5) ---
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
            ->with('active_tab', 'family'); // Keeps Tab Open
    }
    
    // --- STORE TRAINING / LICENSE (Tab 4) ---
    public function storeTraining(Request $request, $id)
    {
         $request->validate([
            'title' => 'required|string',
            'type' => 'required|string',
            'start_date' => 'nullable|date',
            // Changed max:2048 to max:10240 (10MB)
            'certificate' => 'nullable|file|mimes:pdf,jpg,png|max:10240'
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

        return redirect()->back()
            ->with('message', 'Training/License added successfully.')
            ->with('active_tab', 'training'); // Keeps Tab Open
    }

    // --- STORE HEALTH RECORD (Tab 6) ---
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

        return redirect()->back()
            ->with('message', 'Health record added successfully.')
            ->with('active_tab', 'health'); // Keeps Tab Open
    }
    // --- UPDATE MENTAL HEALTH NOTES ---
    public function updateHealthNotes(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        
        // Only update the mental_health column
        $employee->update([
            'mental_health' => $request->mental_health
        ]);

        return redirect()->back()
            ->with('message', 'Mental health notes saved successfully.')
            ->with('active_tab', 'health'); // <--- Keeps you on the Health tab
    }
}