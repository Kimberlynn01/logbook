@extends('layouts.auth')

@section('title', 'Masuk')

@section('content')
    <h1 class="mb-1.5 text-[1.3rem] font-extrabold">Masuk ke akun Anda</h1>
    <p class="mb-6 text-[#6C7278] text-[0.9rem]">Silakan masuk untuk melanjutkan ke dashboard mentor.</p>

    @if ($errors->any())
        <div class="border border-[#B8422E] text-[#B8422E] px-3 py-2.5 text-[0.85rem] mb-4">
            <ul class="m-0 pl-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-4">
            <label for="email"
                class="block text-[0.72rem] font-['Space_Grotesk'] uppercase tracking-[0.16em] text-[#6C7278] mb-1.5">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                class="w-full border border-[#1A1C1E]/[0.12] px-3 py-2.5 bg-[#F7F5F2] text-[#1A1C1E] text-[0.92rem] focus:outline-none focus:border-[#1A1C1E]">
        </div>

        <div class="mb-4">
            <label for="password"
                class="block text-[0.72rem] font-['Space_Grotesk'] uppercase tracking-[0.16em] text-[#6C7278] mb-1.5">Kata
                Sandi</label>
            <input type="password" id="password" name="password" required
                class="w-full border border-[#1A1C1E]/[0.12] px-3 py-2.5 bg-[#F7F5F2] text-[#1A1C1E] text-[0.92rem] focus:outline-none focus:border-[#1A1C1E]">
        </div>

        <div class="flex items-center gap-2 mb-5 text-[0.85rem] text-[#6C7278]">
            <input type="checkbox" id="remember" name="remember" class="w-auto">
            <label for="remember" class="m-0 normal-case tracking-normal text-[#6C7278]">Ingat saya</label>
        </div>

        <button type="submit"
            class="w-full border-0 bg-[#1A1C1E] text-[#F7F5F2] px-3.5 py-2.5 font-semibold cursor-pointer text-[0.92rem] hover:opacity-90">
            Masuk
        </button>
    </form>

    <p class="mt-5 text-center text-[0.85rem] text-[#6C7278]">
        Belum punya akun?
        <a href="{{ route('register') }}" class="text-[#1A1C1E] font-semibold no-underline hover:underline">Daftar di
            sini</a>
    </p>
@endsection
