<?php

namespace App\Http\Controllers;

use App\Models\JournalEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JournalController extends Controller
{
    public function index(Request $request): View
    {
        return view('journal.index', ['entries' => $request->user()->journalEntries()->latest('entry_date')->paginate(12)]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['entry_date' => 'required|date', 'title' => 'required|string|max:255', 'body' => 'required|string']);
        $request->user()->journalEntries()->create($data);

        return back()->with('status', 'Catatan disimpan.');
    }

    public function update(Request $request, JournalEntry $entry): RedirectResponse
    {
        abort_unless($entry->user_id === $request->user()->id, 403);
        $entry->update($request->validate(['entry_date' => 'required|date', 'title' => 'required|string|max:255', 'body' => 'required|string']));

        return back()->with('status', 'Catatan diperbarui.');
    }

    public function destroy(Request $request, JournalEntry $entry): RedirectResponse
    {
        abort_unless($entry->user_id === $request->user()->id, 403);
        $entry->delete();

        return back()->with('status', 'Catatan dihapus.');
    }
}
