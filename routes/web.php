<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SalaryItemController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Landing Page
Route::get('/', function () {
    return view('welcome');
});

// --- KIOSK MODE (Scanner) - Public Access ---
Route::get('/scan', function () {
    return view('attendance.scan');
})->name('attendance.scanPage');

Route::post('/scan/process', [AttendanceController::class, 'scan'])->name('attendance.scan');


// 2. Dashboard (Uses DashboardController for logic)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// 3. GENERAL ROUTES (For ALL Logged-in Users)
Route::middleware('auth')->group(function () {
    
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Attendance (Manual Clock In/Out Buttons)
    Route::post('/clock-in', [AttendanceController::class, 'clockIn'])->name('clock.in');
    Route::post('/clock-out', [AttendanceController::class, 'clockOut'])->name('clock.out');

    // Leave Filing
    Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves.index');
    Route::post('/leaves', [LeaveController::class, 'store'])->name('leave.store');

    // Download Payslip PDF
    Route::get('/payroll/{id}/download', [PayrollController::class, 'downloadPdf'])->name('payroll.download');
});

// 4. ADMIN ROUTES (Only for Users with role = 'admin')
Route::middleware(['auth', 'admin'])->group(function () {

    // Employee Management
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('/employees/{id}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/{id}', [EmployeeController::class, 'update'])->name('employees.update');
    
    // Digital ID Card
    Route::get('/employees/{id}/id-card', [EmployeeController::class, 'showIdCard'])->name('employees.idcard');

    // Leave Management (Approvals)
    Route::get('/leaves/manage', [LeaveController::class, 'manage'])->name('leaves.manage');
    Route::post('/leaves/{id}/update', [LeaveController::class, 'updateStatus'])->name('leave.update');

    // Attendance Monitoring (Admin View)
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/{id}/edit', [AttendanceController::class, 'edit'])->name('attendance.edit');
    Route::put('/attendance/{id}', [AttendanceController::class, 'update'])->name('attendance.update');

    // Salary Management (Allowances & Deductions)
    Route::get('/employees/{id}/salary', [SalaryItemController::class, 'edit'])->name('salary.edit');
    Route::post('/employees/{id}/salary', [SalaryItemController::class, 'store'])->name('salary.store');
    Route::delete('/salary/{id}', [SalaryItemController::class, 'destroy'])->name('salary.destroy');

    // Payroll Generation
    Route::post('/employees/{id}/generate', [PayrollController::class, 'generateForEmployee'])->name('payroll.create');
    Route::post('/payroll/generate-all', [PayrollController::class, 'generateAll'])->name('payroll.generateAll');
    
    // Payroll History & Actions
    Route::get('/payroll/history', [PayrollController::class, 'history'])->name('payroll.history');
    Route::post('/payroll/pay-all', [PayrollController::class, 'markAllAsPaid'])->name('payroll.payAll');
    Route::post('/payroll/{id}/pay', [PayrollController::class, 'markAsPaid'])->name('payroll.paid');
    Route::delete('/payroll/{id}', [PayrollController::class, 'destroy'])->name('payroll.destroy');
});

require __DIR__.'/auth.php';