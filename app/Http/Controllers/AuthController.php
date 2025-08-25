<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // pastikan sudah ada

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required'],
            'password' => ['required']
        ]);

        // cek apakah username ada di database
        $user = User::where('username', $request->username)->first();

        if (!$user && !Auth::validate(['username' => $request->username, 'password' => $request->password])) {
            // username dan password salah
            return back()->withErrors([
                'username' => 'Username dan Password salah.',
            ])->withInput();
        }

        if (!$user) {
            // username salah
            return back()->withErrors([
                'username' => 'Username salah.',
            ])->withInput();
        }

        if (!Auth::validate(['username' => $request->username, 'password' => $request->password])) {
            // password salah
            return back()->withErrors([
                'password' => 'Password salah.',
            ])->withInput();
        }

        // login berhasil
        Auth::login($user, $request->remember);
        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
