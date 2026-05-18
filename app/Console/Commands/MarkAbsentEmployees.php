<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MarkAbsentEmployees extends Command
{
    protected $signature = 'attendance:mark-absent
                            {--date=    : Single date to check (YYYY-MM-DD). Defaults to yesterday.}
                            {--from=    : Start date for range backfill (YYYY-MM-DD).}
                            {--to=      : End date for range backfill (YYYY-MM-DD). Defaults to yesterday.}
                            {--force    : Also run on weekends}';

    protected $description = 'Auto-mark employees absent if they have no attendance record and no approved leave for a given workday.';

    public function handle(): int
    {
        // Build the list of dates to process
        if ($this->option('from')) {
            $start = Carbon::parse($this->option('from'));
            $end   = $this->option('to') ? Carbon::parse($this->option('to')) : Carbon::yesterday();
            $dates = [];
            for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
                $dates[] = $d->copy();
            }
        } else {
            $dates = [
                $this->option('date') ? Carbon::parse($this->option('date')) : Carbon::yesterday()
            ];
        }

        // Only employees with a fixed (non-flexible) schedule assigned
        $employees = Employee::with('schedule')->whereHas('schedule', function ($q) {
            $q->where('is_flexible', false);
        })->get();

        if ($employees->isEmpty()) {
            $this->warn('No employees with a fixed schedule found.');
            return 0;
        }

        $totalMarked  = 0;
        $totalSkipped = 0;

        foreach ($dates as $date) {
            if ($date->isWeekend() && !$this->option('force')) {
                continue;
            }

            $marked  = 0;
            $skipped = 0;

            foreach ($employees as $employee) {
                $hasAttendance = Attendance::where('attendable_id', $employee->id)
                    ->where('attendable_type', Employee::class)
                    ->whereDate('date', $date->toDateString())
                    ->exists();

                if ($hasAttendance) {
                    $skipped++;
                    continue;
                }

                $onLeave = LeaveRequest::where('employee_id', $employee->id)
                    ->where('status', 'Approved')
                    ->whereDate('start_date', '<=', $date->toDateString())
                    ->whereDate('end_date', '>=', $date->toDateString())
                    ->exists();

                if ($onLeave) {
                    $skipped++;
                    continue;
                }

                Attendance::create([
                    'attendable_id'     => $employee->id,
                    'attendable_type'   => Employee::class,
                    'date'              => $date->toDateString(),
                    'time_in'           => null,
                    'time_out'          => null,
                    'status'            => 'Absent',
                    'tardy_minutes'     => 0,
                    'undertime_minutes' => 0,
                    'overtime_minutes'  => 0,
                    'overtime_type'     => null,
                ]);

                $marked++;
            }

            if ($marked > 0) {
                $this->line("{$date->toDateString()} — {$marked} marked absent, {$skipped} skipped.");
            }

            $totalMarked  += $marked;
            $totalSkipped += $skipped;
        }

        $this->info("Done — {$totalMarked} total absent records created, {$totalSkipped} skipped.");
        return 0;
    }
}
