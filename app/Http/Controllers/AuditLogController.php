<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(): View
    {
        return view('superadmin.audit-logs.index', [
            'logs' => AuditLog::with('actor')->latest()->paginate(30),
        ]);
    }
}
