<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended($this->redirectPathFor(Auth::user()));
        }

        $user = \App\Models\User::where('email', $request->input('email'))->first();

        if ($user && (bool) ($user->must_change_password ?? false)) {
            $enteredPassword = (string) $request->input('password');
            $normalizedPassword = Str::title(Str::lower(trim($enteredPassword)));

            if ($normalizedPassword !== '' && Hash::check($normalizedPassword, $user->password)) {
                Auth::login($user, $remember);
                $request->session()->regenerate();

                return redirect()->intended($this->redirectPathFor($user));
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    protected function redirectPathFor($user): string
    {
        if (method_exists($user, 'hasRole')) {
            if ($user->hasRole('participant')) {
                return route('participant.dashboard');
            }

            if ($user->hasAnyRole([
                'super-admin',
                'programme-coordinator',
                'attendance-officer',
                'm&e-officer',
            ])) {
                return route('admin.dashboard');
            }
        }

        return '/';
    }
}
