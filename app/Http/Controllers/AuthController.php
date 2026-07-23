<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'email' => 'Email atau kata sandi yang Anda masukkan salah.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended($this->homeRoute());
    }

    public function showRegisterForm(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'     => ['required', 'string', 'min:8', 'confirmed'],
            'role'         => ['required', 'in:mahasiswa,mentor'],
            'university'   => ['required_if:role,mahasiswa', 'nullable', 'string', 'max:255'],
            'major'        => ['required_if:role,mahasiswa', 'nullable', 'string', 'max:255'],
            'degree_level' => ['required_if:role,mahasiswa', 'nullable', 'in:D3,D4,S1,S2,S3'],
        ], [
            'university.required_if'   => 'Kampus wajib diisi untuk akun mahasiswa.',
            'major.required_if'        => 'Jurusan wajib diisi untuk akun mahasiswa.',
            'degree_level.required_if' => 'Jenjang studi wajib dipilih untuk akun mahasiswa.',
        ]);

        $user = User::create([
            'name'         => $validated['name'],
            'email'        => $validated['email'],
            'password'     => Hash::make($validated['password']),
            'role'         => $validated['role'],
            'university'   => $validated['university'] ?? null,
            'major'        => $validated['major'] ?? null,
            'degree_level' => $validated['degree_level'] ?? null,
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->to($this->homeRoute());
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->to(Route::has('login') ? route('login') : '/login');
    }

    protected function homeRoute(): string
    {
        return Route::has('dashboard') ? route('dashboard') : '/';
    }
}
