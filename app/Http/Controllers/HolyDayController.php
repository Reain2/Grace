<?php

namespace App\Http\Controllers;

use App\Models\HolyDay;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HolyDayController extends Controller
{
    public function index(Request $request): View
    {
        $days = HolyDay::where('tradition_id', $request->user()->tradition_id)->where(fn ($query) => $query->where('date', '>=', today())->orWhere('is_annual', true))->orderByRaw('MONTH(date), DAY(date)')->paginate(20);

        return view('holy-days.index', compact('days'));
    }
}
