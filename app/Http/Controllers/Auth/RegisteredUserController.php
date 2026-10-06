<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Role;
use App\Enums\UserStatus;
use App\Enums\VerificationStatus;
use App\Http\Controllers\Controller;
use App\Models\Tradition;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register', ['traditions' => Tradition::query()->where('is_active', true)->orderBy('name')->get()]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'tradition_id' => ['required', 'integer', 'exists:traditions,id'],
            'proof_file' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png', 'max:4096'],
            'consent' => ['accepted'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = DB::transaction(function () use ($validated) {
            $path = Storage::disk('local')->putFile('verification-proofs', $validated['proof_file']);
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => Role::User,
                'tradition_id' => $validated['tradition_id'],
                'status' => UserStatus::Active,
                'verification_status' => VerificationStatus::Pending,
                'timezone' => 'Asia/Jakarta',
                'consent_at' => now(),
            ]);
            $user->verificationRequests()->create([
                'tradition_id' => $validated['tradition_id'],
                'proof_type' => 'dummy_image',
                'proof_path' => $path,
                'status' => VerificationStatus::Pending,
            ]);

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
