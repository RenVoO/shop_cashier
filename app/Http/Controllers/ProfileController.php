<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request){
        return view('user.profile', [
            'user' => $request->user()
        ]);
    }

    public function edit(Request $request)
    {
        return view('user.profile-edit', [
            'user' => $request->user()
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama' => ['required', 'max:100'],
            'username' => ['required', 'unique:users,username,' . $request->user()->id],
            'password_baru' => ['nullable', 'max:100', 'confirmed']
        ]);

        if ($request->password_baru) {
            $request->merge([
                'password' => bcrypt($request->password_baru),
            ]);
            $request->user()->update($request->all());
            return redirect()->route('profile.show')->with('update', 'success');
        } else {
            $request->user()->update($request->only('nama', 'username'));
            return redirect()->route('profile.show')->with('update', 'success');
        }
    }

    
}
