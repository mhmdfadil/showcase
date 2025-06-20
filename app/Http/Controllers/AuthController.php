<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Record login session
            $user->sessions()->create([
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'payload' => ['login_time' => now()],
                'last_activity' => now()->timestamp,
            ]);

            Alert::toast('Login berhasil!', 'success');

            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->isMahasiswa()) {
                return redirect()->route('mahasiswa.dashboard');
            }
        }

        Alert::toast('Email atau password salah!', 'error');
        return back()->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Alert::toast('Anda telah logout.', 'info');
        return redirect('/login');
    }
}
