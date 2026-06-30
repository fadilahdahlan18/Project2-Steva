<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:50|regex:/^\S+$/|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'no_hp' => 'nullable|string|max:20',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
            'password' => 'nullable|string|min:8|confirmed',
        ];

        $request->validate($rules, [
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'username.regex' => 'Username tidak boleh mengandung spasi.',
        ]);

        $user->nama = $request->nama;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->no_hp = $request->no_hp;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('foto')) {
            if ($user->foto && !str_starts_with($user->foto, 'data:')) {
                Storage::delete('public/' . $user->foto);
            }
            $file = $request->file('foto');
            $user->foto = 'data:' . $file->getMimeType() . ';base64,' . base64_encode(file_get_contents($file->getRealPath()));
        }

        $user->save();

        // Redirect based on role
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Profil berhasil diperbarui.');
        } elseif ($user->role === 'pelatih') {
            return redirect()->route('pelatih.dashboard')->with('success', 'Profil berhasil diperbarui.');
        } else {
            return redirect()->route('murid.dashboard')->with('success', 'Profil berhasil diperbarui.');
        }
    }
}
