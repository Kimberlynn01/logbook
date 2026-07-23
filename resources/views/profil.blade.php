@extends('layouts.app')

@section('title', 'Profil')
@section('activeMenu', 'profil')

@section('content')
    <div class="px-5 py-6 md:px-10 md:py-10">
        <div class="mb-8">
            <h1 class="font-['Space_Grotesk'] text-2xl font-bold text-[#1A1C1E]">Profil</h1>
        </div>

        <div class="max-w-xl bg-white rounded-lg border border-[#1A1C1E]/[0.12] p-6 md:p-8">
            {{-- Head: avatar + info singkat --}}
            <div class="flex items-center gap-4 mb-8">
                <div
                    class="w-14 h-14 shrink-0 rounded-full bg-[#1A1C1E] text-white flex items-center justify-center font-['Space_Grotesk'] font-bold text-lg">
                    {{ $mentorInitials }}
                </div>
                <div>
                    <p class="font-semibold text-[#1A1C1E]">{{ $user->name }}</p>
                    <p class="text-sm text-[#6C7278]">{{ $user->email }}</p>
                    @if ($role === 'mentor')
                        <p class="text-sm text-[#6C7278]">{{ $user->students()->count() }} mahasiswa bimbingan</p>
                    @endif
                </div>
            </div>

            @if (session('status'))
                <div class="mb-6 px-3 py-2.5 bg-[#1A1C1E]/[0.05] border-l-2 border-[#1A1C1E] text-sm text-[#1A1C1E]">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('profil.update') }}" class="grid gap-5">
                @csrf
                @method('PUT')

                <div class="grid gap-1.5">
                    <label for="name" class="text-sm font-medium text-[#6C7278]">Nama</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                        class="rounded-md border border-[#1A1C1E]/[0.16] px-3 py-2 text-sm text-[#1A1C1E] focus:outline-none focus:border-[#1A1C1E] focus:ring-1 focus:ring-[#1A1C1E]">
                    @error('name')
                        <span class="text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div class="grid gap-1.5">
                    <label for="email" class="text-sm font-medium text-[#6C7278]">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="rounded-md border border-[#1A1C1E]/[0.16] px-3 py-2 text-sm text-[#1A1C1E] focus:outline-none focus:border-[#1A1C1E] focus:ring-1 focus:ring-[#1A1C1E]">
                    @error('email')
                        <span class="text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div class="pt-5 border-t border-[#1A1C1E]/[0.12] grid gap-1.5">
                    <p class="text-xs uppercase tracking-wide text-[#6C7278] mb-1">Ubah Kata Sandi (opsional)</p>
                    <label for="password" class="text-sm font-medium text-[#6C7278]">Kata Sandi Baru</label>
                    <input type="password" id="password" name="password"
                        class="rounded-md border border-[#1A1C1E]/[0.16] px-3 py-2 text-sm text-[#1A1C1E] focus:outline-none focus:border-[#1A1C1E] focus:ring-1 focus:ring-[#1A1C1E]">
                    @error('password')
                        <span class="text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit"
                    class="justify-self-start mt-1 px-5 py-2.5 rounded-md bg-[#1A1C1E] text-white text-sm font-semibold hover:opacity-90 transition">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
@endsection
