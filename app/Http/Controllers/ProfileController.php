<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        return view('profil', [
            'user' => $user,
            'mentorName' => $user->name,
            'mentorInitials' => $this->initials($user->name),
            'mentorNotifications' => [],
            'role' => $user->role,
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update($validated);

        return back()->with('status', 'Profil berhasil diperbarui.');
    }

    private function initials(string $name): string
    {
        $words = preg_split('/\s+/', trim($name));

        return mb_strtoupper(
            collect($words)->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->implode('')
        );
    }
}
