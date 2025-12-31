<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\SalaryItem;

class SalaryItemController extends Controller
{
    // 1. Show the Salary Management Page for a specific employee
    public function edit($employeeId) {
        $employee = Employee::with('user', 'salaryItems')->findOrFail($employeeId);
        return view('salary.edit', compact('employee'));
    }

    // 2. Store a new Item (Allowance or Deduction)
    public function store(Request $request, $employeeId) {
        $request->validate([
            'name' => 'required|string',
            'amount' => 'required|numeric',
            'type' => 'required|in:earning,deduction'
        ]);

        SalaryItem::create([
            'employee_id' => $employeeId,
            'name' => $request->name,
            'amount' => $request->amount,
            'type' => $request->type
        ]);

        return redirect()->back()->with('message', 'Item added successfully!');
    }

    // 3. Delete an Item
    public function destroy($id) {
        $item = SalaryItem::findOrFail($id);
        $item->delete();
        
        return redirect()->back()->with('message', 'Item removed.');
    }
}