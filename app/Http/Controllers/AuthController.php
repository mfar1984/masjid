<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // First, check if user exists and credentials are correct
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Priority 1: Check if user's role is active (higher priority than email verification)
            if (!$user->hasActiveRole()) {
                // Logout the user immediately
                Auth::logout();

                // Return with role inactive error - different error key for different modal
                return back()->withErrors([
                    'role_inactive' => 'Kumpulan pengguna anda telah dinyahaktifkan. Sila hubungi pentadbir untuk maklumat lanjut.',
                ])->onlyInput('email');
            }

            // Priority 2: Check if email is verified (only if role is active)
            if (!$user->hasVerifiedEmail()) {
                // Logout the user immediately
                Auth::logout();

                // Return with verification error
                return back()->withErrors([
                    'verification' => 'Akaun anda belum disahkan. Sila hubungi pentadbir untuk pengesahan akaun.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            return redirect()->intended('/overview');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
