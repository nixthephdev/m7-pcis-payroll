<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'date', 'type', 'description', 'is_recurring'];

    protected $casts = [
        'date' => 'date',
        'is_recurring' => 'boolean',
    ];

    public static function isHoliday(Carbon $date): bool
    {
        $exactMatch = static::where('is_recurring', false)
            ->whereDate('date', $date->toDateString())
            ->exists();

        if ($exactMatch) {
            return true;
        }

        return static::where('is_recurring', true)
            ->whereMonth('date', $date->month)
            ->whereDay('date', $date->day)
            ->exists();
    }
}
