<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    // THIS IS THE LINE YOU ARE MISSING
    protected $fillable = [
        'name', 
        'time_in', 
        'time_out', 
        'is_flexible'
    ];
}