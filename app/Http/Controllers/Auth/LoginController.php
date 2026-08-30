<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle authentication attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $login = $credentials['email'];
        $password = $credentials['password'];
        $remember = $request->filled('remember-me');

        // Determine if logging in using email or name/username
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        if (Auth::attempt([$field => $login, 'password' => $password], $remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Username/Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
