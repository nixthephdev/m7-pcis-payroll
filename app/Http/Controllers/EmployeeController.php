<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;
use App\Models\Schedule;
use App\Models\AuditLog;
use App\Models\EmployeeEmploymentHistory;
use App\Models\EmployeeHealthExam;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index(Request $request) {
        $search = $request->get('search');

        $employees = Employee::with('user')
            ->when($search, function($query) use ($search) {
                $query->where('employee_code', 'LIKE', "%$search%")
                      ->orWhere('position', 'LIKE', "%$search%")
                      ->orWhereHas('user', function($q) use ($search) {
                          $q->where('name', 'LIKE', "%$search%")
                            ->orWhere('email', 'LIKE', "%$search%");
                      });
            })
            ->orderBy('employee_code', 'asc')
            ->get();

        return view('employees.index', compact('employees', 'search'));
    }

    public function create() {
        $schedules = Schedule::all();

        $supervisors = Employee::with('user')
            ->where(function($query) {
                $query->where('position', 'LIKE', '%Head%')
                      ->orWhere('position', 'LIKE', '%Coordinator%')
                      ->orWhere('position', 'LIKE', '%Manager%')
                      ->orWhere('position', 'LIKE', '%Principal%')
                      ->orWhere('position', 'LIKE', '%Supervisor%');
            })
            ->get();

        if ($supervisors->isEmpty()) {
            $supervisors = Employee::with('user')->get();
        }

        return view('employees.create', compact('schedules', 'supervisors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users',
            'employee_code' => 'required|string|unique:employees',
            'position'      => 'required|string',
            'role'          => 'required',
            'password'      => 'required',
        ]);

        $user = User::create([
            'name'     => $request->first_name . ' ' . $request->last_name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        $employee = Employee::create([
            'user_id'        => $user->id,
            'employee_code'  => $request->employee_code,
            'first_name'     => $request->first_name,
            'last_name'      => $request->last_name,
            'position'       => $request->position,
            'supervisor_id'  => $request->supervisor_id,
            'schedule_id'    => $request->schedule_id,
            'basic_salary'   => $request->basic_salary ?? 0,
            'vacation_credits'  => $request->vacation_credits ?? 0,
            'sick_credits'      => $request->sick_credits ?? 0,
            'middle_name'    => $request->middle_name,
            'birthdate'      => $request->birthdate,
            'contact_number' => $request->contact_number,
            'address'        => $request->address,
            'tin_no'         => $request->tin_no,
            'sss_no'         => $request->sss_no,
            'philhealth_no'  => $request->philhealth_no,
            'pagibig_no'     => $request->pagibig_no,
            'hobbies'        => $request->hobbies,
        ]);

        return redirect()->route('employees.edit', $employee->id)
            ->with('message', 'Employee record created! You can now add Education, Family, and other details.')
            ->with('active_tab', 'education');
    }

    public function edit($id) {
        $employee = Employee::with([
            'user', 'education', 'family', 'trainings',
            'health', 'salaryHistory', 'employmentHistory', 'healthExams'
        ])->findOrFail($id);

        $supervisors = Employee::with('user')
            ->where('id', '!=', $id)
            ->where(function($query) {
                $query->where('position', 'LIKE', '%Head%')
                      ->orWhere('position', 'LIKE', '%Coordinator%')
                      ->orWhere('position', 'LIKE', '%Principal%')
                      ->orWhere('position', 'LIKE', '%Manager%')
                      ->orWhere('position', 'LIKE', '%Director%')
                      ->orWhere('position', 'LIKE', '%Supervisor%');
            })
            ->orderBy('position', 'asc')
            ->get();

        $schedules = Schedule::all();

        return view('employees.edit', compact('employee', 'supervisors', 'schedules'));
    }

    // --- UPDATE EMPLOYMENT DETAILS (Tab: Employment) ---
    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $request->validate([
            'email'         => 'required|email|unique:users,email,'.$employee->user_id,
            'role'          => 'required|in:admin,employee,guard',
            'employee_code' => 'required',
            'position'      => 'required',
        ]);

        $employee->user->update([
            'email' => $request->email,
            'role'  => $request->role,
        ]);

        $employee->update([
            'employee_code'              => $request->employee_code,
            'position'                   => $request->position,
            'supervisor_id'              => $request->supervisor_id,
            'schedule_id'                => $request->schedule_id,
            'joining_date'               => $request->joining_date,
            'vacation_credits'           => $request->vacation_credits,
            'sick_credits'               => $request->sick_credits,
            'birthday_leave_credits'     => $request->birthday_leave_credits ?? 1,
            'solo_parent_leave_credits'  => $request->solo_parent_leave_credits ?? 0,
            'is_solo_parent'             => $request->has('is_solo_parent') ? 1 : 0,
            'incentive_hours_credits'    => $request->incentive_hours_credits ?? 0,
        ]);

        return redirect()->back()
            ->with('message', 'Employment details updated successfully.')
            ->with('active_tab', 'job');
    }

    public function showIdCard($id) {
        $employee = Employee::with('user')->findOrFail($id);
        return view('employees.id_card', compact('employee'));
    }

    // --- UPDATE PERSONAL INFO (Tab: Personal 201) ---
    public function updatePersonal(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $data = [
            'first_name'              => $request->first_name,
            'last_name'               => $request->last_name,
            'middle_name'             => $request->middle_name,
            'birthdate'               => $request->birthdate,
            'birthplace'              => $request->birthplace,
            'marital_status'          => $request->marital_status,
            'contact_number'          => $request->contact_number,
            'personal_email'          => $request->personal_email,
            'address'                 => $request->address,
            'special_interests'       => $request->special_interests,
            'hobbies'                 => $request->hobbies,
            'tin_no'                  => $request->tin_no,
            'sss_no'                  => $request->sss_no,
            'philhealth_no'           => $request->philhealth_no,
            'pagibig_no'              => $request->pagibig_no,
            'bank_name'                 => $request->bank_name,
            'bank_account_name'         => $request->bank_account_name,
            'bank_account_number'       => $request->bank_account_number,
            'mental_health'             => $request->mental_health,
            'emergency_contact_person'  => $request->emergency_contact_person,
            'emergency_contact_number'  => $request->emergency_contact_number,
        ];

        // File uploads
        $fileFields = [
            'birth_certificate' => 'birth_certificate_path',
            'nbi_clearance'     => 'nbi_clearance_path',
            'tin_proof'         => 'tin_proof_path',
            'sss_proof'         => 'sss_proof_path',
            'philhealth_proof'  => 'philhealth_proof_path',
            'pagibig_proof'     => 'pagibig_proof_path',
            'bank_proof'        => 'bank_proof_path',
        ];

        foreach ($fileFields as $inputName => $column) {
            if ($request->hasFile($inputName)) {
                $data[$column] = $request->file($inputName)->store('employee-docs', 'public');
            }
        }

        $employee->user->update([
            'name' => $request->first_name . ' ' . $request->last_name,
        ]);

        $employee->update($data);

        return redirect()->back()
            ->with('message', 'Personal details updated successfully.')
            ->with('active_tab', 'personal');
    }

    // --- UPLOAD EMPLOYEE PHOTO (HR/Admin only) ---
    public function uploadPhoto(Request $request, $id)
    {
        $request->validate([
            'photo' => 'required|file|mimes:jpg,jpeg,png|max:15360',
        ]);

        $employee = Employee::findOrFail($id);

        $path = $request->file('photo')->store('employee-photos', 'public');

        $employee->update(['photo_path' => $path]);
        $employee->user->update(['avatar' => $path]);

        return redirect()->back()
            ->with('message', 'Employee photo updated successfully.')
            ->with('active_tab', 'personal');
    }

    // --- UPDATE SALARY ---
    public function updateSalary(Request $request, $id)
    {
        $request->validate([
            'new_salary'     => 'required|numeric',
            'effective_date' => 'required|date',
            'reason'         => 'required|string',
        ]);

        $employee = Employee::findOrFail($id);
        $oldSalary = $employee->basic_salary;

        \App\Models\SalaryHistory::create([
            'employee_id'     => $employee->id,
            'previous_salary' => $oldSalary,
            'new_salary'      => $request->new_salary,
            'effective_date'  => $request->effective_date,
            'reason'          => $request->reason,
        ]);

        $employee->update(['basic_salary' => $request->new_salary]);

        return redirect()->back()
            ->with('message', 'Salary updated and history recorded.')
            ->with('active_tab', 'salary');
    }

    // --- STORE EDUCATION ---
    public function storeEducation(Request $request, $id)
    {
        $request->validate([
            'school_name'    => 'required',
            'level'          => 'required',
            'date_graduated' => 'nullable|date',
            'diploma'        => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'tor'            => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $diplomaPath = null;
        if ($request->hasFile('diploma')) {
            $diplomaPath = $request->file('diploma')->store('diplomas', 'public');
        }

        $torPath = null;
        if ($request->hasFile('tor')) {
            $torPath = $request->file('tor')->store('tor-files', 'public');
        }

        \App\Models\EmployeeEducation::create([
            'employee_id'    => $id,
            'level'          => $request->level,
            'school_name'    => $request->school_name,
            'date_graduated' => $request->date_graduated,
            'diploma_path'   => $diplomaPath,
            'tor_path'       => $torPath,
        ]);

        return redirect()->back()
            ->with('message', 'Education record added successfully.')
            ->with('active_tab', 'education');
    }

    // --- STORE EMPLOYMENT HISTORY ---
    public function storeEmploymentHistory(Request $request, $id)
    {
        $request->validate([
            'company_name' => 'required|string',
            'designation'  => 'required|string',
            'from_date'    => 'required|string',
            'coe'          => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $coePath = null;
        if ($request->hasFile('coe')) {
            $coePath = $request->file('coe')->store('coe-files', 'public');
        }

        EmployeeEmploymentHistory::create([
            'employee_id'  => $id,
            'from_date'    => $request->from_date,
            'to_date'      => $request->to_date ?: null,
            'company_name' => $request->company_name,
            'designation'  => $request->designation,
            'coe_path'     => $coePath,
        ]);

        return redirect()->back()
            ->with('message', 'Employment history added successfully.')
            ->with('active_tab', 'education');
    }

    // --- STORE TRAINING / LICENSE ---
    public function storeTraining(Request $request, $id)
    {
        $request->validate([
            'title'       => 'required|string',
            'type'        => 'required|string',
            'start_date'  => 'nullable|date',
            'certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $path = null;
        if ($request->hasFile('certificate')) {
            $path = $request->file('certificate')->store('certificates', 'public');
        }

        \App\Models\EmployeeTraining::create([
            'employee_id'      => $id,
            'title'            => $request->title,
            'type'             => $request->type,
            'license_no'       => $request->license_no,
            'start_date'       => $request->start_date,
            'end_date'         => $request->end_date,
            'expiry_date'      => $request->expiry_date,
            'certificate_path' => $path,
        ]);

        return redirect()->back()
            ->with('message', ($request->type === 'License' ? 'License' : 'Training') . ' added successfully.')
            ->with('active_tab', 'training');
    }

    // --- UPDATE EDUCATION ---
    public function updateEducation(Request $request, \App\Models\EmployeeEducation $edu)
    {
        $request->validate([
            'school_name'    => 'required|string',
            'level'          => 'required|string',
            'date_graduated' => 'nullable|date',
            'diploma'        => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'tor'            => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $data = [
            'level'          => $request->level,
            'school_name'    => $request->school_name,
            'date_graduated' => $request->date_graduated,
        ];

        if ($request->hasFile('diploma')) {
            if ($edu->diploma_path) Storage::disk('public')->delete($edu->diploma_path);
            $data['diploma_path'] = $request->file('diploma')->store('diplomas', 'public');
        }

        if ($request->hasFile('tor')) {
            if ($edu->tor_path) Storage::disk('public')->delete($edu->tor_path);
            $data['tor_path'] = $request->file('tor')->store('tor-files', 'public');
        }

        $edu->update($data);

        return redirect()->back()
            ->with('message', 'Education record updated successfully.')
            ->with('active_tab', 'education');
    }

    // --- UPDATE EMPLOYMENT HISTORY ---
    public function updateEmploymentHistory(Request $request, EmployeeEmploymentHistory $job)
    {
        $request->validate([
            'company_name' => 'required|string',
            'designation'  => 'required|string',
            'from_date'    => 'required|string',
            'coe'          => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $data = [
            'from_date'    => $request->from_date,
            'to_date'      => $request->to_date ?: null,
            'company_name' => $request->company_name,
            'designation'  => $request->designation,
        ];

        if ($request->hasFile('coe')) {
            if ($job->coe_path) Storage::disk('public')->delete($job->coe_path);
            $data['coe_path'] = $request->file('coe')->store('coe-files', 'public');
        }

        $job->update($data);

        return redirect()->back()
            ->with('message', 'Employment history updated successfully.')
            ->with('active_tab', 'education');
    }

    // --- UPDATE TRAINING / LICENSE ---
    public function updateTraining(Request $request, \App\Models\EmployeeTraining $training)
    {
        $request->validate([
            'title'       => 'required|string',
            'start_date'  => 'nullable|date',
            'certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $data = [
            'title'       => $request->title,
            'license_no'  => $request->license_no,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'expiry_date' => $request->expiry_date,
        ];

        if ($request->hasFile('certificate')) {
            if ($training->certificate_path) Storage::disk('public')->delete($training->certificate_path);
            $data['certificate_path'] = $request->file('certificate')->store('certificates', 'public');
        }

        $training->update($data);

        return redirect()->back()
            ->with('message', ($training->type === 'License' ? 'License' : 'Training') . ' updated successfully.')
            ->with('active_tab', 'training');
    }

    // --- DELETE EDUCATION ---
    public function destroyEducation(\App\Models\EmployeeEducation $edu)
    {
        if ($edu->diploma_path) Storage::disk('public')->delete($edu->diploma_path);
        if ($edu->tor_path)     Storage::disk('public')->delete($edu->tor_path);
        $edu->delete();
        return redirect()->back()
            ->with('message', 'Education record deleted.')
            ->with('active_tab', 'education');
    }

    // --- DELETE EMPLOYMENT HISTORY ---
    public function destroyEmploymentHistory(EmployeeEmploymentHistory $job)
    {
        if ($job->coe_path) Storage::disk('public')->delete($job->coe_path);
        $job->delete();
        return redirect()->back()
            ->with('message', 'Employment record deleted.')
            ->with('active_tab', 'education');
    }

    // --- DELETE TRAINING / LICENSE ---
    public function destroyTraining(\App\Models\EmployeeTraining $training)
    {
        if ($training->certificate_path) Storage::disk('public')->delete($training->certificate_path);
        $label = $training->type === 'License' ? 'License' : 'Training';
        $training->delete();
        return redirect()->back()
            ->with('message', $label . ' record deleted.')
            ->with('active_tab', 'training');
    }

    // --- STORE FAMILY MEMBER ---
    public function storeFamily(Request $request, $id)
    {
        $request->validate([
            'name'     => 'required',
            'relation' => 'required',
            'birthdate'  => 'nullable|date',
            'birthplace' => 'nullable|string',
        ]);

        \App\Models\EmployeeFamily::create([
            'employee_id' => $id,
            'name'        => $request->name,
            'relation'    => $request->relation,
            'birthdate'   => $request->birthdate,
            'birthplace'  => $request->birthplace,
            'occupation'  => $request->occupation,
        ]);

        return redirect()->back()
            ->with('message', 'Family member added successfully.')
            ->with('active_tab', 'family');
    }

    // --- STORE HEALTH RECORD ---
    public function storeHealth(Request $request, $id)
    {
        $request->validate([
            'condition'     => 'required|string',
            'date_diagnosed' => 'nullable|date',
        ]);

        \App\Models\EmployeeHealth::create([
            'employee_id'    => $id,
            'condition'      => $request->condition,
            'date_diagnosed' => $request->date_diagnosed,
            'medication'     => $request->medication,
            'dosage'         => $request->dosage,
        ]);

        return redirect()->back()
            ->with('message', 'Health record added successfully.')
            ->with('active_tab', 'health');
    }

    // --- STORE ANNUAL HEALTH EXAM (APE / Drug Test) ---
    public function storeHealthExam(Request $request, $id)
    {
        $request->validate([
            'exam_type' => 'required|in:APE,DrugTest',
            'exam_year' => 'required|digits:4|integer',
            'result'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $resultPath = null;
        if ($request->hasFile('result')) {
            $resultPath = $request->file('result')->store('health-exams', 'public');
        }

        EmployeeHealthExam::create([
            'employee_id'  => $id,
            'exam_type'    => $request->exam_type,
            'exam_year'    => $request->exam_year,
            'result_notes' => $request->result_notes,
            'result_path'  => $resultPath,
        ]);

        return redirect()->back()
            ->with('message', $request->exam_type . ' result for ' . $request->exam_year . ' added successfully.')
            ->with('active_tab', 'health');
    }

    // --- UPDATE MENTAL HEALTH NOTES ---
    public function updateHealthNotes(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $employee->update([
            'mental_health' => $request->mental_health,
        ]);

        return redirect()->back()
            ->with('message', 'Mental health notes saved successfully.')
            ->with('active_tab', 'health');
    }

    // =========================================================
    // EMPLOYEE SELF-SERVICE
    // =========================================================

    public function myProfile()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        if (!$user->employee) {
            return redirect()->route('dashboard')->with('error', 'No employee profile linked to your account.');
        }

        $employee = Employee::with([
            'user', 'education', 'family', 'trainings',
            'health', 'salaryHistory', 'employmentHistory', 'healthExams', 'schedule'
        ])->findOrFail($user->employee->id);

        return view('employees.my_profile', compact('employee'));
    }

    public function updateMyPersonal(Request $request)
    {
        $employee = \Illuminate\Support\Facades\Auth::user()->employee;

        $data = [
            'first_name'        => $request->first_name,
            'last_name'         => $request->last_name,
            'middle_name'       => $request->middle_name,
            'birthdate'         => $request->birthdate,
            'birthplace'        => $request->birthplace,
            'marital_status'    => $request->marital_status,
            'contact_number'    => $request->contact_number,
            'personal_email'    => $request->personal_email,
            'address'           => $request->address,
            'special_interests' => $request->special_interests,
            'hobbies'           => $request->hobbies,
            'tin_no'            => $request->tin_no,
            'sss_no'            => $request->sss_no,
            'philhealth_no'     => $request->philhealth_no,
            'pagibig_no'        => $request->pagibig_no,
            'bank_name'                 => $request->bank_name,
            'bank_account_name'         => $request->bank_account_name,
            'bank_account_number'       => $request->bank_account_number,
            'emergency_contact_person'  => $request->emergency_contact_person,
            'emergency_contact_number'  => $request->emergency_contact_number,
        ];

        $fileFields = [
            'birth_certificate' => 'birth_certificate_path',
            'nbi_clearance'     => 'nbi_clearance_path',
            'tin_proof'         => 'tin_proof_path',
            'sss_proof'         => 'sss_proof_path',
            'philhealth_proof'  => 'philhealth_proof_path',
            'pagibig_proof'     => 'pagibig_proof_path',
            'bank_proof'        => 'bank_proof_path',
        ];

        foreach ($fileFields as $inputName => $column) {
            if ($request->hasFile($inputName)) {
                $data[$column] = $request->file($inputName)->store('employee-docs', 'public');
            }
        }

        $employee->user->update([
            'name' => $request->first_name . ' ' . $request->last_name,
        ]);

        $employee->update($data);

        return redirect()->route('employee.myProfile')
            ->with('message', 'Personal details updated successfully.')
            ->with('active_tab', 'personal');
    }

    public function uploadMyPhoto(Request $request)
    {
        $request->validate(['photo' => 'required|file|mimes:jpg,jpeg,png|max:15360']);

        $user = \Illuminate\Support\Facades\Auth::user();
        $employee = $user->employee;
        $path = $request->file('photo')->store('employee-photos', 'public');
        $employee->update(['photo_path' => $path]);
        $user->update(['avatar' => $path]);

        return redirect()->route('employee.myProfile')
            ->with('message', 'Profile photo updated.')
            ->with('active_tab', 'personal');
    }

    public function storeMyEducation(Request $request)
    {
        $request->validate([
            'school_name'    => 'required',
            'level'          => 'required',
            'date_graduated' => 'nullable|date',
            'diploma'        => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'tor'            => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $employee = \Illuminate\Support\Facades\Auth::user()->employee;

        $diplomaPath = $request->hasFile('diploma') ? $request->file('diploma')->store('diplomas', 'public') : null;
        $torPath     = $request->hasFile('tor')     ? $request->file('tor')->store('tor-files', 'public')   : null;

        \App\Models\EmployeeEducation::create([
            'employee_id'    => $employee->id,
            'level'          => $request->level,
            'school_name'    => $request->school_name,
            'date_graduated' => $request->date_graduated,
            'diploma_path'   => $diplomaPath,
            'tor_path'       => $torPath,
        ]);

        return redirect()->route('employee.myProfile')
            ->with('message', 'Education record added.')
            ->with('active_tab', 'education');
    }

    public function storeMyEmploymentHistory(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string',
            'designation'  => 'required|string',
            'from_date'    => 'required|string',
            'coe'          => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $employee = \Illuminate\Support\Facades\Auth::user()->employee;
        $coePath  = $request->hasFile('coe') ? $request->file('coe')->store('coe-files', 'public') : null;

        EmployeeEmploymentHistory::create([
            'employee_id'  => $employee->id,
            'from_date'    => $request->from_date,
            'to_date'      => $request->to_date ?: null,
            'company_name' => $request->company_name,
            'designation'  => $request->designation,
            'coe_path'     => $coePath,
        ]);

        return redirect()->route('employee.myProfile')
            ->with('message', 'Employment history added.')
            ->with('active_tab', 'education');
    }

    public function storeMyTraining(Request $request)
    {
        $request->validate([
            'title'       => 'required|string',
            'type'        => 'required|string',
            'start_date'  => 'nullable|date',
            'certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $employee = \Illuminate\Support\Facades\Auth::user()->employee;
        $path     = $request->hasFile('certificate') ? $request->file('certificate')->store('certificates', 'public') : null;

        \App\Models\EmployeeTraining::create([
            'employee_id'      => $employee->id,
            'title'            => $request->title,
            'type'             => $request->type,
            'license_no'       => $request->license_no,
            'start_date'       => $request->start_date,
            'end_date'         => $request->end_date,
            'expiry_date'      => $request->expiry_date,
            'certificate_path' => $path,
        ]);

        return redirect()->route('employee.myProfile')
            ->with('message', ($request->type === 'License' ? 'License' : 'Training') . ' added.')
            ->with('active_tab', 'training');
    }

    public function updateMyEducation(Request $request, \App\Models\EmployeeEducation $edu)
    {
        $employee = \Illuminate\Support\Facades\Auth::user()->employee;
        abort_if($edu->employee_id !== $employee->id, 403);

        $request->validate([
            'school_name'    => 'required',
            'level'          => 'required',
            'date_graduated' => 'nullable|date',
            'diploma'        => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'tor'            => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($request->hasFile('diploma')) {
            if ($edu->diploma_path) Storage::disk('public')->delete($edu->diploma_path);
            $edu->diploma_path = $request->file('diploma')->store('diplomas', 'public');
        }
        if ($request->hasFile('tor')) {
            if ($edu->tor_path) Storage::disk('public')->delete($edu->tor_path);
            $edu->tor_path = $request->file('tor')->store('tor-files', 'public');
        }
        $edu->level          = $request->level;
        $edu->school_name    = $request->school_name;
        $edu->date_graduated = $request->date_graduated;
        $edu->save();

        return redirect()->route('employee.myProfile')
            ->with('message', 'Education record updated.')
            ->with('active_tab', 'education');
    }

    public function destroyMyEducation(\App\Models\EmployeeEducation $edu)
    {
        $employee = \Illuminate\Support\Facades\Auth::user()->employee;
        abort_if($edu->employee_id !== $employee->id, 403);

        if ($edu->diploma_path) Storage::disk('public')->delete($edu->diploma_path);
        if ($edu->tor_path) Storage::disk('public')->delete($edu->tor_path);
        $edu->delete();

        return redirect()->route('employee.myProfile')
            ->with('message', 'Education record deleted.')
            ->with('active_tab', 'education');
    }

    public function updateMyEmploymentHistory(Request $request, EmployeeEmploymentHistory $job)
    {
        $employee = \Illuminate\Support\Facades\Auth::user()->employee;
        abort_if($job->employee_id !== $employee->id, 403);

        $request->validate([
            'company_name' => 'required|string',
            'designation'  => 'required|string',
            'from_date'    => 'required|string',
            'coe'          => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($request->hasFile('coe')) {
            if ($job->coe_path) Storage::disk('public')->delete($job->coe_path);
            $job->coe_path = $request->file('coe')->store('coe-files', 'public');
        }
        $job->from_date    = $request->from_date;
        $job->to_date      = $request->to_date ?: null;
        $job->company_name = $request->company_name;
        $job->designation  = $request->designation;
        $job->save();

        return redirect()->route('employee.myProfile')
            ->with('message', 'Employment history updated.')
            ->with('active_tab', 'education');
    }

    public function destroyMyEmploymentHistory(EmployeeEmploymentHistory $job)
    {
        $employee = \Illuminate\Support\Facades\Auth::user()->employee;
        abort_if($job->employee_id !== $employee->id, 403);

        if ($job->coe_path) Storage::disk('public')->delete($job->coe_path);
        $job->delete();

        return redirect()->route('employee.myProfile')
            ->with('message', 'Employment record deleted.')
            ->with('active_tab', 'education');
    }

    public function updateMyTraining(Request $request, \App\Models\EmployeeTraining $training)
    {
        $employee = \Illuminate\Support\Facades\Auth::user()->employee;
        abort_if($training->employee_id !== $employee->id, 403);

        $request->validate([
            'title'       => 'required|string',
            'start_date'  => 'nullable|date',
            'certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($request->hasFile('certificate')) {
            if ($training->certificate_path) Storage::disk('public')->delete($training->certificate_path);
            $training->certificate_path = $request->file('certificate')->store('certificates', 'public');
        }
        $training->title       = $request->title;
        $training->license_no  = $request->license_no;
        $training->start_date  = $request->start_date;
        $training->end_date    = $request->end_date;
        $training->expiry_date = $request->expiry_date;
        $training->save();

        return redirect()->route('employee.myProfile')
            ->with('message', ($training->type === 'License' ? 'License' : 'Training') . ' updated.')
            ->with('active_tab', 'training');
    }

    public function destroyMyTraining(\App\Models\EmployeeTraining $training)
    {
        $employee = \Illuminate\Support\Facades\Auth::user()->employee;
        abort_if($training->employee_id !== $employee->id, 403);

        if ($training->certificate_path) Storage::disk('public')->delete($training->certificate_path);
        $label = $training->type === 'License' ? 'License' : 'Training';
        $training->delete();

        return redirect()->route('employee.myProfile')
            ->with('message', $label . ' record deleted.')
            ->with('active_tab', 'training');
    }

    public function storeMyFamily(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'relation' => 'required',
        ]);

        $employee = \Illuminate\Support\Facades\Auth::user()->employee;

        \App\Models\EmployeeFamily::create([
            'employee_id' => $employee->id,
            'name'        => $request->name,
            'relation'    => $request->relation,
            'birthdate'   => $request->birthdate,
            'birthplace'  => $request->birthplace,
            'occupation'  => $request->occupation,
        ]);

        return redirect()->route('employee.myProfile')
            ->with('message', 'Family member added.')
            ->with('active_tab', 'family');
    }

    public function storeMyHealth(Request $request)
    {
        $request->validate(['condition' => 'required|string']);

        $employee = \Illuminate\Support\Facades\Auth::user()->employee;

        \App\Models\EmployeeHealth::create([
            'employee_id'    => $employee->id,
            'condition'      => $request->condition,
            'date_diagnosed' => $request->date_diagnosed,
            'medication'     => $request->medication,
            'dosage'         => $request->dosage,
        ]);

        return redirect()->route('employee.myProfile')
            ->with('message', 'Health record added.')
            ->with('active_tab', 'health');
    }

    public function storeMyHealthExam(Request $request)
    {
        $request->validate([
            'exam_type' => 'required|in:APE,DrugTest',
            'exam_year' => 'required|digits:4|integer',
            'result'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $employee   = \Illuminate\Support\Facades\Auth::user()->employee;
        $resultPath = $request->hasFile('result') ? $request->file('result')->store('health-exams', 'public') : null;

        EmployeeHealthExam::create([
            'employee_id'  => $employee->id,
            'exam_type'    => $request->exam_type,
            'exam_year'    => $request->exam_year,
            'result_notes' => $request->result_notes,
            'result_path'  => $resultPath,
        ]);

        return redirect()->route('employee.myProfile')
            ->with('message', $request->exam_type . ' result for ' . $request->exam_year . ' added.')
            ->with('active_tab', 'health');
    }

    public function updateMyHealthNotes(Request $request)
    {
        $employee = \Illuminate\Support\Facades\Auth::user()->employee;
        $employee->update(['mental_health' => $request->mental_health]);

        return redirect()->route('employee.myProfile')
            ->with('message', 'Mental health notes saved.')
            ->with('active_tab', 'health');
    }
}
