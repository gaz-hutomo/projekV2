<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Form register
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Proses register
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name'          => $data['name'],
            'email'         => $data['email'],
            'password_hash' => Hash::make($data['password']),
        ]);

        // auto-login setelah register
        Auth::login($user);

        return redirect()->route('shop.index')
            ->with('success', 'Registrasi berhasil, kamu sudah login.');
    }

    // Form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Perhatikan: pakai key 'password', bukan 'password_hash'
        if (Auth::attempt([
            'email'    => $credentials['email'],
            'password' => $credentials['password'],
        ], $request->boolean('remember'))) {

            $request->session()->regenerate();

            return redirect()->intended(route('shop.index'))
                ->with('success', 'Berhasil login.');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('shop.index')
            ->with('success', 'Berhasil logout.');
    }
}
