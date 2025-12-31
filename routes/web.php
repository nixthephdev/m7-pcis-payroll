<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\EmployeeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Landing Page
Route::get('/', function () {
    return view('welcome');
});

// 2. Dashboard (Accessible by all logged-in users)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 3. GENERAL ROUTES (For ALL Logged-in Users: Employees & Admin)
Route::middleware('auth')->group(function () {
    
    // Profile Management (Default Laravel Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Attendance (Clock In/Out)
    Route::post('/clock-in', [AttendanceController::class, 'clockIn'])->name('clock.in');
    Route::post('/clock-out', [AttendanceController::class, 'clockOut'])->name('clock.out');

    // Leave Filing (Employees filing requests)
    Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves.index');
    Route::post('/leaves', [LeaveController::class, 'store'])->name('leave.store');

    // Download Payslip PDF
    Route::get('/payroll/{id}/download', [PayrollController::class, 'downloadPdf'])->name('payroll.download');
});

// 4. ADMIN ROUTES (Only for Users with role = 'admin')
Route::middleware(['auth', 'admin'])->group(function () {

    // Employee Management (Add/View Employees)
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');

    // Leave Management (Approvals)
    Route::get('/leaves/manage', [LeaveController::class, 'manage'])->name('leaves.manage');
    Route::post('/leaves/{id}/update', [LeaveController::class, 'updateStatus'])->name('leave.update');

    // Payroll Generation (Calculate Salaries)
    Route::post('/generate-payroll', [PayrollController::class, 'generatePayroll'])->name('payroll.generate');

    // Attendance Monitoring
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/{id}/edit', [AttendanceController::class, 'edit'])->name('attendance.edit');
    Route::put('/attendance/{id}', [AttendanceController::class, 'update'])->name('attendance.update');
});

require __DIR__.'/auth.php';