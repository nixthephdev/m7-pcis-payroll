<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', now()->year);

        $holidays = Holiday::query()
            ->where(function ($q) use ($year) {
                $q->where('is_recurring', true)
                  ->orWhere(function ($q2) use ($year) {
                      $q2->where('is_recurring', false)
                         ->whereYear('date', $year);
                  });
            })
            ->orderByRaw("MONTH(date), DAY(date)")
            ->get();

        $years = Holiday::where('is_recurring', false)
            ->selectRaw('YEAR(date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('holidays.index', compact('holidays', 'year', 'years'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'date'         => 'required|date',
            'type'         => 'required|in:regular,special',
            'description'  => 'nullable|string',
            'is_recurring' => 'boolean',
        ]);

        Holiday::create([
            'name'         => $request->name,
            'date'         => $request->date,
            'type'         => $request->type,
            'description'  => $request->description,
            'is_recurring' => $request->boolean('is_recurring'),
        ]);

        return redirect()->back()->with('message', 'Holiday "' . $request->name . '" has been added.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'date'         => 'required|date',
            'type'         => 'required|in:regular,special',
            'description'  => 'nullable|string',
            'is_recurring' => 'boolean',
        ]);

        $holiday = Holiday::findOrFail($id);
        $holiday->update([
            'name'         => $request->name,
            'date'         => $request->date,
            'type'         => $request->type,
            'description'  => $request->description,
            'is_recurring' => $request->boolean('is_recurring'),
        ]);

        return redirect()->back()->with('message', 'Holiday "' . $request->name . '" has been updated.');
    }

    public function destroy($id)
    {
        $holiday = Holiday::findOrFail($id);
        $name = $holiday->name;
        $holiday->delete();

        return redirect()->back()->with('message', '"' . $name . '" has been removed from holidays.');
    }
}
