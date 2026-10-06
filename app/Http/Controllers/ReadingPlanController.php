<?php

namespace App\Http\Controllers;

use App\Models\ReadingPlan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReadingPlanController extends Controller
{
    public function index(Request $request): View
    {
        $plans = ReadingPlan::with('items')->where('tradition_id', $request->user()->tradition_id)->where('is_active', true)->latest()->paginate(10);

        return view('reading-plans.index', compact('plans'));
    }
}
