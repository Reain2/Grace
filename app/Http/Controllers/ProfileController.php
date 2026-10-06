<?php

namespace App\Http\Controllers;

use App\Enums\VerificationStatus;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Tradition;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
            'traditions' => Tradition::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function changeTradition(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tradition_id' => ['required', 'integer', 'exists:traditions,id'],
            'proof_file' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png', 'max:4096'],
        ]);
        $user = $request->user();

        abort_if((int) $user->tradition_id === (int) $validated['tradition_id'], 422, 'Pilih tradisi yang berbeda.');

        DB::transaction(function () use ($user, $validated): void {
            $path = Storage::disk('local')->putFile('verification-proofs', $validated['proof_file']);
            $user->update([
                'tradition_id' => $validated['tradition_id'],
                'verification_status' => VerificationStatus::Pending,
            ]);
            $user->verificationRequests()->create([
                'tradition_id' => $validated['tradition_id'],
                'proof_type' => 'dummy_image',
                'proof_path' => $path,
                'status' => VerificationStatus::Pending,
            ]);
        });

        return Redirect::route('profile.edit')->with('status', 'tradition-change-submitted');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
