<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminQuoteController extends Controller
{
    public function index(Request $request): View
    {
        $quotes = Quote::where('tradition_id', $request->user()->tradition_id)->latest()->paginate(15);

        return view('admin.quotes.index', compact('quotes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['body' => ['required', 'string'], 'source' => ['required', 'string', 'max:255'], 'scheduled_for' => ['nullable', 'date'], 'is_active' => ['boolean']]);
        $request->user()->tradition->quotes()->create($data + ['created_by' => $request->user()->id]);

        return back()->with('status', 'Kutipan dibuat.');
    }

    public function togglePublish(Request $request, Quote $quote): RedirectResponse
    {
        abort_unless($quote->tradition_id === $request->user()->tradition_id, 403);
        $quote->update(['is_active' => ! $quote->is_active]);

        return back()->with('status', $quote->is_active ? 'Kutipan diterbitkan.' : 'Kutipan disembunyikan.');
    }

    public function update(Request $request, Quote $quote): RedirectResponse
    {
        abort_unless($quote->tradition_id === $request->user()->tradition_id, 403);
        $data = $request->validate([
            'body' => ['required', 'string'],
            'source' => ['required', 'string', 'max:255'],
            'scheduled_for' => ['nullable', 'date'],
            'is_active' => ['boolean'],
        ]);
        $quote->update($data);

        return back()->with('status', 'Kutipan diperbarui.');
    }

    public function destroy(Request $request, Quote $quote): RedirectResponse
    {
        abort_unless($quote->tradition_id === $request->user()->tradition_id, 403);
        $quote->delete();

        return back()->with('status', 'Kutipan dihapus.');
    }
}
