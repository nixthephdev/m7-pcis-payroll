<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditController extends Controller
{
    public function index() {
        // Double Security: Ensure only Admin can see this
        if (Auth::user()->role !== 'admin') {
            abort(404); // Fake a 404 Not Found to hide it
        }

        $logs = AuditLog::with('user')->orderBy('created_at', 'desc')->paginate(50);
        return view('audit.index', compact('logs'));
    }
}