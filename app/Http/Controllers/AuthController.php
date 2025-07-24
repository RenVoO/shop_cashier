<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $cred = $request->validate([
            'username' => ['required', 'exists:users'],
            'password' => ['required']
        ]);

        if (Auth::attempt($cred, $request->remember)) {
            return redirect('/');
        }

        return back()->withErrors([
            'username' => 'Username atau Password yang diberikan salah.',
        ])->onlyInput('username');
    }   

    public function logout(Request $request) 
    {   
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
