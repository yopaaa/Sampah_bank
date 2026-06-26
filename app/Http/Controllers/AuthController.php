<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Handle login POST request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $redirect = Auth::user()->role === 'admin' ? '/agen' : '/user';
            return redirect()->intended($redirect);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Handle register POST request (user biasa).
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Generate username dari email (sebelum @) + random 3 digit
        $validated['username'] = explode('@', $validated['email'])[0] . rand(100, 999);
        $validated['role'] = 'user';

        User::create($validated);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat! Silakan login.');
    }

    /**
     * Handle register POST request (agen/admin).
     */
    public function registerAdmin(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Generate username dari email (sebelum @) + random 3 digit
        $validated['username'] = explode('@', $validated['email'])[0] . rand(100, 999);
        $validated['role'] = 'admin';

        User::create($validated);

        return redirect()->route('login')->with('success', 'Akun agen berhasil dibuat! Silakan login.');
    }
}
