<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\VerificationRequest;
use App\Services\VerificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VerificationController extends Controller
{
    public function __construct(private readonly VerificationService $service) {}

    public function index(Request $request): View
    {
        $query = VerificationRequest::query()->with(['user', 'tradition'])->latest();
        if ($request->user()->role === Role::ReligionAdmin) {
            $query->where('tradition_id', $request->user()->tradition_id);
        }

        return view('verification.index', ['verifications' => $query->paginate(15)]);
    }

    public function proof(Request $request, VerificationRequest $verification): mixed
    {
        return $this->service->streamProof($verification, $request->user());
    }

    public function claim(Request $request, VerificationRequest $verification): RedirectResponse
    {
        $this->service->claim($verification, $request->user());

        return back()->with('status', 'Request berhasil diklaim.');
    }

    public function approve(Request $request, VerificationRequest $verification): RedirectResponse
    {
        $this->service->approve($verification, $request->user());

        return back()->with('status', 'User disetujui.');
    }

    public function reject(Request $request, VerificationRequest $verification): RedirectResponse
    {
        $validated = $request->validate(['reason' => ['required', 'string', 'min:10', 'max:1000']]);
        $this->service->reject($verification, $request->user(), $validated['reason']);

        return back()->with('status', 'Request ditolak.');
    }
}
