<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuoteController extends Controller
{
    public function index(Request $request): View
    {
        $quotes = Quote::where('tradition_id', $request->user()->tradition_id)->where('is_active', true)->latest()->paginate(10);

        return view('quotes.index', compact('quotes'));
    }

    public function bookmarks(Request $request): View
    {
        $quotes = $request->user()->belongsToMany(Quote::class, 'quote_bookmarks')->where('tradition_id', $request->user()->tradition_id)->latest()->paginate(10);

        return view('quotes.bookmarks', compact('quotes'));
    }

    public function bookmark(Request $request, Quote $quote): RedirectResponse
    {
        abort_unless($quote->tradition_id === $request->user()->tradition_id, 403);
        $quote->bookmarks()->toggle([$request->user()->id]);

        return back();
    }
}
