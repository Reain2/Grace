<?php

namespace App\Http\Controllers;

use App\Services\VerificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VerificationStatusController extends Controller
{
    public function __construct(private readonly VerificationService $service) {}

    public function resubmit(Request $request): RedirectResponse
    {
        $validated = $request->validate(['proof_file' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png', 'max:4096']]);
        $this->service->resubmit($request->user(), $validated['proof_file']);

        return back()->with('status', 'Bukti dikirim ulang untuk ditinjau.');
    }

    public function __invoke(Request $request): View
    {
        return view('verification.status', [
            'verification' => $request->user()->verificationRequests()->latest()->first(),
        ]);
    }
}
